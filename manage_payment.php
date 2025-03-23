<?php
session_start();
include 'db_config.php'; // Include your database configuration

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch all payment records
$query = "SELECT p.id, p.payment_image, t.firstname, t.lastname, p.upload_date 
          FROM payments p 
          JOIN tenants t ON p.tenant_id = t.id 
          ORDER BY p.upload_date DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payments</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('img/bg.jpg'); /* Add your background image path */
            background-size: cover;
            background-position: center;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h1 {
            text-align: center;
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #3498db;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1; /* Highlight row on hover */
        }
        img {
            max-width: 100px; /* Limit image size */
            max-height: 100px;
            cursor: pointer; /* Change cursor to pointer */
            border-radius: 5px; /* Rounded corners for images */
        }
        .back-btn {
            position: absolute;
            left: 10px;
            bottom: 10px;
            padding: 10px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .back-btn:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Uploaded Payment Images</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tenant Name</th>
                    <th>Payment Image</th>
                    <th>Upload Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></td>
                            <td>
                                <a href="<?php echo htmlspecialchars($row['payment_image']); ?>" target="_blank">
                                    <img src="<?php echo htmlspecialchars($row['payment_image']); ?>" alt="Payment Image">
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($row['upload_date']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No payment images uploaded yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Back button at the bottom-left -->
    <a href="admin_dashboard.php">
        <button class="back-btn">Back</button>
    </a>

</body>
</html>
