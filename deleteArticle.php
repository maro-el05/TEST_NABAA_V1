<?php
include('db_connection.php');

if (isset($_GET['id'])) {
    $article_id = $_GET['id'];
    $sql = "DELETE FROM articles WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $article_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Article deleted successfully.";
    } else {
        echo "Error deleting article.";
    }
    header('Location: manageArticles.php');
}
?>
