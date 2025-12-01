<?php

namespace App\Http\Controllers;

use Google\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Notification;
use Google\Client as GoogleClient;

class NotificationController extends Controller
{
    private $projectId;
    private $credentialsPath;

    public function __construct()
    {
        $this->projectId = "retali-project"; // ganti sesuai project_id FCM
        $this->credentialsPath = storage_path('app/firebase/retali-project-firebase.json');
    }

    private function getAccessToken()
{
    $client = new GoogleClient();
    $client->setAuthConfig(storage_path('app/firebase/retali-project-firebase.json'));
    $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    $client->refreshTokenWithAssertion();
    $token = $client->getAccessToken();
    return $token['access_token'];
}

    // Tampilkan semua notifikasi
    public function index()
    {
        $notifications = Notification::latest()->get();
        return view('admin.notifications.index', compact('notifications'));
    }

    // Tampilkan form buat notifikasi baru
    public function create()
    {
        return view('admin.notifications.create');
    }

    // simpan notifikasi dan kirim FCM
    public function sendNotification(Request $request)
    {
        $request->validate([
            'fcm_token' => 'nullable|string',
            'title' => 'required|string',
            'message' => 'required|string',
        ]);

        // simpan ke database
        $notification = Notification::create([
            'title' => $request->title,
            'message' => $request->message,
            'is_active' => true,
        ]);

        // kirim ke FCM kalau ada token
        if ($request->fcm_token) {
            $fcmToken = $request->fcm_token;

            $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

            $message = [
    "message" => [
        "token" => $fcmToken,
        "notification" => [
            "title" => $request->title,
            "body" => $request->message,
        ],
        "android" => [
            "priority" => "high",
            "notification" => [
                "sound" => "default",
                "channel_id" => "default_channel"
            ]
        ],
        "apns" => [
            "payload" => [
                "aps" => [
                    "sound" => "default",
                    "content-available" => 1
                ]
            ]
        ]
    ],
];

            $response = Http::withToken($this->getAccessToken())
                ->post($url, $message);
        }

        return redirect()->back()->with('success', 'Notifikasi berhasil dibuat!');
    }
}
