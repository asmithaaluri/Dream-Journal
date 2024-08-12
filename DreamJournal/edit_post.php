<?php
    require 'dbconnection.php';
    include('header.php');

    $user_dream_entry = "";

    $error_messages_array = array();

    if (isset($_POST['update_dream_entry']))
    {
        $user_dream_entry = trim($_POST['user_dream_entry']);
        $post_id = $_SESSION['update_post_id'];

        if (empty($user_dream_entry))
        {
            array_push($error_messages_array, "Please enter a dream.<br>");
        } else
        {
            $update_dream_entry_query = mysqli_query($connection, "UPDATE entries SET post='$user_dream_entry' WHERE id='$post_id'");

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
        <form action="edit_post.php" method="POST" class="log_dream_form">
            <a href="index.php"><img id="icon" src="assets/images/back.png" alt="Backward"></a>
            <h2><?php echo "Update log for " . $_SESSION['dream_log_date']; ?></h2>
            <br>
            <textarea name="user_dream_entry" rows="4" cols="50" placeholder="Type your dream here..."><?php echo $_SESSION['edit_entry_post']; ?></textarea>
            <br>
            <input type="submit" name="update_dream_entry" value="Update Dream" required>
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