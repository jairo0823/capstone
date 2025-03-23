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

// Fetch renewal requests
$sql = "SELECT rr.request_id, rr.id, rr.renewal_date, rr.status, t.firstname, t.lastname 
        FROM renewal_requests rr 
        JOIN tenants t ON rr.username = t.id"; // Ensure `rr.username` refers to `t.id`
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Renewal Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        table th {
            background-color: #007BFF;
            color: white;
        }
        .btn {
            padding: 10px 20px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Renewal Requests</h1>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Tenant Name</th>
            <th>Renewal Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['firstname'] . ' ' . $row['lastname'] ?></td> <!-- Display tenant's full name -->
                    <td><?= $row['renewal_date'] ?></td>
                    <td><?= $row['status'] ?></td>
                    <td>
                        <a href="edit_request.php?id=<?= $row['id'] ?>" class="btn">Edit</a>
                        <a href="delete_request.php?id=<?= $row['id'] ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No renewal requests found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php $conn->close(); ?>
