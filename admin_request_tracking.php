<?php
require 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and fetch form data for editing
    $application_id = mysqli_real_escape_string($conn, $_POST['application_id']);
    $tenant_name = mysqli_real_escape_string($conn, $_POST['tenant_name']);
    $scope_of_work = mysqli_real_escape_string($conn, $_POST['scope_of_work']);
    $date_from = mysqli_real_escape_string($conn, $_POST['date_from']);
    $date_to = mysqli_real_escape_string($conn, $_POST['date_to']);
    $time_from = mysqli_real_escape_string($conn, $_POST['time_from']);
    $time_to = mysqli_real_escape_string($conn, $_POST['time_to']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);

    // Update the database with the edited information
    $sql = "UPDATE work_permits SET tenant_name='$tenant_name', scope_of_work='$scope_of_work', date_from='$date_from', date_to='$date_to', time_from='$time_from', time_to='$time_to', status='$status', remarks='$remarks' WHERE application_id='$application_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Request updated successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch existing requests for display
$sql = "SELECT * FROM work_permits";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Request Tracking</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #eafaf1; }
        .container { margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Admin Maintenance Request Tracking</h2>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Application ID</th>
                    <th>Tenant Name</th>
                    <th>Scope of Work</th>
                    <th>Date From</th>
                    <th>Date To</th>
                    <th>Time From</th>
                    <th>Time To</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= isset($row['application_id']) ? htmlspecialchars($row['application_id']) : 'N/A' ?></td>
                            <td><?= isset($row['tenant_name']) ? htmlspecialchars($row['tenant_name']) : 'N/A' ?></td>
                            <td><?= isset($row['scope_of_work']) ? htmlspecialchars($row['scope_of_work']) : 'N/A' ?></td>
                            <td><?= isset($row['date_from']) ? htmlspecialchars($row['date_from']) : 'N/A' ?></td>
                            <td><?= htmlspecialchars($row['date_to']) ?></td>
                            <td><?= htmlspecialchars($row['time_from']) ?></td>
                            <td><?= htmlspecialchars($row['time_to']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <td><?= htmlspecialchars($row['remarks']) ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="application_id" value="<?= htmlspecialchars($row['application_id']) ?>">
                                    <input type="text" name="tenant_name" value="<?= htmlspecialchars($row['tenant_name']) ?>" required>
                                    <input type="text" name="scope_of_work" value="<?= htmlspecialchars($row['scope_of_work']) ?>" required>
                                    <input type="date" name="date_from" value="<?= htmlspecialchars($row['date_from']) ?>" required>
                                    <input type="date" name="date_to" value="<?= htmlspecialchars($row['date_to']) ?>" required>
                                    <input type="time" name="time_from" value="<?= htmlspecialchars($row['time_from']) ?>" required>
                                    <input type="time" name="time_to" value="<?= htmlspecialchars($row['time_to']) ?>" required>
                                    <input type="text" name="status" value="<?= htmlspecialchars($row['status']) ?>" required>
                                    <input type="text" name="remarks" value="<?= htmlspecialchars($row['remarks']) ?>" required>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center">No maintenance requests found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
