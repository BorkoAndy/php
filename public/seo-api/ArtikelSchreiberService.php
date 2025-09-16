<?php

class ArtikelSchreiberService {
  public function generateSeoContent($keywords, $language = 'en') {
    $endpoint = 'https://www.artikelschreiber.com/api/articleapi.php';
    $url = $endpoint . '?language=' . urlencode($language) . '&keywords=' . urlencode($keywords);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; SEOBot/1.0)',
        CURLOPT_TIMEOUT => 10
    ]);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        error_log("ArtikelSchreiber API error: " . $error);
        return new SeoContent('Untitled', 'No description available');
    }

    $data = json_decode($response, true);
    return new SeoContent($data['title'] ?? 'Untitled', $data['meta_description'] ?? 'No description available');
}
}