<?php

class GoogleVisionClient {
    private string $apiKey;

    public function __construct(string $apiKey) {
        $this->apiKey = $apiKey;
    }

    public function describe(string $imageUrl): array {
        $endpoint = "https://vision.googleapis.com/v1/images:annotate?key={$this->apiKey}";

        $payload = [
            'requests' => [[
                'image' => ['source' => ['imageUri' => $imageUrl]],
                'features' => [['type' => 'LABEL_DETECTION', 'maxResults' => 10]]
            ]]
        ];

        $curl = curl_init($endpoint);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => ["Content-Type: application/json"]
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response, true);
        $labels = $data['responses'][0]['labelAnnotations'] ?? [];

        return array_map(fn($l) => $l['description'], $labels);
    }
}