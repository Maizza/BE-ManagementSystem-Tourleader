<?php
// app/Http/Controllers/Api/TourLeaderController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Tourleader;

class TourLeaderController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // ⬅️ ambil beserta relasi kloter
        $tl = Tourleader::with('kloter')->where('email', $request->email)->first();

        if (!$tl || !Hash::check($request->password, $tl->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        $token = $tl->createToken('tourleader_token')->plainTextToken;

        // ⬅️ bentuk payload user yang jelas untuk app
        return response()->json([
            'status'  => true,
            'message' => 'Login berhasil',
            'token'   => $token,
            'user'    => [
                'id'              => $tl->id,
                'name'            => $tl->name,
                'email'           => $tl->email,
                'fcm_token'       => $tl->fcm_token,
                'kloter_id'       => $tl->kloter_id,
                'kloter'          => $tl->kloter?->nama,
                'kloter_tanggal'  => $tl->kloter?->tanggal,
            ],
        ], 200);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user()->load('kloter'));
    }
}
