<?php

function sanitizeFormString($inputText) {
    $inputText = strip_tags($inputText);
    $inputText = Str_replace(" ", "", $inputText);
    $inputText = ucfirst(strtolower($inputText));
    return $inputText;
}

function sanitizeFormPassword($inputText) {
    $inputText = strip_tags($inputText);
    return $inputText;
}

if(isset($_POST['registerButton'])) {
    //echo "Register button was pressed";
    $email = sanitizeFormString($_POST['email']);
    $password = sanitizeFormPassword($_POST['password']);
    $password2 = sanitizeFormPassword($_POST['password2']);

    $account->register( $email, $password, $password2);
}



?>