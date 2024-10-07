 <?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db_connection.php');
// Check if type and id are set in the POST request
if (isset($_POST['type']) && isset($_POST['id'])) {
    $type = $_POST['type'];
    $id = $_POST['id'];

    // Determine which table to update based on the type
    if ($type == 'message') {
        // Update the read status for the message in the contact table
        $sql = "UPDATE contact SET read_status = 1 WHERE id_contact = ?";
    } elseif ($type == 'comment') {
        // Update the read status for the comment in the comment table
        $sql = "UPDATE comments SET read_status = 1 WHERE id = ?";
    } else {
        echo "Invalid type provided.";
        exit;
    }

    // Prepare and bind the statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Check if the update was successful
        if ($stmt->affected_rows > 0) {
            echo "success";  // Return 'success' for the AJAX call
        } else {
            echo "Failed to mark as read.";
        }

        $stmt->close();
    } else {
        echo "Failed to prepare the statement.";
    }
} else {
    echo "Invalid request. Type and ID are required.";
}

// Close connection
$conn->close();
?>