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
    <title>Index Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>
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



        .container-fluid {
            padding-bottom: 0;
        }

        .carousel-item {
            height: 32rem;
            background: #777;
            color: white;
            position: relative;
            background-position: center;
            background-size: cover;
        }

        .content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding-bottom: 50px;
        }

        #mainNavbar {
            background-color: white;
            transition: background-color 0.3s ease;
        }

        #mainNavbar.sticky-top {
            background-color: rgba(255, 255, 255, 0.9);
        }

        #mainNavbar {
            box-shadow: none;
        }

        #mainNavbar.sticky-top {
            box-shadow: 0 4px 2px -2px rgba(0, 0, 0, 0.3);

        }
        .navbar-nav {
    margin-left: 600px; /* This will push the nav items to the right */
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

        .navbar-nav .nav-link,
        .navbar-nav .nav-link:focus,
        .navbar-nav .nav-link:hover,
        .search-input,
        .btn-outline-success {
            color: #ffffff;
        }


        .search-input {
            height: 35px;
            width: 200px;
        }

        .btn {
            height: 35px;
            padding: 0 15px;
            line-height: 35px;
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
    background-color: #02090e; /* Slightly darker color for second footer */
    font-size: 14px;
}

footer.bg-secondary a {
    color: #ffffff; /* White color for Back to Top link */
    text-decoration: none;
}

footer .fa-arrow-up {
    margin-right: 5px; /* Adds space between the icon and Back to Top text */
}


footer.bg-secondary .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}


a[title]:hover::after {
    content: attr(title);
    background-color: #333;
    color: #fff;
    padding: 5px;
    border-radius: 5px;
    position: absolute;
    top: 100%; /* Now positioned below the button */
    right: 0%; /* Centered horizontally */
    transform: translateX(-50%); /* Center the tooltip */
    z-index: 1000;
    white-space: nowrap;
}
    </style>

    <div id="top"></div>

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
                                <a href="notifications.php" class="btn btn-outline-warning" style="width: 150px;" title="You have <?php echo $totalUnreadNotifications; ?> new notifications">
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
            </div>
        </nav>
    </header>
    <div class="container">

        <!-- News Section -->
        <div class="container my-4">
             <!-- Greeting Message -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <h4 class="text-center">Hello, <?php echo $_SESSION['user_name']; ?>! Welcome back.</h4>
    <?php endif; ?>
            <h1 class="text-center">Latest News</h1>
            <div id="newsContainer" class="row"></div>
        </div>


        <!-- Bootstrap and Custom Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            crossorigin="anonymous"></script>

        <!-- Script to Fetch and Display News -->
        <script>
            const apiKey = '1f389b12ee7c49c4b327d0cac892f9f5'; // Replace with your NewsAPI key
            const newsContainer = document.getElementById('newsContainer');

            // Fetch news from the API
            fetch(`https://newsapi.org/v2/top-headlines?country=us&apiKey=${apiKey}`)
                .then(response => response.json())
                .then(data => {
                    const validArticles = data.articles.filter(article => article.content && !article.content.includes('removed') && article.content.length > 100);

                    // Check if there are valid articles
                    if (validArticles.length === 0) {
                        newsContainer.innerHTML = '<p>No valid news articles available at the moment.</p>';
                        return;
                    }

                    // Loop through the valid articles and display them
                    validArticles.forEach((article, index) => {
                        const newsCard = `
                    <div class="card my-3 col-md-4">
                        <img src="${article.urlToImage || 'news-image-placeholder.jpg'}" class="card-img-top" alt="News Image" onerror="this.src='news-image-placeholder.jpg'">
                        <div class="card-body">
                            <h5 class="card-title">${article.title}</h5>
                            <p class="card-text">${article.description || 'No description available.'}</p>
                            <button class="btn btn-primary" onclick="viewFullArticle(${index})">Read More</button>
                        </div>
                    </div>
                `;
                        newsContainer.innerHTML += newsCard;
                    });

                    // Store the valid articles in localStorage for later use
                    localStorage.setItem('validArticles', encodeURIComponent(JSON.stringify(validArticles)));
                })
                .catch(error => console.error('Error fetching news:', error));

            // Function to view the full article
            function viewFullArticle(index) {
                const validArticles = JSON.parse(decodeURIComponent(localStorage.getItem('validArticles')));
                const selectedArticle = validArticles[index];
                localStorage.setItem('selectedArticle', encodeURIComponent(JSON.stringify(selectedArticle)));
                window.location.href = 'Apinews_article.php';
            }
        </script>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <script>
        // Function to fetch articles
        function fetchArticles() {
            fetch('admin.php?show=all')
                .then(response => response.json())
                .then(data => {
                    const articlesDiv = document.getElementById('articles');
                    articlesDiv.innerHTML = '';
                    data.forEach(article => {
                        articlesDiv.innerHTML += `
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">${article.title}</h5>
                                        <img src="${article.image}" alt="${article.title}" class="img-fluid" style="max-height: 200px;">
                                        <p>${article.content}</p>
                                        <p><strong>Category:</strong> ${article.category}</p>
                                    </div>
                                </div>
                            `;
                    });
                });
        }

        // Fetch articles on page load
        fetchArticles();
    </script>

<footer class="bg-dark text-white" style="padding: 2rem 0; width: 100%;">
    <div class="container d-flex justify-content-between">
        <div class="col-md-6" style="margin-right: 100px; margin-top: 90px; color:#cd3d49"> <!-- Added margin to create space -->
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
                                <input type="email" class="form-control border-danger" id="email" name="email" placeholder="Enter your email" required>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control border-danger" id="name" name="name" placeholder="Enter your name">
                            </div>
                        </div>
                        <!-- Message -->
                        <div class="form-group mb-3">
                            <textarea name="message" class="form-control border-danger" id="message" placeholder="Your message"></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger btn-block mt-4">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</footer>


<footer class="bg-secondary text-white" style="padding: 1rem 0; width: 100%; background-color: #2c3236 !important;">
    <div class="container d-flex align-items-center justify-content-between"> <!-- Add justify-content-between -->
        <div style="flex: 0 0 auto; margin-right:100px;"> <!-- Keep the Back to Top button at the far left -->
            <a href="#top" class="text-white" style="text-decoration: none;">
                <i class="fa fa-arrow-up"></i> Back to Top
            </a>
        </div>

        <div class="flex-grow-1 text-center"> <!-- Centering the social links -->
            <div class="d-flex justify-content-center align-items-center">
                <span class="text-white" style="text-decoration: none; font-size: 15px; margin-right: 10px;">Follow us on our socials</span>
                <a href="https://www.facebook.com/Nabaatvcom" class="btn-floating text-white me-3" style="font-size: 20px;">
                    <i class="fab fa-facebook"></i>
                </a>
                <a href="#" class="btn-floating text-white me-3" style="font-size: 20px;">
                    <i class="fab fa-x"></i>
                </a>
                <a href="#" class="btn-floating text-white me-3" style="font-size: 20px;">
                    <i class="fab fa-youtube"></i>
                </a>
                <a href="#" class="btn-floating text-white me-3" style="font-size: 20px;">
                    <i class="fab fa-whatsapp"></i>
                </a>
                <a href="#" class="btn-floating text-white" style="font-size: 20px;">
                    <i class="fab fa-threads"></i>
                </a>
            </div>
        </div>

        <div style="flex: 0 0 auto; font-size: 15px; margin-left: 20px;"> <!-- Text on the far right -->
            © 2024, All Rights Reserved | Developed & Designed by GO CREATIVE
        </div>
    </div>
</footer>

</body>

</html>