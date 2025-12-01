<?php

namespace App\Services;

use Google\Client;
use Illuminate\Support\Facades\Http;

class FirebaseService
{
    private string $projectId;
    private Client $client;

    public function __construct()
    {
        $this->projectId = config('services.firebase.project_id');

        $this->client = new Client();
        $this->client->setAuthConfig(storage_path(config('services.firebase.credentials')));
        $this->client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    }

    private function accessToken(): string
    {
        $token = $this->client->fetchAccessTokenWithAssertion();
        return $token['access_token'];
    }

    public function sendToToken(string $token, string $title, string $body, array $data = []): array
    {
        $payload = [
            'message' => [
                'token'        => $token,
                'notification' => ['title' => $title, 'body' => $body],
                'data'         => $data,
            ],
        ];

        return Http::withToken($this->accessToken())
            ->post("https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send", $payload)
            ->json();
    }

    public function sendToTopic(string $topic, string $title, string $body, array $data = []): array
    {
        $payload = [
            'message' => [
                'topic'        => $topic,
                'notification' => ['title' => $title, 'body' => $body],
                'data'         => $data,
            ],
        ];

        return Http::withToken($this->accessToken())
            ->post("https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send", $payload)
            ->json();
    }
}
