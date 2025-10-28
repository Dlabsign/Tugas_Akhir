<?php

namespace app\components;

use yii\base\Component;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GeminiApi extends Component
{
    public $apiKey;
    public $model = "gemini-2.5-pro";  // atau “gemini-2.5-pro” tergantung model yang Anda akses

    public function generateText(string $prompt): ?string
    {
        $client = new Client();
        // Gunakan v1 (bukan v1beta)
        $url = "https://generativelanguage.googleapis.com/v1/models/{$this->model}:generateContent?key={$this->apiKey}";
        $postData = [
            "contents" => [[
                "parts" => [[
                    "text" => $prompt
                ]]
            ]]
        ];
        
        try {
            $response = $client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'json' => $postData,
            ]);
            $data = json_decode($response->getBody()->getContents(), true);
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
        } catch (RequestException $ex) {
            // Bila error, bisa log atau debug isi responsnya
            \Yii::error("Gemini API Error: " . $ex->getMessage());
            if ($ex->hasResponse()) {
                $body = $ex->getResponse()->getBody()->getContents();
                \Yii::error("Gemini API Response Body: " . $body);
            }
            return null;
        }
    }
}
