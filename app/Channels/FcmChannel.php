<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (!$notifiable->fcm_token) {
            return;
        }

        $data = $notification->toFcm($notifiable);
        
        $payload = [
            'message' => [
                'token' => $notifiable->fcm_token,
                'notification' => [
                    'title' => $data['title'],
                    'body' => $data['body'],
                ],
                'data' => array_map('strval', $data['data'] ?? []),
                'android' => [
                    'notification' => [
                        'icon' => 'ic_notification',
                        'color' => '#4F46E5',
                        'sound' => 'default',
                    ],
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                            'badge' => 1,
                        ],
                    ],
                ],
            ],
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Content-Type' => 'application/json',
            ])->post($this->getFcmUrl(), $payload);

            if (!$response->successful()) {
                Log::error('FCM notification failed', [
                    'user_id' => $notifiable->id,
                    'response' => $response->body(),
                ]);
                
                // If token is invalid, clear it
                if ($response->status() === 400) {
                    $notifiable->update(['fcm_token' => null]);
                }
            }

        } catch (\Exception $e) {
            Log::error('FCM notification exception', [
                'user_id' => $notifiable->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function getAccessToken()
    {
        // In a real implementation, you would use Google OAuth2 to get a fresh access token
        // For demo purposes, return the server key (deprecated but still works)
        return config('services.firebase.server_key');
    }

    private function getFcmUrl()
    {
        $projectId = config('services.firebase.project_id');
        return "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";
    }
}