<?php
    require 'dbconnection.php';
    include('header.php');

    $user_dream_entry = "";

    $error_messages_array = array();

    if (isset($_POST['submit_dream_entry']))
    {
        $user_dream_entry = trim($_POST['user_dream_entry']);
        $username = $_SESSION['login_username'];
        $formatted_date = $_SESSION['dream_log_date'];
        $date_obj = DateTime::createFromFormat('l, F j', $formatted_date);
        $new_date = $date_obj->format('Y-m-d');

        if (empty($user_dream_entry))
        {
            array_push($error_messages_array, "Please enter a dream.<br>");
        } else
        {
            $user_dream_entry_query = mysqli_query($connection, "INSERT INTO entries VALUES (NULL, '$username', '$user_dream_entry', '$new_date', NOW())");

            header("Location: index.php");
        }
    }
?>

<html>
    <head>
        <title>Dream Journal</title>
        <link rel="stylesheet" text="text/css" href="assets/css/create_post_style.css">
    </head>
    <body>
        <form action="new_post.php" method="POST" class="log_dream_form">
            <a href="index.php"><img id="icon" src="assets/images/back.png" alt="Backward"></a>
            <h1><?php echo "Log for " . $_SESSION['dream_log_date']; ?></h1>
            <br>
            <textarea name="user_dream_entry" rows="4" cols="50" placeholder="Type your dream here..."></textarea>
            <br>
            <input type="submit" name="submit_dream_entry" value="Log Dream" required>
            <br>
            <div class="error_messages">
                <?php 
                    if (in_array("Please enter a dream.<br>", $error_messages_array))
                    {
                        echo "Please enter a dream.<br>";
                    }
                ?>
            </div>
        </form>
    </body>
</html>