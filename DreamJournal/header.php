<?php 
    require 'dbconnection.php';

    // Check if user is logged in
    if (isset($_SESSION['login_username'])) {
        $username = $_SESSION['login_username']; 
        $user_info_query = mysqli_query($connection, "SELECT * FROM users WHERE username='$username'");
        $user = mysqli_fetch_array($user_info_query);
    } else {
        header("Location: login.php");
        exit();
    }
?>