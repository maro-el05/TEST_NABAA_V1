<?php

// Database connection settings
$servername = "localhost";
$username = "marouane";   // Your database username
$password = "";       // Your database password
$dbname = "news_website";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>