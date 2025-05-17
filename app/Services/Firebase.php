<?php

namespace App\Services;

use App\Models\User;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;

class Firebase
{
    protected string $projectId;
    protected string $credentialsPath;

    public function __construct()
    {
        $this->projectId = 'fitlife-7b8e8';
        $this->credentialsPath = storage_path('firebase_service_account.json');
    }

    protected function getAccessToken(): string
    {
        $scopes = ['https://www.googleapis.com/auth/firebase.messaging'];

        $credentials = new ServiceAccountCredentials($scopes, $this->credentialsPath);
        $token = $credentials->fetchAuthToken();

        return $token['access_token'];
    }

    public function sendNotification(string $deviceToken, string $title, string $body): array
    {
        $accessToken = $this->getAccessToken();

        $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

        $payload = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'android' => [
                    'priority' => 'high',
                    'notification' => [
                        'sound' => 'default',
                    ],
                ],
            ]
        ];

        $response = Http::withToken($accessToken)
            ->post($url, $payload);

        return $response->json();
    }
    public function sendToUser(User $user, string $title, string $body): void
    {
        foreach ($user->deviceTokens as $deviceToken) {
            $this->sendNotification($deviceToken->token, $title, $body);
        }
    }
}