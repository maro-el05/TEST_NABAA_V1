<?php
session_start();
include('db_connection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $articleId = $_POST['article_id'];
    $username = $_POST['username']; // Get username from the form
    $comment = $_POST['comment']; // Get comment from the form

    // Validate inputs
    if (!empty($articleId) && !empty($username) && !empty($comment)) {
        // Prepare SQL to insert the comment
        $sqlInsertComment = "INSERT INTO comments (article_id, username, comment) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sqlInsertComment);
        $stmt->bind_param('iss', $articleId, $username, $comment);

        if ($stmt->execute()) {
            // Comment inserted successfully
            // Redirect back to the article page (replace with your article URL)
            header("Location: article.php?id=" . $articleId);
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "All fields are required.";
    }
}

// Close the connection
$conn->close();
?>