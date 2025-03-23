<?php
session_start();

// Database connection
function get_db_connection() {
    $servername = "localhost"; // Replace with your server name
    $username = "root";        // Replace with your database username
    $password = "";            // Replace with your database password
    $dbname = "tesys";         // Database name

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Check if user is logged in and is a tenant
function check_login_status() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'tenant') {
        header("Location: login.php");
        exit();
    }
}

check_login_status(); // Ensure user is logged in

$success_message = '';
$error_message = '';

// Define file upload directory
$uploadDir = 'uploads/'; // Make sure this directory exists and is writable

// Function to handle file uploads
function uploadFile($file, $uploadDir) {
    $fileName = basename($file["name"]);
    $targetFile = $uploadDir . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    // Check if file is a valid type (for example, allow only pdf and images)
    $validTypes = ['jpg', 'jpeg', 'png', 'pdf'];
    if (!in_array($fileType, $validTypes)) {
        return false;
    }
    
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return $targetFile;
    } else {
        return false;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process the files
    $letter_of_intent_path = uploadFile($_FILES['letter_of_intent'], $uploadDir);
    $business_profile_path = uploadFile($_FILES['business_profile'], $uploadDir);
    $business_registration_path = uploadFile($_FILES['business_registration'], $uploadDir);
    $valid_id_path = uploadFile($_FILES['valid_id'], $uploadDir);
    $bir_registration_path = uploadFile($_FILES['bir_registration'], $uploadDir);
    $financial_statement_path = uploadFile($_FILES['financial_statement'], $uploadDir);
    
    if ($letter_of_intent_path && $business_profile_path && $business_registration_path && $valid_id_path && $bir_registration_path && $financial_statement_path) {
        $conn = get_db_connection();

        // Prepare SQL query
       // Prepare SQL query with correct column names
$query = "INSERT INTO tenant_applications 
(tenant_name, letter_of_intent_path, business_profile_path, business_registration_path, valid_id_path, bir_registration_path, financial_statement_path, application_date) 
VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

// Prepare the statement
$stmt = $conn->prepare($query);

// Check if preparation was successful
if (!$stmt) {
die("Prepare failed: " . $conn->error);
}

// Bind parameters
$tenant_name = $_SESSION['user_id']; // Assuming 'user_id' stores tenant name or identifier
$stmt->bind_param(
"sssssss", 
$tenant_name,
$letter_of_intent_path,
$business_profile_path,
$business_registration_path,
$valid_id_path,
$bir_registration_path,
$financial_statement_path
);

// Execute the statement
if ($stmt->execute()) {
$success_message = "Application submitted successfully!";
} else {
$error_message = "Error submitting your application: " . $stmt->error;
}

// Close statement and connection
$stmt->close();
$conn->close();

    } else {
        $error_message = "File upload failed. Please check your files.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submission</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('img/bg.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .main-content {
            width: 70%;
            max-width: 800px;
            margin: 50px auto;
            background-color: rgba(255, 255, 255, 0.9); /* Slightly opaque white */
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }
        h1 {
            color: #006400;
            text-align: center;
            margin-bottom: 30px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        label {
            color: #006400;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        button {
            padding: 12px 25px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .success-message, .error-message {
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .success-message {
            color: green;
            font-weight: bold;
        }
        .error-message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <h1>Application Submission</h1>
        <?php if ($success_message): ?>
            <p class="success-message"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form action="process.php" method="POST" enctype="multipart/form-data">
            <label for="tenant_name">Tenant Name:</label>
            <input type="text" id="tenant_name" name="tenant_name" required>

            <label for="letter_of_intent">Letter of Intent:</label>
            <input type="file" id="letter_of_intent" name="letter_of_intent" required>

            <label for="business_profile">Business Profile:</label>
            <input type="file" id="business_profile" name="business_profile" required>

            <label for="business_registration">Business Registration:</label>
            <input type="file" id="business_registration" name="business_registration" required>

            <label for="valid_id">Valid ID:</label>
            <input type="file" id="valid_id" name="valid_id" required>

            <label for="bir_registration">BIR Registration:</label>
            <input type="file" id="bir_registration" name="bir_registration" required>

            <label for="financial_statement">Financial Statement:</label>
            <input type="file" id="financial_statement" name="financial_statement" required>

            <button type="submit">Submit Application</button>
        </form>
    </div>
</body>
</html>
