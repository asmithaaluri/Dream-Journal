<?php 
    //ob_start(); // Turns on output buffering
    session_start();

    $timezone = date_default_timezone_set("America/Los_Angeles");

    $connection = mysqli_connect("localhost", "root", "", "dream_journal");

    if(mysqli_connect_errno()) {
        echo "Failed to connect: " . mysqli_connect_errno();
    }
?>