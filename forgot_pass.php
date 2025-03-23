<?php
// Initialize message variable
$message = '';
$redirect = false;  // This will control the redirect action

// Handling the form submission for password reset
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username']; // User enters this, e.g., 'sarahuto'
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if (empty($username)) {
        $message = "Username is required.";
    } elseif ($new_password !== $confirm_password) {
        $message = "Passwords do not match. Please try again.";
    } else {
        // Hash the new password before storing it in the database
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Database connection details (use your MySQL root credentials here)
        $servername = "localhost";
        $db_username = "root";  // Replace with your MySQL username
        $db_password = "";  
        $dbname = "tesys";  

        // Establish a database connection
        $mysqli = new mysqli($servername, $db_username, $db_password, $dbname);

        if ($mysqli->connect_error) {
            $message = "Connection failed: " . $mysqli->connect_error;
        } else {
            // Prepare the SQL statement to update the password for the user
            $stmt = $mysqli->prepare("UPDATE tenants SET password = ? WHERE username = ?");
            $stmt->bind_param("ss", $hashed_password, $username);

            // Execute the query
            if ($stmt->execute()) {
                $message = "Password updated successfully for user: $username!";
                $redirect = true;  // Set redirect flag to true on success
            } else {
                $message = "Error updating password. Please try again.";
            }

            // Close the prepared statement and database connection
            $stmt->close();
            $mysqli->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: ;
           
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(120deg, #a8e063, #56ab2f); /* Green gradient */
        }

        .wrapper {
            display: flex;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 700px;
            overflow: hidden;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .logo-container {
            background: #56ab2f;
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px;
        }

        .logo-container img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 5px solid #fff;
            object-fit: cover;
        }

        .form-container {
            flex: 2;
            padding: 30px;
        }

        .form-container h2 {
            margin: 0 0 20px;
            color: #56ab2f; /* Green color */
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #56ab2f; /* Green focus */
            box-shadow: 0 0 5px rgba(86, 171, 47, 0.5);
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #56ab2f; /* Green button */
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #3b8221; /* Darker green */
        }

        .message {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
            color: #555;
        }

        .success {
            color: #28a745;
        }

        .error {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Left Side: Logo -->
        <div class="logo-container">
            <!-- Replace with your logo image -->
            <img src="img/logo.jpg" alt="Logo">
        </div>

        <!-- Right Side: Form -->
        <div class="form-container">
            <h2>Reset Your Password</h2>

            <!-- Display message if it exists -->
            <?php if ($message): ?>
                <div class="message <?php echo (strpos($message, 'successfully') !== false) ? 'success' : 'error'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn">Update Password</button>
            </form>
            <div class="message">
                <p>Enter your username and set a new password.</p>
            </div>
        </div>
    </div>

    <!-- Add redirect script if the password is updated successfully -->
    <?php if ($redirect): ?>
        <script>
            setTimeout(function() {
                window.location.href = 'login.php'; // Redirect to login page
            }, 2000); // Redirect after 2 seconds
        </script>
    <?php endif; ?>
</body>
</html>
