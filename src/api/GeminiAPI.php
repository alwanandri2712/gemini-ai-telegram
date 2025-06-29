<?php

class GeminiAPI
{
    private $api_key;
    private $model;
    private $url;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/config.php';
        $this->api_key = $config['gemini']['api_key'];
        $this->model = $config['gemini']['model'];
        $this->url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->api_key}";
    }

    public function generateContent($prompt)
    {
        $data = [
            "contents" => [
                [
                    "parts" => [
                        [
                            "text" => $prompt
                        ]
                    ]
                ]
            ]
        ];

        $ch = curl_init($this->url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json']
        ]);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception('cURL Error: ' . curl_error($ch));
        }

        curl_close($ch);

        return json_decode($result, true);
    }
}
