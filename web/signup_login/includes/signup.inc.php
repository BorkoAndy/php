<?php
if(isset($_POST['submit'])){

    //Grabing the data
    $uid = $_POST['uid'];
    $pwd = $_POST['pwd'];
    $pwdrepeat = $_POST['pwdrepeat'];
    $email = $_POST['email'];

    //Instantiate SignupConttr class
    include "..\classes\dbh.classes.php";
    include "..\classes\signup.classes.php";
    require "..\classes\signup-contr.classes.php";
    $signup = new SignupContr($uid, $pwd, $pwdrepeat, $email);
    
    //Running error handlers and user signup
    $signup->signupUser();

    //Going back to front page
    header("location: ../index.php?error=none");
}