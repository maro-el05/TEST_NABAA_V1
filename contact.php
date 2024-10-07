<?php
// Database connection variables
include('db_connection.php');

// Retrieve form data
$email = isset($_POST['email']) ? $_POST['email'] : '';
$name = isset($_POST['name']) ? $_POST['name'] : '';
$message = isset($_POST['message']) ? $_POST['message'] : '';

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email format.";
    exit;
}

// Prepare and bind SQL statement
$stmt = $conn->prepare("INSERT INTO contact (email, name, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $email, $name, $message);

// Execute the statement
if ($stmt->execute()) {
    header("Location: home.php?success=1"); 
    exit();
} else {
    echo "Error: " . $stmt->error ;
}

// Close connections
$stmt->close();
$conn->close();
?>