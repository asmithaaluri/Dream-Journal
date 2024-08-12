
<?php
    require 'dbconnection.php';
    include('header.php');

    // Get current date from HTTP request
    if (isset($_GET['date']))
    {
        $current_date = new DateTime($_GET['date']);
    }
    else 
    {
        $current_date = new DateTime();
    }

    function check_date_bounds($date)
    {
        $current_date = new DateTime();
        $last_day_of_month = $current_date->format('t');
        $day_num = (int) $date->format('d');
        return $day_num >= 1 && $day_num <= $last_day_of_month;
    }

    function get_five_dates($current_date)
    {
        $dates = array();
        for ($i = -2; $i <= 2; $i++) 
        {
            $new_date = clone $current_date;
            $new_date->modify("$i day");
            array_push($dates, $new_date->format('Y-m-d'));
        }
        return $dates;
    }

    if (isset($_GET['move'])) 
    {
        if ($_GET['move'] === 'backward') 
        {
            $current_date->modify('-5 days');
        } elseif ($_GET['move'] === 'forward') 
        {
            $current_date->modify('+5 days');
        } else if ($_GET['move'] === 'delete_post')
        {
            $post_id = $_GET['post_id'];
            $delete_entry_query = mysqli_query($connection, "DELETE FROM entries WHERE id='$post_id'");

            header("Location: index.php");

        } else if ($_GET['move'] === 'edit_post')
        {   
            // working on editing dream
            $post_id = $_GET['post_id'];
            $edit_entry_post_query = mysqli_query($connection, "SELECT post FROM entries WHERE id='$post_id'");
        
            $row = mysqli_fetch_assoc($edit_entry_post_query);
            $edit_entry_post = $row['post'];

            $_SESSION['update_post_id'] = $post_id;
            $_SESSION['edit_entry_post'] = $edit_entry_post;

            header("Location: edit_post.php");
            echo "have to edit current post";
        }
    }

    // Get all dates from current_date
    $all_dates = get_five_dates($current_date);

    $leftmost_date = new DateTime($all_dates[0]);
    $current_month_name = $leftmost_date->format('F');
    $current_year_num = $leftmost_date->format('Y');

    // Load posts to display for the current date
    $username = $_SESSION['login_username'];
    $formatted_date = $current_date->format('Y-m-d');
    $get_posts_query = mysqli_query($connection, "SELECT * FROM entries WHERE username='$username' AND date='$formatted_date'");

    $all_posts = array();
    if (!empty($get_posts_query))
    {
        while ($row = mysqli_fetch_assoc($get_posts_query))
        {
            array_push($all_posts, $row);
        } 
    }
?>


<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dream Journal</title>
        <link rel="stylesheet" text="text/css" href="assets/css/journal_style.css">
    </head>
    <body>
        <div class="greeting_rectangle">
            <a href="logout.php"><img id="logout_icon" src="assets/images/logout.png" alt="Logout"></a>
            <h2 id="greeting_heading"><?php echo $user['first_name'] . " " . $user['last_name'] . "'s Dream Journal"; ?></h2>
        </div>  
        <div class="month_year">  
            <h2 id="month_year"><?php echo $current_month_name . "  " . $current_year_num; ?></h2>  
        </div>

        <div class="container">
            <div class="date_row">  
                <a href="?move=backward&date=<?php echo $current_date->format('Y-m-d'); ?>"><img id="icon" src="assets/images/back.png" alt="Backward"></a>
                <?php
                foreach ($all_dates as $date_obj) 
                {
                    $date = new DateTime($date_obj);
                
                    if (check_date_bounds($date)) 
                    {
                        ?> <a class="background_date_circle" href="?move=current_date&date=<?php echo $date->format('Y-m-d'); ?>"><?php echo '<span>' . $date->format('d') . '</span>'; ?> </a> <?php
                    }
                }
                ?>
                <a href="?move=forward&date=<?php echo $current_date->format('Y-m-d'); ?>"><img id="icon" src="assets/images/forward.png" alt="Forward"></a>
            </div>
            <div class="line"></div>
            <div class="form_and_posts">
                <form action="new_post.php" method="POST" class="new_post_form">
                    <input type="submit" name="new_post_button" value="+ log dream" required>
                    <h4><?php echo "Dream logs for " . $current_date->format('l, F j'); ?></h4>
                    <?php $_SESSION['dream_log_date'] = $current_date->format('l, F j'); ?>
                </form>
                <div class="dream_posts">
                    <?php
                        if (empty($all_posts))
                        {
                            echo '<div class="post_box">';
                            echo "No dreams entries.";
                            echo '</div>';
                        } else
                        {
                            foreach ($all_posts as $post) 
                            {
                                $timestamp = new DateTime($post['time']);
                                $formatted_time = $timestamp->format('H:i');

                                echo '<div class="post_box">';
                                echo '<div class="post_content">';
                                echo $post['post'] . "<br>";
                                echo '</div>';
                                echo '<div class="timestamp">';
                                echo $formatted_time;
                                echo '</div>';
                                echo '<div class="moves">';
                                ?> 
                                    <a href="?move=delete_post&date=<?php echo $current_date->format('Y-m-d'); ?>&post_id=<?php echo $post['id']; ?>"><img id="icon" src="assets/images/delete.png"></a>
                                    <a href="?move=edit_post&date=<?php echo $current_date->format('Y-m-d'); ?>&post_id=<?php echo $post['id']; ?>"><img id="icon" src="assets/images/edit.png"></a>
                                <?php
                                echo '</div';
                                echo '</div>';
                                echo '<br>';
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>

