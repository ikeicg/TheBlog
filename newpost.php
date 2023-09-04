<?php
    session_start();

    require_once('db/dbconn.php');

    if(!isset($_SESSION['logged_in'])){
        header("Location: index.php"); // Redirect to the index page
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

        // Sanitize user input
        $post_title = filter_var($_POST['post_title'], FILTER_UNSAFE_RAW);
        $post_subtitle = filter_var($_POST['post_subtitle'], FILTER_UNSAFE_RAW);
        $post_content = filter_var($_POST['post_body'], FILTER_UNSAFE_RAW);
        $p_id = '';
        $date_id = NULL;
        $author_id = $_SESSION['user_id'];

        // Prepare a SQL query to retrieve user data
        $sql = "INSERT INTO posts (post_id, post_title, subtitle, author_id, date_created, post_content)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ississ", $p_id, $post_title, $post_subtitle, $author_id, $date_id, $post_content);
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
                header("Location: index.php"); // Redirect to the index page
                exit();
            } else {
                echo "Error creating post: " . $stmt->error;
            }
        
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
        
    }
   
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TheBlog - New Post</title>
    <link rel="stylesheet" href="css/mycss.css">
</head>
<body id="postbody">
    
    <form action="" method="post" name="makepost">
        <div class="postform">
            <h3>NEW POST</h3>
            <input type="text" name="post_title" placeholder="POST TITLE">
            <input type="text" name="post_subtitle" placeholder="POST SUBTITLE">
            <textarea name="post_body" id="" cols="30" rows="10" placeholder="Enter your content here..."></textarea>
            <button name="submit" type="submit">Create Post</button>
        </div>
    </form>

</body>
</html>