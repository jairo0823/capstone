<?php
require 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and fetch form data for lease status update
    $tenant_name = mysqli_real_escape_string($conn, $_POST['tenant_name']);
    $renewal_date = mysqli_real_escape_string($conn, $_POST['renewal_date']);
    $expiration_date = mysqli_real_escape_string($conn, $_POST['expiration_date']);

    // Update the database with the new lease status
    $sql = "UPDATE tenantsheet SET renewal_date='$renewal_date', expiration_date='$expiration_date' WHERE tenant_name='$tenant_name'";

    if ($conn->query($sql) === TRUE) {
        echo "Lease status updated successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Lease Status</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2 class="mt-5">Update Lease Status</h2>
    <form action="" method="POST">
        <div class="mb-3">
            <label for="tenant_name" class="form-label">Tenant Name</label>
            <select class="form-control" id="tenant_name" name="tenant_name" required>
                <?php
                // Fetch renewal requests from the database
                $sql = "SELECT username, renewal_date FROM renewal_requests WHERE status='pending'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='".htmlspecialchars($row['username'])."'>".htmlspecialchars($row['username'])." - ".htmlspecialchars($row['renewal_date'])."</option>";
                    }
                } else {
                    echo "<option value=''>No pending renewal requests found</option>";
                }
                ?>
            </select>
