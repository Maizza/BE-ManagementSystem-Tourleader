<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Tourleader;

class FCMTokenController extends Controller
{
    // Simpan / update token FCM milik tour leader login
    public function store(Request $request)
    {
        $data = $request->validate([
            'token'    => 'required|string',
            'platform' => 'nullable|string', // 'android' | 'ios' | 'web'
        ]);

        /** @var \App\Models\Tourleader $tourleader */
        $tourleader = $request->user(); // auth:sanctum dengan guard tourleader
        if (!$tourleader instanceof Tourleader) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // simpan ke kolom tour_leaders.fcm_token
        $tourleader->update(['fcm_token' => $data['token']]);

        // (Opsional) subscribe ke topic 'all' untuk MOBILE SAJA
        $platform = strtolower($data['platform'] ?? '');
        if ($platform !== 'web') {
            $serverKey = config('services.fcm.server_key');
            if ($serverKey) {
                Http::withHeaders([
                    'Authorization' => 'key=' . $serverKey,
                    'Content-Type'  => 'application/json',
                ])->post('https://iid.googleapis.com/iid/v1:batchAdd', [
                    'to' => '/topics/all',
                    'registration_tokens' => [$data['token']],
                ]);
            }
        }

        return response()->json(['success' => true]);
    }

    // Hapus token FCM tour leader login (misal saat logout)
    public function destroy(Request $request)
    {
        /** @var \App\Models\Tourleader $tourleader */
        $tourleader = $request->user();
        if (!$tourleader instanceof Tourleader) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $oldToken = $tourleader->fcm_token;
        $tourleader->update(['fcm_token' => null]);

        // (Opsional) un-subscribe dari topic 'all' (mobile saja)
        if ($oldToken) {
            $serverKey = config('services.fcm.server_key');
            if ($serverKey) {
                Http::withHeaders([
                    'Authorization' => 'key=' . $serverKey,
                    'Content-Type'  => 'application/json',
                ])->post('https://iid.googleapis.com/iid/v1:batchRemove', [
                    'to' => '/topics/all',
                    'registration_tokens' => [$oldToken],
                ]);
            }
        }

        return response()->json(['success' => true]);
    }
}
