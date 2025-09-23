<?php
require 'db_config.php';
session_start();

// Helper: sanitize input
function clean($value) {
  return htmlspecialchars(trim($value));
}

// Required fields
$user_role     = $_POST['user_role'] ?? 'regular';
$company_name  = clean($_POST['company_name']);
$first_name    = clean($_POST['first_name']);
$last_name     = clean($_POST['last_name']);
$phone_number  = clean($_POST['phone_number']);
$email         = clean($_POST['email']);
$street        = clean($_POST['street']);
$city          = clean($_POST['city']);
$postal_code   = clean($_POST['postal_code']);
$country       = clean($_POST['country']);
$vat_number    = clean($_POST['vat_number'] ?? '');
$password      = $_POST['password'];
$password_repeat = $_POST['password_repeat'];

// Optional checkboxes
$optional      = $_POST['optional'] ?? [];
$wants_common_info    = in_array('common', $optional) ? 1 : 0;
$wants_sales_info     = in_array('sales', $optional) ? 1 : 0;
$wants_technical_info = in_array('technical', $optional) ? 1 : 0;
$other_info    = clean($_POST['other_info'] ?? '');

// Legal
$terms_accepted = isset($_POST['terms']) ? 1 : 0;
if (!$terms_accepted) {
  die("Sie müssen die Nutzungsbedingungen und die Datenschutzerklärung akzeptieren.");
}

// Password match
if ($password !== $password_repeat) {
  die("Die Passwörter stimmen nicht überein.");
}
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// File upload
// $trade_license_path = '';
// if (isset($_FILES['trade_license']) && $_FILES['trade_license']['error'] === UPLOAD_ERR_OK) {
//   $file_tmp  = $_FILES['trade_license']['tmp_name'];
//   $file_name = basename($_FILES['trade_license']['name']);
//   $file_type = mime_content_type($file_tmp);

//   $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
//   if (!in_array($file_type, $allowed_types)) {
//     die("Trade license must be an image (JPG, PNG, GIF).");
//   }

//   $upload_dir = 'uploads/';
//   if (!is_dir($upload_dir)) {
//     mkdir($upload_dir, 0755, true);
//   }

//   $new_path = $upload_dir . uniqid() . '_' . $file_name;
//   if (move_uploaded_file($file_tmp, $new_path)) {
//     $trade_license_path = $new_path;
//   } else {
//     die("Failed to upload trade license.");
//   }
// } else {
//   die("Trade license upload is required.");
// }




// Check if email already exists
$check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
$check->execute([':email' => $email]);

if ($check->fetchColumn() > 0) {
    $_SESSION['signup_error'] = 'This email is already registered.';
    $_SESSION['form_data'] = $_POST;
    header("Location: signup.php");
    exit;
}

// Insert into DB
$sql = "INSERT INTO users (
  user_role, company_name, first_name, last_name, phone_number, email,
  street, city, postal_code, country, vat_number, password_hash,
  wants_common_info, wants_sales_info, wants_technical_info, other_info,
  trade_license_path, terms_accepted
) VALUES (
  :user_role, :company_name, :first_name, :last_name, :phone_number, :email,
  :street, :city, :postal_code, :country, :vat_number, :password_hash,
  :wants_common_info, :wants_sales_info, :wants_technical_info, :other_info,
  :trade_license_path, :terms_accepted
)";

$stmt = $pdo->prepare($sql);
if (
$stmt->execute([
  ':user_role' => $user_role,
  ':company_name' => $company_name,
  ':first_name' => $first_name,
  ':last_name' => $last_name,
  ':phone_number' => $phone_number,
  ':email' => $email,
  ':street' => $street,
  ':city' => $city,
  ':postal_code' => $postal_code,
  ':country' => $country,
  ':vat_number' => $vat_number,
  ':password_hash' => $password_hash,
  ':wants_common_info' => $wants_common_info,
  ':wants_sales_info' => $wants_sales_info,
  ':wants_technical_info' => $wants_technical_info,
  ':other_info' => $other_info,
  ':trade_license_path' => $trade_license_path,
  ':terms_accepted' => $terms_accepted
])
) {
// Redirect or confirm
header("Location: ../../index.php");
exit;
} else {
  // Handle failure
  echo "Signup failed. Please try again.";
}
