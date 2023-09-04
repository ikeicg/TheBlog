<?php
    session_start();

    require_once('db/dbconn.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        
        // Sanitize user input
        $username = filter_var($_POST['username'], FILTER_UNSAFE_RAW);
        $password = filter_var($_POST['pswd'], FILTER_UNSAFE_RAW);

        // Prepare a SQL query to retrieve user data
        $sql = "SELECT * FROM users WHERE username = ? AND password = ? ; ";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();

                // Password matches; login successful
                $_SESSION['username'] = $username;
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $row["id"];
                echo $_SESSION['user_id'];
                header("Location: index.php"); // Redirect to the index page
                exit();

            } else {

                // User not found; set an error message in the session
                $_SESSION['login_error'] = "User not found";
                header("Location: login.php");
                exit();

            }
    
            $stmt->close();

        } else {
            // SQL query preparation failed; set an error message in the session
            $_SESSION['login_error'] = "Database error";
            header("Location: login.php");
            exit();
        }
    
        $conn->close();
    }
   

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TheBlog - Login</title>
    <link rel="stylesheet" href="css/mycss.css">
</head>
<body id="loginbody">
    
    <form action="" method="post" name="logon">
        <div class="logonform">
            <h3>LOGIN</h3>
            <input type="text" name="username" placeholder="USERNAME">
            <input type="password" name="pswd" placeholder="PASSWORD">
            <button name="submit" type="submit">Login</button>
        </div>
    </form>

    <?php 
        if(isset($_SESSION['login_error'])){
            echo '
            <div class="errordiv"> Invalid Login Attempt<div>
            ';

            unset($_SESSION['login_error']);
        }
    ?>

</body>
</html>