<?php
include("includes/classes/Account.php");

$account = new Account();

include("includes/handlers/register-handler.php");
include("includes/handlers/login-handler.php");
?>


<!DOCTYPE html>
<html>
    <head>
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="css/login.css">
        <title>Smart Postbox Login</title>
    </head>
    <body>
        <div class="align">
            <img src="img/logo.png" class="logo">
            <div class="title">Smart Postbox</div>
            <div class="sub-title">Stay in touch</div>
            <div class="card">
                <div class="head">
                    <div></div>
                    <a id="login" class="selected" href="#login">Login</a>
                    <a id="register" href="#register">Register</a>
                    <div></div>
                </div>
                <div class="tabs">
                    <form action="login.php" method="POST">
                        <div class="inputs">
                            <div class="input">
                                <input type="email" name="email" placeholder="Email" required>
                                <img src="img/mail.svg">
                            </div>
                            <div class="input">
                                <input type="password" name="password" placeholder="Password">
                                <img src="img/pass.svg">
                            </div>
                            <label class="checkbox">
                                <input type="checkbox">
                                <span>Remember me</span>
                            </label>
                        </div>
                        <button type="submit" name="loginButton">Login</button>
                    </form>
                    <form action="login.php" method="POST">
                        <div class="inputs">
                            <div class="input">
                                <input type="email" name="email" placeholder="Email" required>
                                <img src="img/mail.svg">
                            </div>
                            <div class="input">
                                <input type="password" name="password" placeholder="Password">
                                <img src="img/pass.svg">
                            </div>
                            <div class="input">
                                <input type="password" name="password2" placeholder="Confirm Password">
                                <img src="img/pass.svg">
                            </div>
                        <button type="submit" name="registerButton">Register</button>
                    </form>
                </div>
            </div>
        </div>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <script src="js/login.js"></script>
    </body>
</html>