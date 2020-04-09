<?php 

if(isset($_POST['loginButton'])) {
    //echo "Login button was pressed";
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $account->login($email, $password);

    if($result == true) {
        $_SESSION['userLoggedIn'] = $email;
        header("Location: index.php");
    }
}

?>