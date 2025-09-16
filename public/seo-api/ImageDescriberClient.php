<?php

class ImageDescriberClient {
    private string $apiKey;

    public function __construct(string $apiKey) {
        $this->apiKey = $apiKey;
    }

    public function describe(string $imageUrl): array {
        // Stubbed response for testing
        return ['Beach ball', 'Sunscreen', 'Sandcastle', 'Flip flops', 'Ocean waves'];
    }
}