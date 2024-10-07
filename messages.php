 <?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('db_connection.php');


// Fetch all contact messages (both read and unread)
$sql = "SELECT id_contact, email, name, message, read_status FROM contact";
$result = $conn->query($sql);
?>

<!-- Style to make the 'Read' button green -->
<style>
    .btn-success {
        background-color: green;
        border-color: green;
    }
</style>

<div id="messages-section">
    <h4>Contact Messages</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Email</th>
                <th>Name</th>
                <th>Message</th>
                <th>Action</th> <!-- New column for Mark as Read button -->
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr id="message-<?php echo $row['id_contact']; ?>">
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['message']); ?></td>
                        <td>
                            <?php if ($row['read_status'] == 0): ?>
                                <!-- Unread message: Show Mark as Read button -->
                                <button id="message-btn-<?php echo $row['id_contact']; ?>" 
                                        class="btn btn-primary" 
                                        onclick="markAsRead('message', <?php echo $row['id_contact']; ?>)">
                                    Mark as Read
                                </button>
                            <?php else: ?>
                                <!-- Read message: Show Read button (disabled) -->
                                <button id="message-btn-<?php echo $row['id_contact']; ?>" 
                                        class="btn btn-success" disabled>
                                    Read
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No messages found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php
// Close connection
$conn->close();
?>