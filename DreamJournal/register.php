<?php
    require 'dbconnection.php';

    // Declare variables
    $register_first_name = "";
    $register_last_name = "";
    $register_username = "";
    $register_password_one = "";
    $register_password_two = "";
    $error_messages_array = array();
    $successful_messages_array = array();

    // Handle register form
    if (isset($_POST['register_submit_button'])) {
        // Clean up user inputs
        $register_first_name = strip_tags($_POST['register_first_name']); // Remove HTML tags
        $register_first_name = str_replace(' ', '', $register_first_name); // Remove spaces
        $register_first_name = ucfirst(strtolower($register_first_name)); // Uppercase first letter
        $_SESSION['register_first_name'] = $register_first_name; // Store into session variable

        $register_last_name = strip_tags($_POST['register_last_name']);
        $register_last_name = str_replace(' ', '', $register_last_name);
        $register_last_name = ucfirst(strtolower($register_last_name));
        $_SESSION['register_last_name'] = $register_last_name;

        $register_username = strip_tags($_POST['register_username']);
        $_SESSION['register_username'] = $register_username;

        $register_password_one = strip_tags($_POST['register_password_one']);
        $register_password_two = strip_tags($_POST['register_password_two']);

    // Validate form inputs
        if (strlen($register_first_name) > 30 || strlen($register_first_name) < 2) 
        {
            array_push($error_messages_array, "First name must be between 2 and 30 characters (inclusive).<br>");
            $_SESSION['register_first_name'] = "";
        }

        if (strlen($register_last_name) > 30 || strlen($register_last_name) < 2) 
        {
            array_push($error_messages_array, "Last name must be between 2 and 30 characters (inclusive).<br>");
            $_SESSION['register_last_name'] = "";
        }

        $check_username_query = mysqli_query($connection, "SELECT username FROM users WHERE username='$register_username'");
        if (mysqli_num_rows($check_username_query) != 0)
        {
            array_push($error_messages_array, "Username is taken.<br>");
            $_SESSION['register_username'] = "";
        }

        if ($register_password_one != $register_password_two) 
        {
            array_push($error_messages_array, "Your passwords do not match.<br>");
        } 
        else if (strlen($register_password_one) > 30 || strlen($register_password_one) < 5) 
        {
            array_push($error_messages_array, "Your password must be between 5 and 30 characters (inclusive).<br>");
        } 
        else if (preg_match('/[^A-Za-z0-9]/', $register_password_one)) 
        {
            array_push($error_messages_array, "Your password can only contain uppercase letters, lowercase letters, and numbers.<br>");
        }

        if (empty($error_messages_array)) 
        {
            $password = md5($register_password_one); // Encrypts password
            $profile_pic = "assets/images/profile_pic.png";

            $register_user_query = mysqli_query($connection, "INSERT INTO users VALUES (NULL, '$register_first_name', '$register_last_name', '$register_username', '$password', 'TRUE')");

            array_push($successful_messages_array, "You're all set! Go ahead and login!<br>");

            // Clear session variables
            $_SESSION['register_first_name'] = "";
            $_SESSION['register_last_name'] = "";
            $_SESSION['register_username'] = "";
        }
    }
?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dream Journal</title>
    <link rel="stylesheet" text="text/css" href="assets/css/register_style.css">
    <link rel="stylesheet" text="text/css" href="assets/css/login_style.css">
</head>
<body> 
    <div class="register_container">
        <h1>Create An Account</h1>
        <div class="cloud_one">
                <img id="cloud_one" src="assets/images/cloud_one.png"></img>
            </div>
            <div class="cloud_two">
                <img id="cloud_two" src="assets/images/cloud_two.png"></img>
            </div>
        <form action="register.php" method="POST" class=
        "register_form">
            <input type="text" name="register_first_name" placeholder="First Name" autocomplete="off" value="<?php 
                if (isset($_SESSION['register_first_name'])) {
                    echo ($_SESSION['register_first_name']);
                }
            ?>" required>
            <br>
            <div class="error_message">
                <?php 
                    if (in_array("First name must be between 2 and 30 characters (inclusive).<br>", $error_messages_array)) 
                    {
                        echo "First name must be between 2 and 30 characters (inclusive).<br>";
                    }
                ?>
            </div>

            <input type="text" name="register_last_name" placeholder="Last Name" autocomplete="off" value="<?php 
                if (isset($_SESSION['register_last_name'])) {
                    echo ($_SESSION['register_last_name']);
                }
            ?>" required>
            <br>
            <div class="error_message">
                <?php 
                    if (in_array("Last name must be between 2 and 30 characters (inclusive).<br>", $error_messages_array)) 
                    {
                        echo "Last name must be between 2 and 30 characters (inclusive).<br>";
                    }
                ?>
            </div>

            <input type="text" name="register_username" placeholder="Username" autocomplete="off" value="<?php 
                if (isset($_SESSION['register_username'])) {
                    echo ($_SESSION['register_username']);
                }
            ?>" required>
            <br>
            <div class="error_message">
                <?php 
                    if (in_array("Username is taken.<br>", $error_messages_array)) 
                    {
                        echo "Username is taken.<br>";
                    }
                ?>
            </div>

            <input type="password" name="register_password_one" placeholder="Password" required>
            <br>

            <input type="password" name="register_password_two" placeholder="Confirm Password" required>
            <br>
            <div class="error_message">
                <?php 
                    if (in_array("Your passwords do not match.<br>", $error_messages_array)) 
                    {
                        echo "Your passwords do not match.<br>";
                    }
                    else if (in_array("Your password must be between 5 and 30 characters (inclusive).<br>", $error_messages_array)) 
                    {
                        echo "Your password must be between 5 and 30 characters (inclusive).<br>";
                    }
                    else if (in_array("Your password can only contain uppercase letters, lowercase letters, and numbers.<br>", $error_messages_array)) 
                    {
                        echo "Your password can only contain uppercase letters, lowercase letters, and numbers.<br>";
                    }
                ?>
            </div>

            <div class="submit_button_wrapper">
                <input type="submit" name="register_submit_button" value="Register">
                <br>
            </div>
            <div class="error_message success_message">
                <?php 
                    if (in_array("You're all set! Go ahead and login!<br>", $successful_messages_array)) 
                    {
                        echo "You're all set! Click <a id=\"success_link\" href=\"login.php\">here</a> to login!<br>";
                    }
                ?>
                <br>
            </div>
            <br>

            <div class="link_wrapper"> 
                <a id="login_link" href="login.php">Already have an account? Login here!</a>
            </div>
            
        </form>
    </div>
</body>
</html>
