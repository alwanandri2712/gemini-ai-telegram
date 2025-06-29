<?php

require_once __DIR__ . '/autoload.php';

// Read input from Telegram
$update = json_decode(file_get_contents('php://input'), true);

// Process commands
if (isset($update['message'])) {
    $chat_id = $update['message']['chat']['id'];
    $message_text = $update['message']['text'];
    $telegram = new TelegramBot();

    if (strpos($message_text, '/ai') === 0) {
        processAiCommand($chat_id, $message_text, $telegram);
    } elseif (strpos($message_text, '/cuaca') === 0) {
        processWeatherCommand($chat_id, $message_text, $telegram);
    } else {
        $telegram->sendMessage($chat_id, "Gunakan perintah /ai diikuti dengan pertanyaan Anda, atau /cuaca untuk informasi cuaca.");
    }
}

// Respond to Telegram
http_response_code(200);

function processAiCommand($chat_id, $message_text, $telegram) 
{
    $prompt = substr($message_text, 4); // Remove "/ai " from the message

    try {
        $gemini = new GeminiAPI();
        $response = $gemini->generateContent($prompt);

        if (isset($response['candidates'][0]['content']['parts'][0]['text'])) {
            $result = $response['candidates'][0]['content']['parts'][0]['text'];
            $formatted_result = Formatter::formatResponse($result);
            $telegram->sendMessage($chat_id, $formatted_result);
        } else {
            $telegram->sendMessage($chat_id, "Tidak ada respons yang valid dari API.");
        }
    } catch (Exception $e) {
        $telegram->sendMessage($chat_id, "Error: " . $e->getMessage());
    }
}

function processWeatherCommand($chat_id, $message_text, $telegram) 
{
    $location = trim(substr($message_text, 7)); // Remove "/cuaca " from the message

    if (empty($location)) {
        $telegram->sendMessage($chat_id, "Gunakan format: /cuaca {lokasi}");
        return;
    }

    try {
        $weather = new WeatherAPI();
        $weather_data = $weather->getWeather($location);

        if (isset($weather_data['main']) && isset($weather_data['weather'][0])) {
            $temp_c = round($weather_data['main']['temp'], 1);
            $description = $weather_data['weather'][0]['description'];
            $city = $weather_data['name'];
            $country = $weather_data['sys']['country'];

            $weather_message = "Cuaca di {$city}, {$country}:\n"
                . "Suhu: {$temp_c}°C\n"
                . "Kondisi: {$description}";

            $telegram->sendMessage($chat_id, $weather_message);
        } else {
            $telegram->sendMessage($chat_id, "Tidak dapat menemukan data cuaca untuk lokasi tersebut.");
        }
    } catch (Exception $e) {
        $telegram->sendMessage($chat_id, "Error: " . $e->getMessage());
    }
}
