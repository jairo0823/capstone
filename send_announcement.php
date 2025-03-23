<?php
// Enable error reporting for debugging (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tesys";

// Create a new MySQLi connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("<p style='color: red;'>Connection failed: " . $conn->connect_error . "</p>");
}

$messageFeedback = ""; // Variable to store feedback messages

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user input to prevent SQL injection
    $announcement = $conn->real_escape_string($_POST['announcement']);

    // Save announcement to the database
    $sql = "INSERT INTO announcements (announcement) VALUES ('$announcement')";
    if ($conn->query($sql) === TRUE) {
        $messageFeedback .= "<p style='color: green;'>Announcement saved successfully!</p>";
    } else {
        $messageFeedback .= "<p style='color: red;'>Error saving announcement: " . $conn->error . "</p>";
    }

    // Fetch tenants' emails
    $emailQuery = "SELECT email FROM tenants";
    $result = $conn->query($emailQuery);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Here you would normally send emails (PHPMailer or mail())
            // For now, we just simulate sending emails
            $to = $row['email'];
            $subject = "New Announcement from Xentro Mall";
            $message = "Announcement: $announcement";
            // Simulate email sending feedback
            $messageFeedback .= "<p style='color: blue;'>Email sent to: $to</p>";
        }
    } else {
        $messageFeedback .= "<p style='color: red;'>No tenants found!</p>";
    }
}

// Close the database connection (only after all operations are complete)
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Announcement</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to bottom right, #e0f7fa, #80deea);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
            flex-direction: column;
        }
        .container {
            background: #ffffff;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 600px;
            text-align: center;
        }
        h1 {
            color: #00796b;
            margin-bottom: 25px;
            font-size: 24px;
        }
        textarea {
            width: 100%;
            height: 150px;
            padding: 15px;
            border: 1px solid #b2dfdb;
            border-radius: 10px;
            resize: none;
            font-size: 16px;
            margin-bottom: 25px;
            transition: border-color 0.3s;
        }
        textarea:focus {
            border-color: #00796b;
            outline: none;
        }
        button {
            background-color: #00796b;
            color: white;
            padding: 15px;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }
        button:hover {
            background-color: #004d40;
            transform: translateY(-2px);
        }
        .feedback {
            margin-top: 20px;
            font-size: 16px;
        }
        /* Back Button Styles */
        .back-button {
            position: absolute;
            bottom: 20px;
            left: 20px;
            background-color: transparent;
            border: none;
            font-size: 24px;
            cursor: pointer;
            padding: 10px;
            color: #00796b;
            text-decoration: none;
        }
        .back-button:hover {
            color: #004d40;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Send Announcement</h1>
        <form method="POST" action="">
            <textarea name="announcement" placeholder="Type your announcement here..." required></textarea>
            <button type="submit">Send Announcement</button>
        </form>
        <div class="feedback">
            <?php echo $messageFeedback; ?>
        </div>
    </div>

    <!-- Back Button -->
    <a href="javascript:history.back()" class="back-button">&larr; Go Back</a>
</body>
</html>
