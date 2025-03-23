<?php
// submit_renewal.php
session_start();
include 'db_config.php'; // Include DB connection

// Ensure tenant is logged in and username is available
if (!isset($_SESSION['username'])) {
    die("Error: Tenant is not logged in. Please log in to submit a renewal request.");
}

$username = $_SESSION['username']; // Fetch tenant username from the session

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    if (!isset($_POST['renewal_date']) || empty($_POST['renewal_date'])) {
        die("Error: Renewal date is required.");
    }

    $renewal_date = $_POST['renewal_date'];

    // Insert the renewal request into the database
    $sql = "INSERT INTO renewal_requests (username, renewal_date, status) VALUES (?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $renewal_date);

    if ($stmt->execute()) {
        echo "<h1>Renewal request submitted successfully!</h1><p>Your request will be reviewed by the admin.</p>";
    } else {
        echo "Error submitting request: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    die("Invalid request method.");
}
?>
