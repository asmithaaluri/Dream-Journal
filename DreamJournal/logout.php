<?php
require 'dbconnection.php';
session_start();

$username = $_SESSION['login_username'];
$update_logout_query = mysqli_query($connection, "UPDATE users SET logout='TRUE' WHERE username='$username'");

session_destroy();
header("Location: login.php");

?>
