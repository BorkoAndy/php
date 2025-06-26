<?php
// print_r($_POST["submit"]);
if(isset($_POST['submit'])){
    // echo "login page";
    
    //Grabing the data
    $uid = $_POST['uid'];
    $pwd = $_POST['pwd'];   

    //Instantiate SignupConttr class
    include "..\classes\dbh.classes.php";
    include "..\classes\login.classes.php";
    require "..\classes\login-contr.classes.php";
    $login = new LoginContr($uid, $pwd);
    
    //Running error handlers and user login
    $login->loginUser();

    //Going back to front page
    header("location: ../index.php?error=none&userloggedin=true");
}