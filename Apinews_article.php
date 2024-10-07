<?php
session_start();
include('db_connection.php');

$isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';

$unreadQuery = "SELECT COUNT(*) AS unread_count FROM contact WHERE read_status = 0";
$unreadResult = $conn->query($unreadQuery);
$unreadMessages = 0;

if ($unreadResult->num_rows > 0) {
    $row = $unreadResult->fetch_assoc();
    $unreadMessages = $row['unread_count'];
}

// Fetch unread comments count
$unreadCommentsQuery = "SELECT COUNT(*) AS unread_count FROM comments WHERE read_status = 0"; // Update the table name accordingly
$unreadCommentsResult = $conn->query($unreadCommentsQuery);
$unreadComments = 0;

if ($unreadCommentsResult->num_rows > 0) {
    $row = $unreadCommentsResult->fetch_assoc();
    $unreadComments = $row['unread_count'];
}

// Calculate total unread notifications
$totalUnreadNotifications = $unreadMessages + $unreadComments;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>News Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            overflow-x: hidden;
        }

        body {
            display: flex;
            flex-direction: column;
        }



        #mainNavbar {
            background-color: white;
            transition: background-color 0.3s ease;
        }

        #mainNavbar.sticky-top {
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 2px -2px rgba(0, 0, 0, 0.3);
        }

        .navbar-nav {
            margin-left: 600px; /* Center the nav items */
        }

        .navbar {
            justify-content: center; /* Center the entire navbar */
        }

        .custom-card {
            width: 100%;
            z-index: 10;
            position: relative;
            max-height: 310px;
            overflow: visible;
            margin: 0 auto;
        }

        .content-box {
            border: 2px solid #ccc;
            padding: 20px;
            border-radius: 10px;
            background-color: #f9f9f9;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .latest-articles-list h4 {
            text-align: center;
        }

        .btn-outline-success,
    .btn-outline-danger {
        color: #ffffff;
        border-color: #ffffff;
        width: 100px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .btn-danger.btn-block {
        width: 120px;
        height: 35px;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 auto;
    }


        footer.bg-secondary {
            background-color: #2c3236 !important;
            font-size: 14px;
        }

        footer.bg-secondary a {
            color: #ffffff; /* White color for Back to Top link */
            text-decoration: none;
        }

        footer .fa-arrow-up {
            margin-right: 5px; /* Adds space between the icon and Back to Top text */
        }

        footer.bg-secondary .container-fluid {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>

<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" id="mainNavbar">
        <div class="container-fluid justify-content-center">
            <a class="navbar-brand" href="#">
                <img class="d-inline-block align-top" src="NABAA-TV-LOGO1.png" width="400" height="60" />
            </a>
        </div>
    </nav>

    <nav class="navbar navbar-expand-md navbar-dark" style="background-color:#c2272e;">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="politics.php">Politics</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="entertainement.php">Entertainment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="sports.php">Sports</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">API News</a>
                    </li>
                </ul>
            </div>
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <form class="d-flex" role="search">
                    <!-- Admin Notification Button -->
                    <?php if ($isAdmin): ?>
                        <a href="notifications.php" class="btn btn-outline-warning" style="width: 150px;"
                           title="You have <?php echo $totalUnreadNotifications; ?> new notifications">
                            <i class="fa-solid fa-bell"></i> Notifications
                        </a>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="logout.php" class="btn btn-outline-danger ms-2">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-danger ms-2">Sign in</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </nav>
</header>

<div class="container">
    <div class="container my-4">
        <div class="row">
            <div class="col-md-12">
                <div class="article-content-container content-box">
                    <button id="articleCategory"></button>
                    <h1 id="articleTitle"></h1>
                    <p><small id="articleAuthor" class="text-muted"></small></p>
                    <img id="articleImage" class="img-fluid my-3" src="" alt="Article Image">
                    <p id="articleContent"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap and Custom Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            crossorigin="anonymous"></script>

    <!-- Script to Display Full Article Content -->
    <script>
        // Retrieve article details from localStorage
        const article = JSON.parse(decodeURIComponent(localStorage.getItem('selectedArticle')));

        // Display article details on the page
        document.getElementById('articleTitle').innerText = article.title;
        document.getElementById('articleCategory').innerText = article.category || 'API NEWS';
        document.getElementById('articleAuthor').innerText = article.author ? `Author: ${article.author}` : 'NABAATV PUBLISHER';
        document.getElementById('articleImage').src = article.urlToImage || 'news-image-placeholder.jpg';
        document.getElementById('articleImage').onerror = function () {
            this.src = 'news-image-placeholder.jpg';
        };

        // Display the available content without truncating
        document.getElementById('articleContent').innerText = article.content || article.description || 'Content not available.';
    </script>

    <footer class="bg-dark text-white" style="padding: 2rem 0; width: 100%;">
        <div class="container d-flex justify-content-between">
            <div class="col-md-6" style="margin-right: 100px; margin-top: 90px; color:#cd3d49">
                <h3>Your feedback matters! Contact us for assistance.</h3>
            </div>
            <div class="col-md-6">
                <div class="card shadow-lg custom-card">
                    <div class="card-body text-center p-3">
                        <h3 class="card-title text-danger">Contact Us</h3>
                        <!-- Contact Us Form -->
                        <form action="contact.php" method="POST">
                            <!-- Email and Name -->
                            <div class="row mb-3">
                                <div class="col">
                                    <input type="email" class="form-control" placeholder="Your Email" name="email" required>
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" placeholder="Your Name" name="name" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" rows="3" placeholder="Your Message" name="message"
                                          required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-3">
            <a href="#top" class="text-white"><i class="fa fa-arrow-up"></i> Back to Top</a>
        </div>
        <p class="text-center">NABAA TV &copy; 2024. All rights reserved.</p>
    </footer>
</body>
</html>
