<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    // Redirect to login page or show error message
    
}

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tesys"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the application details if application_id is provided
if (isset($_GET['application_id'])) {
    $id = $_GET['application_id'];
    $sql = "SELECT * FROM tenant_applications WHERE application_id=$id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        // Application not found, redirect back to the list page or show an error
        header("Location: manage_requirements.php");
        exit();
    }
}

// Function to send feedback email
function sendFeedbackEmail($tenantEmail, $tenantName, $feedback) {
    $subject = "Feedback on your Application";
    $message = "Dear " . htmlspecialchars($tenantName) . ",\n\n" . 
               "Here is the feedback for your application:\n\n" .
               htmlspecialchars($feedback) . "\n\n" .
               "Best regards,\nAdmin";
    $headers = "From: jairopogirobiso@gmail.com"; // Replace with the actual email of the sender

    return mail($tenantEmail, $subject, $message, $headers);
}

// Handle form submission to update the application and send feedback
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input
    $tenant_name = $conn->real_escape_string($_POST['tenant_name']);
    $remarks = $conn->real_escape_string($_POST['remarks']);
    $status = $conn->real_escape_string($_POST['status']);
    $feedback = $conn->real_escape_string($_POST['feedback']); // Feedback input

    // Update application in the database
    $updateSql = "UPDATE tenant_applications SET tenant_name='$tenant_name', remarks='$remarks', status='$status' WHERE application_id=$id";
    if ($conn->query($updateSql) === TRUE) {
        // Optionally, save the feedback to the database
        if (!empty($feedback)) {
            $feedbackSql = "INSERT INTO tenant_feedback (application_id, feedback) VALUES ($id, '$feedback')";
            $conn->query($feedbackSql);
        }

        // Fetch tenant's email address from the database
        $tenantEmail = $row['tenant_email']; // Assuming there's a column `tenant_email`

        // Send feedback email
        if (!empty($tenantEmail) && !empty($feedback)) {
            if (sendFeedbackEmail($tenantEmail, $tenant_name, $feedback)) {
                echo "Feedback sent successfully to the tenant.";
            } else {
                echo "Failed to send feedback email.";
            }
        }

        // Set success message in session
        $_SESSION['update_success'] = "Update successful!";
        
        // Redirect to the management page after submission
        header("Location: manage_requirements.php");
        exit();
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
    <title>Edit Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }
        h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block }
        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
        .success {
            color: green;
            text-align: center;
            margin-bottom: 15px;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Edit Application</h1>

    <?php if (isset($_SESSION['update_success'])): ?>
        <p class="success"><?php echo $_SESSION['update_success']; unset($_SESSION['update_success']); ?></p>
    <?php endif; ?>

    <?php if (isset($row)): ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) . '?application_id=' . $id; ?>">
            <label for="tenant_name">Tenant Name:</label>
            <input type="text" id="tenant_name" name="tenant_name" value="<?php echo htmlspecialchars($row['tenant_name']); ?>" required>

            <label for="remarks">Remarks:</label>
            <textarea id="remarks" name="remarks" required><?php echo htmlspecialchars($row['remarks']); ?></textarea>

            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="Pending" <?php if ($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="Approved" <?php if ($row['status'] == 'Approved') echo 'selected'; ?>>Approved</option>
                <option value="Declined" <?php if ($row['status'] == 'Declined') echo 'selected'; ?>>Declined</option>
            </select>

            <label for="feedback">Send Feedback to Tenant:</label>
            <textarea id="feedback" name="feedback" placeholder="Enter your feedback for the tenant here..."></textarea>

            <button type="submit">Update Application</button>
        </form>
    <?php else: ?>
        <p class="error">Application not found.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php
$conn->close();
?>