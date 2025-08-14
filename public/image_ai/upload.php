<?php
// if(isset($_FILES['image'])){
//     $file = $_FILES['image'];
    
    
//     3. Move file to upload folder
//     $uploadDir = 'uploads/';
//     if(!file_exists($uploadDir)){
//         mkdir($uploadDir, 0755, true);
//     }
//     $targetFile = $uploadDir . basename($file['name']);
    
//     if(move_uploaded_file($file['tmp_name'], $targetFile)){
//         echo json_encode(['success' => true, 'file' => $targetFile]);
//     } else {
//         echo json_encode(['error' => 'Failed to upload file']);
//     }
    
// }
?>
<?php
// upload_to_api.php

$apiUrl = "https://172.23.48.1:32769//api/Image/upload"; // change to your API URL

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $filePath = $_FILES['image']['tmp_name'];
    $fileName = $_FILES['image']['name'];

    $allowedTypes = [
    'image/jpeg',
    'image/png',
    'image/gif',
    'image/webp',
    'image/svg+xml'
];
    
    // 1. Verify MIME type
    if(!in_array($file['type'], $allowedTypes)){
        die(json_encode(['error' => 'Invalid file type']));
    }
    
    // 2. Verify file size (max 5MB for example)
    if($file['size'] > 5 * 1024 * 1024){
        die(json_encode(['error' => 'File too large']));
    }

    // Prepare CURL request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'file' => new CURLFile($filePath, mime_content_type($filePath), $fileName)
    ]);

    // For local HTTPS testing — disable SSL verify (NOT for production)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "Error: " . curl_error($ch);
    } else {
        echo "API Response: " . $response;
    }

    curl_close($ch);
} else {
    echo "No file uploaded or an error occurred.";
}
