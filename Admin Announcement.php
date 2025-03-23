<?php
// Connection to the database
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "tesys"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $announcement = $_POST['announcement'];
    
    // Insert the announcement into the database
    $sql = "INSERT INTO announcements (announcement) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $announcement);
    if ($stmt->execute()) {
        echo "<p style='color: green;'>Announcement sent successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error sending announcement!</p>";
    }

    // Assuming you have a tenants table where tenant emails are stored
    $sql = "SELECT email FROM tenants";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $to = $row['email'];
            $subject = "New Announcement from Xentro Mall";
            $message = "<html><body>";
            $message .= "<h2 style='color: #4CAF50;'>Xentro Mall Announcement</h2>";
            $message .= "<p style='font-size: 18px;'>$announcement</p>";
            $message .= "</body></html>";
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
            $headers .= "From: info@xentromall.com" . "\r\n"; // Change to your email

            mail($to, $subject, $message, $headers);
        }
    } else {
        echo "<p style='color: red;'>No tenants found!</p>";
    }
}

$conn->close();
?>

