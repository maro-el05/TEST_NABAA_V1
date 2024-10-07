<?php
session_start();
// Database connection settings
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Invalid email format.";
    }
    // Check if name or password are empty
    elseif (empty($name) || empty($password)) {
        $error_message = "Name and password cannot be empty.";
    }
    // Check password strength (optional)
    elseif (strlen($password) < 6) {
        $error_message = "Password must be at least 6 characters long.";
    }
    else {
        // Hash the password using md5
        $hashed_password = md5($password);

        $query = "INSERT INTO users (name, email, password, role ) VALUES (?, ?, ?, 'user')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            header("Location: login.php?registration=success");
            exit;
        } else {
            $error_message = "Registration failed: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGN UP</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    body {
    background-color: #2f2f2f; /* Dark Gray Background */
    font-family: Arial, sans-serif;
}

.card {
    border: none;
    border-radius: 10px;
    background-color: #f8f9fa;
}

.card-title {
    color: #b30000; /* Red */
    font-weight: bold;
    margin-bottom: 20px;
}

.form-control {
    border-radius: 5px;
    border: 1px solid #b30000; /* Red Border for Input Fields */
}

label {
    color: #4f4f4f; /* Dark Gray Label Text */
}

.btn-primary {
    background-color: #b30000; /* Red Button */
    border-color: #b30000;
    font-size: 16px;
    font-weight: bold;
    padding: 10px;
}

.btn-primary:hover {
    background-color: #800000; /* Darker Red on Hover */
    border-color: #800000;
}

.card {
    box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.3); /* Soft Shadow */
}

.inline-text {
    margin-top: 20px;
}

.btn-link{
  color:#2f2f2f;
}
</style>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-body text-center p-4">
                        <h3 class="card-title text-danger">Create an account</h3>

                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control border-danger" id="name" name="name" placeholder="Enter your name" required>

                                <label for="email">Email:</label>
                                <input type="email" class="form-control border-danger" id="email" name="email" placeholder="Enter your email" required> 

                                <label for="password">Password:</label>
                                <input type="password" class="form-control border-danger" id="password" name="password" placeholder="Enter Password" required>
                            </div>
                            <button type="submit" class="btn btn-danger btn-block">REGISTER</button>
                        </form>

                        <?php if (isset($error_message)): ?>
                            <div class="alert alert-danger mt-3" role="alert">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
