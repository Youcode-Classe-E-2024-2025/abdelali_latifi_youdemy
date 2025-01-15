<?php

require_once '../athentification/athen.php';

$login = new login();
$error = '';

if(isset($_POST['submit'])){

    $name = htmlspecialchars(trim($_POST['name']));
    $password = htmlspecialchars(trim($_POST['password']));

    if(empty($name) || empty($password)) {
        $error = 'All fields are required.';
    }else{
        try{

            $result = $login->login($name, $password);

            if($result === 10){
                $error = 'incorrect password';
            }elseif($result == 100){
                $error = 'user not found';
            }
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $error = "A server error occurred. Please try again later.";
        }
    }
}
?>