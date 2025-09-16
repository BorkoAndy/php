<?php

require_once 'ImageDescriberClient.php';
require_once 'GoogleVisionClient.php';

class VisionService {
    private string $provider;
    private ImageDescriberClient $imageDescriber;
    private GoogleVisionClient $googleVision;

    public function __construct(string $provider, array $config) {
        $this->provider = $provider;
        $this->imageDescriber = new ImageDescriberClient($config['imagedescriber_api_key']);
        $this->googleVision = new GoogleVisionClient($config['google_vision_api_key']);
    }

    public function describe(string $imageUrl): array {
        if ($this->provider === 'imagedescriber') {
            return $this->imageDescriber->describe($imageUrl);
        } elseif ($this->provider === 'google') {
            return $this->googleVision->describe($imageUrl);
        } else {
            throw new Exception('Unknown vision provider');
        }
    }
}