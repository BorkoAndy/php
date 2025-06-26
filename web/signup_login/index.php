<?php
session_start();
if(isset($_SESSION["useruid"])){
    echo "Logged as: <br/>";
    print_r($_SESSION["useruid"]);
}?>
    <div id="sign_up">
        <h4>Sign up</h4>
        <form action="includes/signup.inc.php" method="post">
            <input type="text" name="uid" placeholder="Username">
            <input type="password" name="pwd" placeholder="Password">
            <input type="password" name="pwdrepeat" placeholder="Repeat password">
            <input type="text" name="email" placeholder="E-mail">
            <br>
            <button type="submit" name="submit">SIGN UP</button>
        </form>
    </div>

    <div id="login">
        <h4>Login</h4>
        <p>Don't have an account yet? Sign up her!</p>
        <form action="includes/login.inc.php" method="post">
            <input type="text" name="uid" placeholder="Username">
            <input type="password" name="pwd" placeholder="Password">
            <br>
            <button type="submit" name="submit">LOGIN</button>
        </form>

    </div>

    <a href="includes/logout.inc.php">Logout</a>




