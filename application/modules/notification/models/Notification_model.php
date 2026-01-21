<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Firebase\JWT\JWT;

class Notification_model extends CI_Model
{
    private $firebase;

    public function __construct()
    {
        parent::__construct();
        $this->config->load('notification/firebase', TRUE);
        $this->firebase = $this->config->item('firebase', 'notification');
    }

    /**
     * ===============================
     * GOOGLE OAUTH - ACCESS TOKEN
     * ===============================
     */
    private function getAccessToken(): ?string
    {
        if (
            empty($this->firebase) ||
            empty($this->firebase['service_account']) ||
            !file_exists($this->firebase['service_account'])
        ) {
            log_message('error', 'Firebase config / service account not found');
            return null;
        }

        $jsonKey = json_decode(
            file_get_contents($this->firebase['service_account']),
            true
        );

        if (!$jsonKey || empty($jsonKey['client_email'])) {
            log_message('error', 'Invalid Firebase service account JSON');
            return null;
        }

        $payload = [
            'iss'   => $jsonKey['client_email'],
            'sub'   => $jsonKey['client_email'],
            'aud'   => $this->firebase['token_uri'],
            'iat'   => time(),
            'exp'   => time() + 3600,
            'scope' => $this->firebase['scope']
        ];

        $jwt = JWT::encode($payload, $jsonKey['private_key'], 'RS256');

        $context = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query([
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion'  => $jwt
                ])
            ]
        ]);

        $response = file_get_contents(
            $this->firebase['token_uri'],
            false,
            $context
        );

        $result = json_decode($response, true);

        return $result['access_token'] ?? null;
    }

    /**
     * ===============================
     * SEND FCM PUSH
     * ===============================
     */
    public function sendNotification(
        string $fcmToken,
        string $title,
        string $body,
        array $data = []
    ): array {
        $accessToken = $this->getAccessToken();

        if (!$accessToken) {
            return [
                'success' => false,
                'error'   => 'Failed to get access token'
            ];
        }

        $payload = [
            'message' => [
                'token' => $fcmToken,
                'notification' => [
                    'title' => $title,
                    'body'  => $body
                ],
                'data' => array_map('strval', $data) // FCM wajib string
            ]
        ];

        $ch = curl_init(
            "https://fcm.googleapis.com/v1/projects/{$this->firebase['project_id']}/messages:send"
        );

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$accessToken}",
                "Content-Type: application/json"
            ],
            CURLOPT_POSTFIELDS => json_encode($payload)
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return [
                'success' => false,
                'error'   => $error
            ];
        }

        return [
            'success' => true,
            'response' => json_decode($response, true)
        ];
    }
}
