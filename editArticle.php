<?php
session_start();include('db_connection.php');

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
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
</head>

<body>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background-color: #212429;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        #mainNavbar {
            background-color: white;
        }
        .navbar-nav {
    margin-left: 600px; /* This will push the nav items to the right */
}

.navbar {
    justify-content: center; /* Center the entire navbar */
}


        header+* {
            background-color: #212429;
        }

        .container {
            color: white;
            padding: 50px;
        }

        .form-control,
        #editor {
            background-color: #343a40;
            color: #ffffff;
            border: 1px solid #495057;
            padding: 10px;
            font-size: 16px;
        }

        .btn-post-article {
            display: inline-block;
            margin: 20px auto;
            background-color: #c2272e;
            color: white;
            width: 150px;
        }

        .btn-reset-article {
            display: inline-block;
            margin: 20px 10px;
            background-color: #c2272e;
            color: white;
            width: 150px;
        }

        #title {
            font-size: 24px;
            font-weight: bold;
            color: black;
        }

        #content {
            font-family: sans-serif;
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

    <?php
    // Include the database connection file
    include('db_connection.php');
    $isAdmin = isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';

    // Get the article ID from the URL
    if (isset($_GET['id'])) {
        $article_id = $_GET['id'];
        $sql = "SELECT * FROM articles WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $article_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $article = $result->fetch_assoc();
    }
    ?>

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
        <h2>Edit Article</h2>
        <form action="updateArticle.php" method="POST" enctype="multipart/form-data">
            <!-- Include article ID to identify which article to update -->
            <input type="hidden" name="id" value="<?php echo $article['id']; ?>">

            <div class="form-group">
                <label for="title">Article Title:</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo $article['title']; ?>" required>
            </div>

            <div class="form-group">
                <label for="content">Article Content:</label>
                <div id="editor">
                    <?php echo $article['content']; ?>
                </div>
                <input type="hidden" name="content" id="content" required>
            </div>

            <div class="form-group">
                <label for="category">Category:</label>
                <select class="form-control" name="category" required>
                    <option value="sports" <?php echo ($article['category'] == 'sports') ? 'selected' : ''; ?>>Sports</option>
                    <option value="politics" <?php echo ($article['category'] == 'politics') ? 'selected' : ''; ?>>Politics</option>
                    <option value="entertainment" <?php echo ($article['category'] == 'entertainment') ? 'selected' : ''; ?>>Entertainment</option>
                </select>
            </div>

            <div class="form-group">
    <label for="image">Article Image:</label>
    <input type="file" class="form-control" name="image" accept="image/*">

    <!-- Check if an image exists for the article -->
    <?php if (!empty($article['image'])): ?>
        <img src="<?php echo 'path/to/images/' . $article['image']; ?>" alt="Article Image" width="150" class="mt-2">
    <?php else: ?>
        <!-- Optional: Display a placeholder or text if no image is found -->
        <p>No image available for this article.</p>
    <?php endif; ?>
</div>

            <button type="submit" class="btn btn-post-article">Update Article</button>
            <button type="reset" class="btn btn-reset-article">Reset</button>
        </form>
    </div>

    <!-- Include Quill library -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    <script>
        // Initialize Quill editor with content
        const quill = new Quill('#editor', {
            theme: 'snow'
        });

        // Set the Quill editor content to match the article content
        quill.root.innerHTML = `<?php echo $article['content']; ?>`;

        // Ensure the form content includes the Quill editor data
        document.querySelector('form').onsubmit = function () {
            document.querySelector('input[name=content]').value = quill.root.innerHTML;
        };
    </script>
</body>

</html>
