<?php
    ?>
    <div id="sign_up">
        <h4>Sign up</h4>
        <form action="signup.inc.php" method="post">
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
        <form action="login.inc.php" method="post">
            <input type="text" name="uid" placeholder="Username">
            <input type="password" name="pwd" placeholder="Password">
            <br>
            <button type="submit">LOGIN</button>
        </form>

    </div>


