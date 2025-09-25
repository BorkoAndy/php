<?php
require 'res\php\db_config.php';
$sql = "INSERT INTO users (username, password, role) 
        VALUES (:username, :password, :role)";
$stmt = $pdo->prepare($sql);

// Hash the password before storing
$hashedPassword = password_hash('09andy21', PASSWORD_DEFAULT);

// Bind values
$stmt->execute([
    ':username' => "andrej",    
    ':password' => $hashedPassword,
    ':role' => 'superuser' // e.g. 'user', 'admin', etc.
]);

echo "User 'andrej' added successfully.";