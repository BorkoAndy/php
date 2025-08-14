<?php
namespace Classes;
include "../admin_signup_login/classes/vehicle.classes.php";
include "../admin_signup_login/classes/dbh.classes.php";
//if user = root -> give all access
//if user = stuff -> give restricted access
if(isset($_POST['submit'])){    
    switch ($_POST['form_name']) {
      case "add_car":
        echo "Add a car";
        $car = new Vehicle($_POST['car_brand'], $_POST['model'], $_POST['year'], $_POST['license_plate'], $_POST['status'], $_POST['price_per_day'], $_POST['category'], $_POST['car_photo']);
        $var = $car->add_car();
        echo $var;
        break;
      case "add_category":
        echo "add category";
        break;
      case "add_customer":
        echo "add customer";
        break;
      case "add_maintenance":
        echo "add maintenance";
        break;
      case "add_payment":
        echo "add payment";
        break;
      case "add_rental":
        echo "add rental";
        break;
      default:
      echo "Uncorrect request";
    }
}else{
    echo "not a post request";
}


//File upload - NOT CHECKED
// $target_dir = "uploads/";
// $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
// $uploadOk = 1;
// $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// // Check if image file is a actual image or fake image
// if(isset($_POST["submit"])) {
//   $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
//   if($check !== false) {
//     echo "File is an image - " . $check["mime"] . ".";
//     $uploadOk = 1;
//   } else {
//     echo "File is not an image.";
//     $uploadOk = 0;
//   }
// }

// // Check if file already exists
// if (file_exists($target_file)) {
//   echo "Sorry, file already exists.";
//   $uploadOk = 0;
// }

// // Check file size
// if ($_FILES["fileToUpload"]["size"] > 500000) {
//   echo "Sorry, your file is too large.";
//   $uploadOk = 0;
// }

// // Allow certain file formats
// if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
// && $imageFileType != "gif" ) {
//   echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
//   $uploadOk = 0;
// }

// // Check if $uploadOk is set to 0 by an error
// if ($uploadOk == 0) {
//   echo "Sorry, your file was not uploaded.";
// // if everything is ok, try to upload file
// } else {
//   if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
//     echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
//   } else {
//     echo "Sorry, there was an error uploading your file.";
//   }
// }

//Endof File Upload
?>

<!-- Add a car -->
<form action="" method="post" name="add_car">
    <input type="hidden" name="form_name" value="add_car">
    <select name="category" id=""></select>
    <input type="text" name="car_brand" placeholder="Brand" required>
    <input type="text" name="model" placeholder="Model" required>
    <input type="text" name="year" placeholder="Year" required>
    <input type="text" name="license_plate" placeholder="License Plate">
    <input type="text" name="status" placeholder="Status" required>
    <input type="text" name="price_per_day" placeholder="Price per day" required>
    <input type="text" name="car_photo" placeholder="Photo">
    <button type="submit" name="submit">Add car</button>
</form>


<!-- Add or delete category -->
<form action="" method="post" name="add_category">
  <input type="hidden" name="form_name" value="add_category">
    <label for="new_category">Add a vehicle category</label>
    <input name ="new_category" type="text" placeholder="vehicle type" required>
    <!-- Selection with categories in order to delete -->
    <select name="categories_list" id="">
        <!-- loop through DB -->
        <option value="1"></option>

    </select>
    <button type="submit"  name="submit">Add category</button>
</form>


<!-- Add customers -->
<form action="" method="post">
    <input type="hidden" name="form_name" value="add_customer">
    <input type="text" name="first_name" placeholder="First name" required>
    <input type="text" name="last_name" placeholder="Last name" required>
    <input type="text" name="phone" placeholder="Phone number" required>
    <input type="text" name="email" placeholder="E-mail" required>
    <input type="text" name="driver_license_number" placeholder="Driver license number" required>
    <button type="submit" name="submit">Add customer</button>
</form>

<!-- Add maintenance  -->
<form action="" method="post">
    <input type="hidden" name="form_name" value="add_maintenance">
    <select name="car_id" id="">

    </select>
    <input type="text" name="maintenance_description" placeholder="Description" required>
    <input type="text" name="maintenance_cost" placeholder="Cost" required>
    <input type="date" name="maintenance_date" placeholder="Date" required>
    <button type="submit" name="submit">Add maintenance</button>
</form>

<!-- Add payments -->
<form action="" method="post">
    <input type="hidden" name="form_name" value="add_payment">
    <select name="rental_id" id="" required></select>
    <input type="text" name="amout" placeholder="Amount" required>
    <input type="text" name="payment_date" placeholder="Payment date" required>
    <input type="text" name="discount" placeholder="Discount">
    <select name="payment_method" id="" required>
        <option value="card">Credit card</option>
        <option value="cash">Cash</option>
        <option value="bank_transfer">Bank transfer</option>
    </select>
    <button type="submit" name="submit">Add payment</button>
 </form>

<!-- Add rentals -->
<form action="">
    <input type="hidden" name="form_name" value="add_rental">
    <select name="car_id" id="">

    </select>
    <select name="customr_id" id="">

    </select>
    <input type="date" name="start_date">
    <input type="date" name="end_date">
    <input type="text" name="total_price">
    <select name="rental_status" id="" required>
        <option value="reserved">Reserved</option>
        <option value="in_progress">In progress</option>
        <option value="completed">Completed</option>
        <option value="canceled">Canceled</option>
    </select>
    <button type="submit" name="submit">Add rental</button>
</form>