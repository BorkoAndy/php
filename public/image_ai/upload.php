<?php
if(isset($_FILES['image'])){
    $file = $_FILES['image'];
    $allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/svg+xml'
    ];

    if(!in_array($file['type'], $allowedTypes)){
        die(json_encode(['error' => 'Invalid file type']));
    }
    if($file['size'] > 5 * 1024 * 1024){
        die(json_encode(['error' => 'File too large']));
    }
    header('Content-Type: application/json'); // tell browser this is JSON

    $apiUrl = "https://localhost:5000/api/Image/upload"; // replace with your local IP

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $filePath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'file' => new CURLFile($filePath, mime_content_type($filePath), $fileName)
        ]);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // local HTTPS only
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            echo $response; // API returned JSON
        } else {
            echo json_encode([
                'error' => "API returned HTTP $httpCode",
                'raw' => $response
            ]);
        }
    } else {
        echo json_encode(['error' => 'No file uploaded or an error occurred.']);
    }
}