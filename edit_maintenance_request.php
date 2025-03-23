<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    // Redirect to login page
}

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tesys"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the maintenance request details
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM maintenance_requests WHERE id=$id";
    $result = $conn->query($sql);
    $request = $result->fetch_assoc();
}

// Update the maintenance request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $admin_name = $_POST['admin_name'];
    $maintenance_details = $_POST['maintenance_details'];
    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];
    $time_from = $_POST['time_from'];
    $time_to = $_POST['time_to'];
    $tenant_email = $_POST['tenant_email'];

    $updateSql = "UPDATE maintenance_requests SET admin_name='$admin_name', maintenance_details='$maintenance_details', date_from='$date_from', date_to='$date_to', time_from='$time_from', time_to='$time_to', tenant_email='$tenant_email' WHERE id=$id";

    if ($conn->query($updateSql) === TRUE) {
        echo "Maintenance request updated successfully.";
        header('Location: manage_requirments.php'); 
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Maintenance Request</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
        .container { width: 70%; margin: auto; border: 2px solid #28a745; padding: 20px; background: white; border-radius: 10px; }
        .header { text-align: center; font-size: 24px; font-weight: bold; color: #28a745; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #28a745; padding: 8px; text-align: left; }
        input, textarea { width: 100%; padding: 5px; border: 1px solid #28a745; border-radius: 5px; }
        button { background-color: #28a745; color: white; border: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Edit Maintenance Request</div>
        <form method="POST">
            <table>
                <tr>
                    <th>Admin Name</th>
                    <td><input type="text" name="admin_name" value="<?= htmlspecialchars($request['admin_name']) ?>" required></td>
                </tr>
                <tr>
                    <th>Maintenance Details</th>
                    <td><textarea name="maintenance_details" required><?= htmlspecialchars($request['maintenance_details']) ?></textarea></td>
                </tr>
                <tr>
                    <th>Permit Valid From</th>
                    <td>From <input type="date" name="date_from" value="<?= htmlspecialchars($request['date_from']) ?>" required></td>
                    <th>To</th>
                    <td><input type="date" name="date_to" value="<?= htmlspecialchars($request['date_to']) ?>" required></td>
                </tr>
                <tr>
                    <th>Time From</th>
                    <td>From <input type="time" name="time_from" value="<?= htmlspecialchars($request['time_from']) ?>" required></td>
                    <th>To</th>
                    <td><input type="time" name="time_to" value="<?= htmlspecialchars($request['time_to']) ?>" required></td>
                </tr>
                <tr>
                    <th>Tenant Email</th>
                    <td><input type="email" name="tenant_email" value="<?= htmlspecialchars($request['tenant_email']) ?>" required></td>
                </tr>
            </table>
            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>
<?php 
$conn->close(); // Close the database connection
?>
