<?php
include("includes/config.php");

//session_destroy();

if(isset($_SESSION['userLoggedIn'])) {
    $userLoggedIn = $_SESSION['userLoggedIn'];
}
else {
    header("Location: login.php");
}
?>


<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css"
        integrity="sha256-mmgLkCYLUQbXn0B1SRqzHar6dCnv9oZFPEC1g1cwlkk="
        crossorigin="anonymous"
        />
        <link href="https://fonts.googleapis.com/css2?family=Do+Hyeon&family=Lobster&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="css/style.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js"
            type="text/javascript"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="js/script.js"></script>
        <script src="http://dweet.io/client/dweet.io.min.js"></script>
        <title>Smart Postbox</title>
    </head>
    <body>
        <header>
            <div class="right"><input type="checkbox" onclick="logout()" checked ></div>
            <script>
            function logout() {
                $.post("includes/handlers/ajax/logout.php", function() {
                location.reload();
                });
            }
            </script>
            <div class="container">
                <img src="img/logo.png" class="logo">
                <h1>Smart Postbox</h1>
                <!-- <button class="btn" onclick="sendDweet()">LED OFF</button> -->
                <a href="#" onclick="sendDweet()">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    LIGHT OFF
                </a>
                <br><br>
                <h2 id="postbox">Postbox Empty!</h2>
            </div>
            <script>
                var LED = 0;
                function sendDweet() {
                    if (LED == 0) {
                        LED = 1;
                    } else {
                        LED = 0;
                    }
                    dweetio.dweet_for("PBturnOnLed", {ButtonPressed: LED}, function(err, dweet) {
                        console.log(dweet.thing); //Thing Name: PBturnOnLed
                        console.log(dweet.content); //content of the dweet
                        console.log(dweet.created); //The creation date of dweet
                    });
                }
            </script>
        </header>

        <section class="counters">
            <div class="container">
                <div>
                    <i class="fas fa-thermometer-quarter fa-4x"></i>
                    <div class="counter" id="temperature_value">0</div><p>&deg;C</p>
                    <h3>Temperature</h3>
                </div>
                <div>
                    <i class="fas fa-cloud-sun fa-4x"></i>
                    <div class="counter" id="light_value">0</div><p>lx</p>
                    <h3>Light</h3>
                </div>
                <div>
                    <i class="fas fa-tint fa-4x"></i>
                    <div class="counter" id="humidity_value">0</div><p>%</p>
                    <h3>Humidity</h3>
                </div>
            </div>
        </section>
    </body>
</html>