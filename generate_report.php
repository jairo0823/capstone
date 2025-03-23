<?php
session_start();
include 'db_config.php';

// Check if the user is logged in and if they are an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Function to update lease status
function updateLeaseStatus($tenant_id, $new_status) {
    global $conn;

    // Validate new_status value
    if (!in_array($new_status, ['Active', 'Expired', 'Pending'])) {
        return false; // Invalid status
    }

    $query = "UPDATE tenants SET lease_status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $new_status, $tenant_id);

    // Execute the query
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Function to generate and download the report as CSV (including payment info)
function generateReport() {
    global $conn;

    // Fetch tenant data along with payment details
    $query = "
    SELECT t.id, t.firstname, t.lastname, t.lease_status, 
           p.amount_paid, p.payment_date, p.payment_status
    FROM tenants t
    LEFT JOIN payments p ON t.id = p.tenant_id
";

    $result = $conn->query($query);

    // Open the output buffer for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="tenant_report_with_payments.csv"');

    $output = fopen('php://output', 'w');
    
    // Write the header row (with payment-related columns)
    fputcsv($output, ['ID', 'First Name', 'Last Name', 'Lease Status', 'Payment Amount', 'Payment Date', 'Payment Status']);

    // Write data rows (including payment details)
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}

// Check if the lease status update form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_lease_status'])) {
    $tenant_id = $_POST['tenant_id'];
    $new_status = $_POST['lease_status'];

    // Call the function to update lease status
    if (updateLeaseStatus($tenant_id, $new_status)) {
        echo "Lease status updated successfully!";
    } else {
        echo "Failed to update lease status.";
    }
}

// Handle report generation request
if (isset($_GET['generate_report'])) {
    generateReport();
    exit();
}

// Fetch tenant data for the dashboard
$tenants_query = "SELECT id, firstname, lastname, lease_status FROM tenants";
$tenants_result = $conn->query($tenants_query);
$tenants = $tenants_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Report</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .btn-report {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 12px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn-report:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Generate Tenant Report</h1>
        <a href="?generate_report=true" class="btn-report">Generate Report</a>
    </div>
</body>
</html>
