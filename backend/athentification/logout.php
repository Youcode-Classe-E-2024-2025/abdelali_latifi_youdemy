<?php
$_SESSION = [];
session_unset();
session_destroy();
header('location: ../../public/index.php');
?>