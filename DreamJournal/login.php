<?php 
    require 'dbconnection.php';
    session_start();

    $username = "";
    $password = "";

    $error_message_array = array();

    if (isset($_POST['login_button']))
    {
        $username = $_POST['login_username'];
        $password = md5($_POST['login_password']);

        $check_login_info_query = mysqli_query($connection, "SELECT * FROM users WHERE username='$username' AND password='$password'");
        $num_rows = mysqli_num_rows($check_login_info_query);

        if ($num_rows == 1)
        {
            $user_logged_out_query = mysqli_query($connection, "SELECT * FROM users WHERE username='$username' AND logout='TRUE'");
            if (mysqli_num_rows($user_logged_out_query))
            {
                $change_to_logged_in = mysqli_query($connection, "UPDATE users SET logout='FALSE' WHERE username='$username'");
            }

            $_SESSION['login_username'] = $username;
            header("Location: index.php");
            exit();
        } 
        else if ($num_rows == 0)
        {
            $check_username_query = mysqli_query($connection, "SELECT username FROM users WHERE username='$username'");
            $num_rows = mysqli_num_rows($check_username_query);

            if ($num_rows == 0)
            {
                $_SESSION['login_username'] = "";
                array_push($error_message_array, "Username does not exist.<br>");
            } 
            else 
            {
                $_SESSION['login_username'] = $username;

                $check_password_query = mysqli_query($connection, "SELECT username FROM users WHERE password='$password'");
                $num_rows = mysqli_num_rows($check_password_query);
                if ($num_rows == 0)
                {
                    array_push($error_message_array, "Incorrect password.<br>");
                }
            }
        }
    }

?>

<html>
    <head>
        <title>Dream Journal</title>
        <link rel="stylesheet" text="text/css" href="assets/css/login_style.css">
        <link rel="stylesheet" text="text/css" href="assets/css/clouds_moving_style.css">
    </head>
    <body>
        <div class="login_container">
            <h1>Dreamy</h1>
            <div class="cloud_one">
                <img id="cloud_one" src="assets/images/cloud_one.png"></img>
            </div>
            <div class="cloud_two">
                <img id="cloud_two" src="assets/images/cloud_two.png"></img>
            </div>
            <form action="login.php" method="POST" class="login_form">
                <input id="login_username_box" type="text" name="login_username" placeholder="Username" autocomplete="off" value="<?php 
                if (isset($_SESSION['login_username']))
                {
                    echo $_SESSION['login_username'];
                }
                ?>" required>
                <br>
                <div class="error_message">
                    <?php 
                        $message = "Username does not exist.<br>";
                        if (in_array($message, $error_message_array))
                        {
                            echo $message;
                        }
                    ?>
                </div>

                <input id="login_password_box" type="password" name="login_password" placeholder="Password" autocomplete="off" required>
                <br>
                <div class="error_message">
                        <?php 
                            $message = "Incorrect password.<br>";
                            if (in_array($message, $error_message_array))
                            {
                                echo $message;
                            }
                        ?>
                </div>
                
                <div class="links_wrapper">
                    <a id="create_account_link" href="register.php">Create An Account</a>
                    <br>

                    <a id="forgot_password_link" href="#">Forgot Password?</a>    
                    <br>
                </div>

                <div class="submit_button_wrapper">
                    <input type="submit" name="login_button" value="Log In" required>
                    <br>
                </div>
            </form>
        </div>
    </body>
</html>