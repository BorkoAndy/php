
<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "booking_test";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Database connected successfully";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    var_dump($_REQUEST['arrival']);
    $room = htmlspecialchars($_REQUEST['rooms']);
    $arrive = htmlspecialchars($_REQUEST['arrival']);
    $departure = htmlspecialchars($_REQUEST['departure']);
    $adults = htmlspecialchars($_REQUEST['adults']);
    $children = htmlspecialchars($_REQUEST['children']);
    $salutation = htmlspecialchars($_REQUEST['salutation']);
    $first_name = htmlspecialchars($_REQUEST['first_name']);
    $last_name = htmlspecialchars($_REQUEST['last_name']);
    $email = htmlspecialchars($_REQUEST['email']);
    $phone = htmlspecialchars($_REQUEST['phone']);
    $address = htmlspecialchars($_REQUEST['address']);
    $post_index = htmlspecialchars($_REQUEST['post_index']);
    $city = htmlspecialchars($_REQUEST['city']);
    $land = htmlspecialchars($_REQUEST['land']);
    
 

    $query = "insert into booking_details (adult, children, room, arrival, departure) 
                values ('$adults', '$children', '$room', '$arrive', '$departure')"; 
    mysqli_query($conn, $query);
    $query = "insert into booking_users_details (salutation, first_name, last_name, email, phone, street, plz, city, land) 
                values ('$salutation', '$first_name', '$last_name', '$email', '$phone', '$address', '$post_index', '$city', '$land')";
    mysqli_query($conn, $query);
    echo mysqli_errno($conn);
}



?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        #form_container {
            display: flex;
            justify-content: center;
        }
        
        form{
            display: flex;
            flex-direction: column;
        }
        input,
        select,
        button {
            width: 200px;
        }
    </style>

</head>
<body>
    <div id="form_container">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <label for="arrival">Arrival</label>
                <input type="date" id="arrival" name="arrival" required>
            <label for="departure">Departure</label>
                <input type="date" id="departure" name="departure" required>
            <label for="rooms">Rooms</label>
                <select name="rooms" id="rooms">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
            <label for="adults">Adults</label>
                <input type="text" id="adults" placeholder="2" name="adults" required>
            <label for="children">Children</label>
                <input type="text" id="children" name="children">
            <label for="salutation">Salutation</label>
                <select name="salutation" id="salutation">
                    <option value="mr">Mr.</option>
                    <option value="mrs">Mrs.</option>
                    <option value="ms">Ms.</option>
                    <option value="div">Div.</option>
                </select>
            <label for="first_name">First name</label>
                <input type="text" id="first_name" name="first_name" required>
            <label for="last_name">Last name</label>
                <input type="text" id="last_name" name="last_name" required>
            <label for="email">E-mail</label>
                <input type="text" id="email" name="email" required>
            <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" required>
            <label for="address">Address</label>
                <input type="text" id="address" name="address" required>
            <label for="post_index">Post index</label>
                <input type="text" id="post_index" name="post_index" required>
            <label for="city">City</label>
                <input type="text" id="city" name="city" required>
            <label for="Land">Land</label>
                <input type="text" id="Land" name="land" required>
            


            <button type="submit">Submit</button>
        </form>
        <a href="calendar.php">Calendar</a>
   </div>
</body>
</html>