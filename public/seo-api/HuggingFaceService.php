<?php

class HuggingFaceService
{
    private string $token;
    private string $model;

    public function __construct(string $token, string $model = 'facebook/bart-large-cnn')
    {
        $this->token = $token;
        $this->model = $model;
    }

    public function generateAltText(array $labels): string
    {
        $description = implode(', ', $labels);

        // 🧠 Hardcoded summarization prompt
        $inputText = "Generate a short alt-text in German (max 125 characters) for an image showing: $description.";

        $endpoint = "https://api-inference.huggingface.co/models/{$this->model}";

        $payload = json_encode(['inputs' => $inputText]);

        $headers = [
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/json'
        ];

        $ch = curl_init($endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => $headers
        ]);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            return "❌ cURL error: $error";
        }

        $data = json_decode($response, true);

        if (isset($data[0]['summary_text'])) {
            return mb_substr($data[0]['summary_text'], 0, 125);
        }

        return "Alt-Text nicht verfügbar";
    }
}