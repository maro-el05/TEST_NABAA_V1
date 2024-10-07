 <?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db_connection.php');

// Fetch all comments (both read and unread)
$sql = "SELECT id, username, comment, read_status FROM comments";
$result = $conn->query($sql);
?>

<!-- Style to make the 'Read' button green -->
<style>
    .btn-success {
        background-color: green;
        border-color: green;
    }
</style>

<div id="comments-section">
    <h4>Comments</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Username</th>
                <th>Comment</th>
                <th>Action</th> <!-- New column for Mark as Read button -->
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr id="comment-<?php echo $row['id']; ?>">
            <td><?php echo htmlspecialchars($row['username']); ?></td>
            <td><?php echo htmlspecialchars($row['comment']); ?></td>
            <td>
                <?php if ($row['read_status'] == 0): ?>
                    <button id="comment-btn-<?php echo $row['id']; ?>" 
                            class="btn btn-primary" 
                            onclick="markAsRead('comment', <?php echo $row['id']; ?>)">
                        Mark as Read
                    </button>
                <?php else: ?>
                    <button id="comment-btn-<?php echo $row['id']; ?>" 
                            class="btn btn-success" disabled>
                        Read
                    </button>
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr>
        <td colspan="3">No comments found.</td>
    </tr>
<?php endif; ?>

        </tbody>
    </table>
</div>

<?php
// Close connection
$conn->close();
?>