<?php

class TelegramBot
{
    private $bot_token;

    public function __construct()
    {
        $config = require __DIR__ . '/../../config/config.php';
        $this->bot_token = $config['telegram']['bot_token'];
    }

    public function sendMessage($chat_id, $message)
    {
        $url = "https://api.telegram.org/bot{$this->bot_token}/sendMessage";
        $data = [
            'chat_id' => $chat_id,
            'text' => $message,
            'parse_mode' => 'HTML'
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data)
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }
}
