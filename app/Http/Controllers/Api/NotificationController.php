<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Google\Client;
use App\Models\User;
use App\Models\Notification;

class NotificationController extends Controller
{
    private $projectId;
    private $credentialsPath;

    public function __construct()
    {
        $this->projectId = "retali-project"; // ganti sesuai project_id
        $this->credentialsPath = storage_path('app/firebase/retali-project-firebase.json');
    }

    // Ambil daftar notifikasi
    public function list()
    {
        $notifications = Notification::latest()->get();
        return response()->json($notifications);
    }

    // Tandai notifikasi tertentu sudah dibaca
    public function markAsRead($id)
    {
        $notif = Notification::find($id);
        if (!$notif) {
            return response()->json([
                'success' => false,
                'message' => 'Notifikasi tidak ditemukan'
            ], 404);
        }

        $notif->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai sudah dibaca'
        ]);
    }

    // Tandai semua notifikasi sudah dibaca
    public function markAllAsRead()
    {
        Notification::whereNull('read_at')->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Semua notifikasi ditandai sudah dibaca'
        ]);
    }

    // Ambil access token untuk FCM
    private function getAccessToken()
    {
        $client = new Client();
        $client->setAuthConfig($this->credentialsPath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        $token = $client->fetchAccessTokenWithAssertion();
        return $token['access_token'] ?? null;
    }

    // Kirim notifikasi ke semua user yang punya fcm_token
    public function send(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'body'  => 'required|string',
        ]);

        $fcmTokens = User::whereNotNull('fcm_token')->pluck('fcm_token')->toArray();
        if (empty($fcmTokens)) {
            return response()->json(['message' => 'Tidak ada token FCM'], 400);
        }

        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";
        $accessToken = $this->getAccessToken();

        foreach ($fcmTokens as $token) {
            $message = [
                "message" => [
                    "token" => $token,
                    "notification" => [
                        "title" => $data['title'],
                        "body"  => $data['body'],
                    ],
                ],
            ];

            Http::withToken($accessToken)->post($url, $message);
        }

        return response()->json(['message' => 'Notifikasi berhasil dikirim']);
    }
}
