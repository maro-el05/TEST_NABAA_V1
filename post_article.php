<?php
// Database connection settings
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


 $title = $_POST['title'];
 $content = $_POST['content'];
 $category = $_POST['category'];
    // Handle image upload
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = basename($_FILES['image']['name']);
        $target_dir = "uploads/";
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_url = $target_file;
        }
    }

    $content = strip_tags($_POST['content']);

    // Insert article into the database
    $stmt = $conn->prepare("INSERT INTO articles (title, content, image_url, category) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $content, $image_url, $category);
    
    if ($stmt->execute()) {
        // Redirect to the main page with a success message
        header("Location: home.php?success=1"); 
        exit();
    } else {
        echo "Error posting article: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
}
?>
