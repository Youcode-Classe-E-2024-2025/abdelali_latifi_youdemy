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
        echo $result ;

    }elseif(preg_match('/[0-9]/', $name)){
        $message = 'the name cannot cantain numbers .';
        echo $result ;

    }
    
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $message = 'invalid email addrese .';
        echo $result ;

    }
    else{
        $result = $registre-> registration($name, $email, $password, $role);
        echo $result ;
        if($result == 1){
            $message = 'Registration successful';
        }
        elseif($result == 500){
            $message = 'name or email has already been taken';
            echo $message ;
        }
        }
    }
?>