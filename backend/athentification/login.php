<?php

require_once '../athentification/athen.php';
session_start();

$login = new login();
$error = '';

if(isset($_POST['submit'])){

    $name = htmlspecialchars(trim($_POST['name']));
    $password = htmlspecialchars(trim($_POST['password']));

    if(empty($name) || empty($password)) {
        $error = 'All fields are required.';
        header('location: ../../public/index.php');
    }else{
        try{

            $result = $login->login($name, $password);
            if ($result === 10) {
                header('location: ../../public/dashbord-admin.php');               
            } elseif ($result === 100) {
                $error = 'User not found.';
                header('Location: ../../public/index.php?error=' . urlencode($error));
                exit;
            }
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $error = "A server error occurred. Please try again later.";
        }
    }
}
?>