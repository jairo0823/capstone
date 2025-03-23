<?php
// Database connection
$host = 'localhost';
$db = 'tesys';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success_message = ""; // Variable to store success message

// Insert renewal request into the database
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the necessary POST variables exist
    if (isset($_POST['request_id']) && isset($_POST['renewal_date'])) {
        $request_id = $_POST['request_id'];
        $renewal_date = $_POST['renewal_date'];

        // Prepare the SQL query to insert data into the renewal_requests table
        $sql = "INSERT INTO renewal_requests (request_id, renewal_date, status) VALUES (?, ?, 'Pending')";
        $stmt = $conn->prepare($sql);

        // Bind the parameters (request_id is an integer, renewal_date is a string)
        $stmt->bind_param("is", $request_id, $renewal_date);

        // Execute the statement and check for success
        if ($stmt->execute()) {
            $success_message = "Renewal request submitted successfully!";
        } else {
            $success_message = "Error: " . $conn->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        $success_message = "Error: Missing request_id or renewal_date.";
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Renewal Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f8ff; /* Light background color */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            width: 400px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
        }
        h1 {
            color: #333;
        }
        label {
            font-size: 16px;
            margin-bottom: 8px;
            display: block;
            color: #333;
        }
        input[type="number"], input[type="date"] {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 15px;
            font-size: 16px;
            color: green;
        }
        .message.error {
            color: red;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Submit Renewal Request</h1>
    <form method="POST" action="">
        <label for="request_id">Request ID:</label>
        <input type="number" name="request_id" id="request_id" required><br><br>

        <label for="renewal_date">Renewal Date:</label>
        <input type="date" name="renewal_date" id="renewal_date" required><br><br>

        <input type="submit" value="Submit Renewal Request">
    </form>

    <?php if ($success_message): ?>
        <div class="message <?= strpos($success_message, 'Error') !== false ? 'error' : ''; ?>">
            <?= $success_message; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
