<?php
require 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and fetch form data for editing
    $application_id = mysqli_real_escape_string($conn, $_POST['application_id']);
    $tradename = mysqli_real_escape_string($conn, $_POST['tradename']);
    $store_premises = mysqli_real_escape_string($conn, $_POST['store_premises']);
    $store_location = mysqli_real_escape_string($conn, $_POST['store_location']);
    $ownership = mysqli_real_escape_string($conn, $_POST['ownership']);
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $business_address = mysqli_real_escape_string($conn, $_POST['business_address']);
    $tin = mysqli_real_escape_string($conn, $_POST['tin']);
    $office_tel = mysqli_real_escape_string($conn, $_POST['office_tel']);
    $tenant_representative = mysqli_real_escape_string($conn, $_POST['tenant_representative']);
    $contact_person = mysqli_real_escape_string($conn, $_POST['contact_person']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $contact_tel = mysqli_real_escape_string($conn, $_POST['contact_tel']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $prepared_by = mysqli_real_escape_string($conn, $_POST['prepared_by']);

    // Update the database with the edited information
    $sql = "UPDATE tenantsheet SET tradename='$tradename', store_premises='$store_premises', store_location='$store_location', ownership='$ownership', company_name='$company_name', business_address='$business_address', tin='$tin', office_tel='$office_tel', tenant_representative='$tenant_representative', contact_person='$contact_person', position='$position', contact_tel='$contact_tel', mobile='$mobile', email='$email', prepared_by='$prepared_by' WHERE application_id='$application_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Tenant information updated successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch existing requests for display
$sql = "SELECT * FROM tenantsheet";
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
            <h2>Admin Form Tracking</h2>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Application ID</th>
                    <th>Trade Name</th>
                    <th>Store Premises</th>
                    <th>Store Location</th>
                    <th>Ownership</th>
                    <th>Company Name</th>
                    <th>Business Address</th>
                    <th>TIN</th>
                    <th>Office Tel</th>
                    <th>Tenant Representative</th>
                    <th>Contact Person</th>
                    <th>Position</th>
                    <th>Contact Tel</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Prepared By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['application_id']) ?></td>
                            <td><?= htmlspecialchars($row['tradename']) ?></td>
                            <td><?= htmlspecialchars($row['store_premises']) ?></td>
                            <td><?= htmlspecialchars($row['store_location']) ?></td>
                            <td><?= htmlspecialchars($row['ownership']) ?></td>
                            <td><?= htmlspecialchars($row['company_name']) ?></td>
                            <td><?= htmlspecialchars($row['business_address']) ?></td>
                            <td><?= htmlspecialchars($row['tin']) ?></td>
                            <td><?= htmlspecialchars($row['office_tel']) ?></td>
                            <td><?= htmlspecialchars($row['tenant_representative']) ?></td>
                            <td><?= htmlspecialchars($row['contact_person']) ?></td>
                            <td><?= htmlspecialchars($row['position']) ?></td>
                            <td><?= htmlspecialchars($row['contact_tel']) ?></td>
                            <td><?= htmlspecialchars($row['mobile']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['prepared_by']) ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="application_id" value="<?= htmlspecialchars($row['application_id']) ?>">
                                    <input type="text" name="tradename" value="<?= htmlspecialchars($row['tradename']) ?>" required>
                                    <input type="text" name="store_premises" value="<?= htmlspecialchars($row['store_premises']) ?>" required>
                                    <input type="text" name="store_location" value="<?= htmlspecialchars($row['store_location']) ?>" required>
                                    <select name="ownership" required>
                                        <option value="Corporation" <?= $row['ownership'] == 'Corporation' ? 'selected' : '' ?>>Corporation</option>
                                        <option value="Sole Proprietor" <?= $row['ownership'] == 'Sole Proprietor' ? 'selected' : '' ?>>Sole Proprietor</option>
                                        <option value="Partnership" <?= $row['ownership'] == 'Partnership' ? 'selected' : '' ?>>Partnership</option>
                                    </select>
                                    <input type="text" name="company_name" value="<?= htmlspecialchars($row['company_name']) ?>" required>
                                    <input type="text" name="business_address" value="<?= htmlspecialchars($row['business_address']) ?>" required>
                                    <input type="text" name="tin" value="<?= htmlspecialchars($row['tin']) ?>">
                                    <input type="text" name="office_tel" value="<?= htmlspecialchars($row['office_tel']) ?>">
                                    <input type="text" name="tenant_representative" value="<?= htmlspecialchars($row['tenant_representative']) ?>">
                                    <input type="text" name="contact_person" value="<?= htmlspecialchars($row['contact_person']) ?>">
                                    <input type="text" name="position" value="<?= htmlspecialchars($row['position']) ?>">
                                    <input type="text" name="contact_tel" value="<?= htmlspecialchars($row['contact_tel']) ?>">
                                    <input type="text" name="mobile" value="<?= htmlspecialchars($row['mobile']) ?>">
                                    <input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>">
                                    <input type="text" name="prepared_by" value="<?= htmlspecialchars($row['prepared_by']) ?>">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="17" class="text-center">No tenant information found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
</content>
</create_file>