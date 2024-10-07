<?php
include('db_connection.php');

if ($_POST) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];

    $sql = "UPDATE articles SET title = ?, content = ?, category = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $title, $content, $category, $id);

    if ($stmt->execute()) {
        echo "Article updated successfully.";
    } else {
        echo "Error updating article.";
    }

    header('Location: home.php');
}
?>
