<?php
class ImageFetcher {
    public function fetch($url) {
        $tempPath = 'temp_image.jpg';
        file_put_contents($tempPath, file_get_contents($url));
        return $tempPath;
    }
}