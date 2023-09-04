<?php

session_start();

require_once('db/dbconn.php');
include('includes/pagination.php');

$pagination = max_page('posts', $conn, 3);

if (isset($_GET['page']) && ($_GET['page'] <= $pagination && $_GET['page'] >= 1) ) {
    $currentPage = $_GET['page'];
} else {
    $currentPage = 1;
}

$start = ($currentPage - 1) * $pagination;

// Fetch blog posts from the database
$sql =  "SELECT *
        FROM posts
        INNER JOIN users ON posts.author_id = users.id 
        LIMIT $start, 3";
$result = $conn->query($sql);

if (isset($_GET['logout']) && $_GET['logout'] === 'true') {

    if (isset($_SESSION['user_id'])) {

        $_SESSION = [];

        session_destroy();
    }


    if (isset($conn)) {
        $conn->close();
    }

    // Redirect back to the original page (replace "index.php" with your actual page)
    header('Location: index.php');
    exit();

} 

?>




<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ICG Blog</title>

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Theme CSS -->
    <link href="css/clean-blog.min.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Navigation -->
    <?php
        include("includes/navbar.php");
    ?>

    <!-- Page Header -->
    <!-- Set your background image for this header on the line below. -->
    <header class="intro-header" style="background-image: url('img/home-bg.jpg')">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="site-heading">
                        <h1>ICG Blog</h1>
                        <hr class="small">
                        <span class="subheading">A Blog by ICG MEDIA</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">

                <?php
                    if ($result->num_rows > 0) 
                    {
                        while ($row = $result->fetch_assoc()) 
                        {

                            //  blog posts headlines
                            $date = date('F j, Y', strtotime($row['date_created']));

                            echo "<div class='post-preview'>
                                        <a href='/TheBlog/post.php?post_id={$row['post_id']}'>
                                            <h2 class='post-title'>
                                                {$row['post_title']}
                                            </h2>
                                            <h3 class='post-subtitle'>
                                                {$row['subtitle']}
                                            </h3>
                                        </a>
                                        <p class='post-meta'>Posted by <a href=''>{$row['fullname']};</a> on {$date};
                                        </p>
                                    </div>
                                    <hr>";

                        }
                    } 
                ?>

                <!-- Pager -->
                <ul class="pager">
                    <li class="next">
                        <?php
                            echo '<a href="/TheBlog/index.php?page='. $currentPage-1 .'">Previous  &larr;</a>';
                        ?>
                    </li>

                    <li class="next">
                        <?php
                            echo '<a href="/TheBlog/index.php?page='. $currentPage+1 .'">Next  &rarr;</a>';
                        ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <hr>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <ul class="list-inline text-center">
                        <li>
                            <a href="#">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-circle fa-stack-2x"></i>
                                    <i class="fa fa-github fa-stack-1x fa-inverse"></i>
                                </span>
                            </a>
                        </li>
                    </ul>
                    <p class="copyright text-muted">Copyright &copy; ICG MEDIA 2023</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="js/jqBootstrapValidation.js"></script>
    <script src="js/contact_me.js"></script>

    <!-- Theme JavaScript -->
    <script src="js/clean-blog.min.js"></script>

</body>

</html>
