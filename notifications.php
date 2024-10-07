 <!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .notifications-title {
            text-align: center; /* Center the title */
            font-size: 2.5rem; /* Increase font size */
            margin-bottom: 40px; /* Add margin to lower the title */
        }
        .custom-left-margin {
            margin-left: -150px; /* Adjust as needed for left positioning */
        }
        .custom-right-margin {
            margin-left: 200px; /* Adjust as needed to move comments to the right */
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="notifications-title text-center">Notifications</h2>
    <div class="row"> <!-- Flex container for the columns -->
        <div class="col-md-6"> <!-- Left column for messages -->
            <div class="container custom-left-margin">
                <h3 class="text-center">Messages</h3> <!-- Section title for messages -->
                <!-- Include messages from messages.php -->
                <?php include 'messages.php'; ?>
            </div>
        </div>
        <div class="col-md-6"> <!-- Right column for comments -->
            <div class="container custom-right-margin"> <!-- Added custom right margin -->
                <h3 class="text-center">Comments</h3> <!-- Section title for comments -->
                <!-- Include comments from commentsPage.php -->
                <?php include 'commentsPage.php'; ?>
            </div>
        </div>
    </div>
</div>

<script>
   function markAsRead(type, id) {
    console.log("Marking as read:", type, id); // Debugging line
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "markAsRead.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log("Response:", xhr.responseText); // Debugging line
            if (xhr.responseText.trim() === 'success') {
                var button = document.getElementById(type + '-btn-' + id);
                button.classList.remove('btn-primary');
                button.classList.add('btn-success');
                button.innerHTML = 'Read';
                button.disabled = true;
            } else {
                console.error(xhr.responseText);  // Log any error message
            }
        }
    };
    xhr.send("type=" + type + "&id=" + id);
}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>