<?php
session_start();
include 'db_config.php'; // Ensure this file contains your database connection details

// Ensure the user is logged in and tenant is identified in session
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit();
}

// Get the user_id from session
$user_id = $_SESSION['user_id'];

// Fetch tenant_id from tenants table
$stmt = $conn->prepare("SELECT id FROM tenants WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($tenant_id);
    $stmt->fetch(); // Retrieve the tenant ID
    $stmt->close();
} else {
    // Handle the case where the tenant is not found
    echo "Tenant not found!";
    exit();
}

$message = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $renewal_date = $_POST['renewal_date'];

    // Check if the tenant exists
    $stmt = $conn->prepare("SELECT id FROM tenants WHERE id = ?");
    $stmt->bind_param("i", $tenant_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Tenant exists, proceed to insert the renewal request
        $stmt->close(); // Close the previous statement

        // Prepare and bind the insert statement
        $stmt = $conn->prepare("INSERT INTO lease_renewal_requests (tenant_id, renewal_date) VALUES (?, ?)");
        $stmt->bind_param("is", $tenant_id, $renewal_date);

        if ($stmt->execute()) {
            $message = "Lease renewal request submitted successfully.";
        } else {
            $message = "Error: " . $stmt->error;
        }
    } else {
        $message = "Error: Tenant ID does not exist.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Lease Renewal Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4fdf4; /* Light green background */
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #388e3c; /* Green title */
        }
        label {
            font-size: 16px;
            color: #388e3c; /* Green label text */
        }
        input[type="text"], input[type="date"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #388e3c; /* Green border */
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #388e3c; /* Green button */
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
        }
        button:hover {
            background-color: #2c6e28; /* Darker green on hover */
        }
        .message {
            text-align: center;
            color: green;
            margin: 10px 0;
        }
        .error {
            text-align: center;
            color: red;
            margin: 10px 0;
        }
        .back-btn {
            width: 100%;
            padding: 10px;
            background-color: #388e3c; /* Green button */
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 10px;
        }
        .back-btn:hover {
            background-color: #2c6e28; /* Darker green on hover */
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Lease Renewal Request</h1>
    
    <?php if (isset($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <form action="" method="post">
        <!-- Display tenant_id automatically -->
        <label for="tenant_id">Tenant ID:</label>
        <input type="hidden" id="tenant_id" name="tenant_id" value="<?php echo $tenant_id; ?>" readonly><br>

        <label for="renewal_date">Desired Renewal Date:</label>
        <input type="date" id="renewal_date" name="renewal_date" required><br>

        <button type="submit">Submit Request</button>
    </form>

    <!-- Back Button -->
    <button class="back-btn" onclick="window.history.back()">Go Back</button>
</div>

</body>
</html>
