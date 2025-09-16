<?php

require_once 'VisionService.php';
require_once 'HuggingFaceService.php';
require_once 'SeoContent.php';

class SeoController {
    private $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        $imageUrl = $_POST['image_url'] ?? '';
        $domain = $_POST['domain'] ?? '';
        $provider = $_POST['vision_provider'] ?? 'google';

        if (empty($imageUrl) || empty($domain)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing image URL or domain']);
            return;
        }

        // Step 1: Get labels from Vision API
        $vision = new VisionService($provider, $this->config);
        $labels = $vision->describe($imageUrl);

        // Step 2: Generate alt-text using Hugging Face
        $hfToken = $this->config['huggingface_token'];
        $hfService = new HuggingFaceService($hfToken);
        $altText = $hfService->generateAltText($labels);

        // Step 3: Return JSON response
        $result = new SeoContent("Title", $altText);
        header('Content-Type: application/json');
        echo json_encode($result->toArray());
    }
}