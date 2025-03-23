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

// Fetch announcements from the database
$sql = "SELECT * FROM announcements ORDER BY created_at DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Announcements</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('img/bg.jpg') no-repeat center center fixed;
            background-color: #f4f6f9;
            color: #333;
            padding: 50px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
        }
        h1 {
            text-align: center;
            color: #4CAF50;
        }
        .announcement {
            background-color: #f9f9f9;
            border-left: 5px solid #4CAF50;
            margin: 10px 0;
            padding: 15px;
        }
        .announcement h3 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .announcement p {
            font-size: 16px;
            color: #666;
        }
        .announcement small {
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Latest Announcements</h1>

        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='announcement'>";
                echo "<h3>Announcement from Xentro Mall</h3>";
                echo "<p>" . nl2br($row['announcement']) . "</p>";
                echo "<small>Posted on " . $row['created_at'] . "</small>";
                echo "</div>";
            }
        } else {
            echo "<p>No announcements available.</p>";
        }
        ?>

    </div>

</body>
</html>

<?php
$conn->close();
?>
