<?php
require_once '../athentification/athen.php';
session_start(); 

$registre = new registre();
$message = '';

if (isset($_POST['submit'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $role = htmlspecialchars(trim($_POST['role']));

    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        $_SESSION['error'] = 'All fields are required.';
        header('location: ../../public/index.php?signup=error');
        exit;

    } elseif (preg_match('/[0-9]/', $name)) {
        $_SESSION['error'] = 'The name cannot contain numbers.';
        header('location: ../../public/index.php?signup=error');
        exit;

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'Invalid email address.';
        header('location: ../../public/index.php?signup=error');
        exit;

    } else {
        $result = $registre->registration($name, $email, $password, $role);
        if ($result == 1) {
            $_SESSION['success'] = 'Registration successful!';
            header('location: ../../public/index.php?signup=success');
            exit;

        } elseif ($result == 500) {
            $_SESSION['error'] = 'Name or email has already been taken.';
            header('location: ../../public/index.php?signup=error');
            exit;
        }
    }
}
?>
