<?php
require_once 'SeoContent.php';

class TextSynthService {
    private $apiKey;

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function generateSeoContent($labels) {
        $prompt = "Generate an SEO-friendly title and alt-text for an image with labels: " . implode(', ', $labels) . ".";

        $payload = json_encode([
            'prompt' => $prompt,
            'model' => 'gptj',
            'temperature' => 0.7,
            'max_tokens' => 150
        ]);

        $ch = curl_init("https://api.textsynth.com/v1/engines/gptj/completions");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->apiKey}",
                "Content-Type: application/json"
            ]
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        $text = $data['text'] ?? '';

        $title = $this->extractLine($text, 'Title:');
        $altText = $this->extractLine($text, 'Alt-text:');

        return new SeoContent($title, $altText); // ✅ Now inside the method
    }

    public function generateSeoContentFromPrompt($prompt) {
        $prompt = <<<EOT
You are an SEO expert. Based on the following image description, generate:

Title: [Write a short, SEO-friendly title]
Alt-text: [Write a descriptive alt-text for the image]

Image description: {$description}
Domain: {$domain}
EOT;
    $payload = json_encode([
        'prompt' => $prompt,
        'model' => 'mistral_7B',
        'temperature' => 0.7,
        'max_tokens' => 150
    ]);
    echo $prompt."<br/>";
    $ch = curl_init("https://api.textsynth.com/v1/engines/mistral_7B/completions");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer {$this->apiKey}",
            "Content-Type: application/json"
        ]
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    print_r($data);
    $text = $data['text'] ?? '';

    $title = $this->extractLine($text, 'Title:');
    $altText = $this->extractLine($text, 'Alt-text:');

    return new SeoContent($title, $altText);
}

    private function extractLine($text, $prefix) {
        foreach (explode("\n", $text) as $line) {
            if (strpos($line, $prefix) === 0) {
                return trim(str_replace($prefix, '', $line));
            }
        }
        return '';
    }
}