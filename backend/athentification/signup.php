<?php

require_once '../athentification/athen.php';
$registre = new registre();
$message = '';

if(isset($_POST['submit'])){
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $role = htmlspecialchars(trim($_POST['role']));

    if(empty($name) || empty($email) || empty($password) || empty($role)){
        $message = 'All fields are required.';
        header('location: ../../public/index.php');

    }elseif(preg_match('/[0-9]/', $name)){
        $message = 'the name cannot cantain numbers .';
        header('location: ../../public/index.php');

    }
    
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $message = 'invalid email addrese .';
        header('location: ../../public/index.php');

    }
    else{
        $result = $registre-> registration($name, $email, $password, $role);
        if($result == 1){
            $message = 'Registration successful';
            header('location: ../../public/index.php');
        }
        elseif($result == 500){
            $message = 'name or email has already been taken';
            header('location: ../../public/index.php');
        }
        }
    }
?>