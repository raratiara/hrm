<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Firebase\JWT\JWT;

class Notification_model extends CI_Model
{
    private array $firebase;

    public function __construct()
    {
        parent::__construct();
        $this->config->load('firebase');
        $this->firebase = $this->config->item('firebase');
    }

    /**
     * ===============================
     * GET GOOGLE OAUTH ACCESS TOKEN
     * ===============================
     */
    private function getAccessToken(): array
    {
        if (
            empty($this->firebase['service_account']) ||
            !file_exists($this->firebase['service_account'])
        ) {
            return [
                'success' => false,
                'error'   => 'service_account_file_not_found'
            ];
        }

        $jsonKey = json_decode(
            file_get_contents($this->firebase['service_account']),
            true
        );

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'error'   => 'invalid_service_account_json',
                'detail'  => json_last_error_msg()
            ];
        }

        if (empty($jsonKey['client_email']) || empty($jsonKey['private_key'])) {
            return [
                'success' => false,
                'error'   => 'service_account_missing_fields'
            ];
        }

        // normalize private key
        $privateKey = str_replace("\\n", "\n", $jsonKey['private_key']);

        $payload = [
            'iss'   => $jsonKey['client_email'],
            'sub'   => $jsonKey['client_email'],
            'aud'   => $this->firebase['token_uri'],
            'iat'   => time(),
            'exp'   => time() + 3600,
            'scope' => $this->firebase['scope']
        ];

        try {
            $jwt = JWT::encode($payload, $privateKey, 'RS256');
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'error'   => 'jwt_encode_failed',
                'detail'  => $e->getMessage()
            ];
        }

        $context = stream_context_create([
            'http' => [
                'method'        => 'POST',
                'header'        => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content'       => http_build_query([
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion'  => $jwt
                ]),
                'ignore_errors' => true
            ]
        ]);

        $response = file_get_contents(
            $this->firebase['token_uri'],
            false,
            $context
        );

        // Ambil HTTP status code
        $httpCode = null;
        if (isset($http_response_header[0])) {
            preg_match('{HTTP\/\S*\s(\d{3})}', $http_response_header[0], $match);
            $httpCode = $match[1] ?? null;
        }

        if ($response === false) {
            return [
                'success' => false,
                'error'   => 'oauth_request_failed',
                'http_code' => $httpCode
            ];
        }

        $result = json_decode($response, true);

        if ($httpCode !== '200') {
            return [
                'success'    => false,
                'http_code'  => $httpCode,
                'error'      => $result['error'] ?? 'oauth_error',
                'error_desc' => $result['error_description'] ?? null,
                'raw'        => $result
            ];
        }

        return [
            'success'      => true,
            'token'        => $result['access_token'],
            'expires_in'   => $result['expires_in'] ?? null,
            'token_type'   => $result['token_type'] ?? null
        ];
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
        $tokenResult = $this->getAccessToken();

        if (!$tokenResult['success']) {
            return $tokenResult;
        }

        $payload = [
            'message' => [
                'token' => $fcmToken,
                'notification' => [
                    'title' => $title,
                    'body'  => $body
                ],
                'data' => array_map('strval', $data)
            ]
        ];

        $ch = curl_init(
            "https://fcm.googleapis.com/v1/projects/{$this->firebase['project_id']}/messages:send"
        );

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$tokenResult['token']}",
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
                'message' => $error
            ];
        }

        $decoded = json_decode($response, true);

        if (isset($decoded['error'])) {
            return [
                'success' => false,
                'message' => $decoded['error']['message'] ?? 'FCM error',
                'error'   => $decoded['error']
            ];
        }

        return [
            'success' => true,
            'response' => $decoded
        ];
    }
}
