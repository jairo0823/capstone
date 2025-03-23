<?php
session_start();

require "emailLibrary/EmailLibrary.php"; // Ensure this path is correct for PHPMailer autoload
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection details
$driver = 'mysql';
$host = 'localhost';
$dbname = 'tesys';
$charset = 'utf8';
$username = 'root';
$password = '';
$dsn = "$driver:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_EMULATE_PREPARES => FALSE,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

// Create the database connection
function get_db_connection() {
    global $dsn, $username, $password, $options;
    try {
        $conn = new PDO($dsn, $username, $password, $options);
        return $conn;
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

// Ensure user is logged in
function check_login_status() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'tenant') {
        header("Location: login.php");
        exit();
    }
}

check_login_status(); // Ensure user is logged in

$success_message = '';
$error_message = '';

// File upload directory
$uploadDir = 'uploads/'; // Ensure this directory exists and is writable

// Function to handle file uploads
function uploadFile($file, $uploadDir) {
    $fileName = basename($file["name"]);
    $targetFile = $uploadDir . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Valid file types
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

// Fetch tenant name from the database
function getTenantName($userId) {
    // Get the database connection
    $conn = get_db_connection();

    // Prepare the query to fetch the tenant name
    $query = "SELECT firstname FROM tenants WHERE id = :user_id"; // Use a prepared statement with parameter binding
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT); // Bind the user_id
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch();
    return $result['firstname'] ?? 'Unknown Tenant'; // Return 'Unknown Tenant' if no result found
}

// Send email notification to the admin using PHPMailer
function sendAdminNotification($tenantName, $applicationDetails) {
    // Create PHPMailer instance
    $mail = new PHPMailer(true); // Enable exceptions for error handling

    try {
        // Server settings (make sure to update with your email server settings)
        $mail->isSMTP(); // Use SMTP
        $mail->Host = 'smtp.gmail.com'; // SMTP server address (for Gmail)
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'jairopogirobiso@gmail.com'; // Your email address
        $mail->Password = 'wedi stuc gbbz qisl'; // Your email password (use app password for Gmail)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587; // TCP port to connect to

        // Sender and recipient details
        $mail->setFrom('jairopogirobiso@gmail.com', 'Tenant Application System'); // Your email
        $mail->addAddress('jairopogirobiso@gmail.com', 'Admin'); // Admin email (change it to the actual admin email)

        // Email subject
        $mail->Subject = "New Tenant Application Submitted";

        // Email body content (HTML format)
        $message = "
        <html>
        <head>
            <title>New Tenant Application</title>
        </head>
        <body>
            <p>A new tenant application has been submitted by <strong>$tenantName</strong>.</p>
            <p>Details:</p>
            <ul>
                <li>Letter of Intent: <a href='{$applicationDetails['letter_of_intent']}'>View</a></li>
                <li>Business Profile: <a href='{$applicationDetails['business_profile']}'>View</a></li>
                <li>Business Registration: <a href='{$applicationDetails['business_registration']}'>View</a></li>
                <li>Valid ID: <a href='{$applicationDetails['valid_id']}'>View</a></li>
                <li>BIR Registration: <a href='{$applicationDetails['bir_registration']}'>View</a></li>
                <li>Financial Statement: <a href='{$applicationDetails['financial_statement']}'>View</a></li>
            </ul>
        </body>
        </html>
        ";

        $mail->isHTML(true); // Set email format to HTML
        $mail->Body = $message; // Set the email body

        // Send the email
        $mail->send();

        return true; // Success
    } catch (Exception $e) {
        // Handle errors
        return false;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Process the files
    $letter_of_intent = uploadFile($_FILES['letter_of_intent'], $uploadDir);
    $business_profile = uploadFile($_FILES['business_profile'], $uploadDir);
    $business_registration = uploadFile($_FILES['business_registration'], $uploadDir);
    $valid_id = uploadFile($_FILES['valid_id'], $uploadDir);
    $bir_registration = uploadFile($_FILES['bir_registration'], $uploadDir);
    $financial_statement = uploadFile($_FILES['financial_statement'], $uploadDir);

    if ($letter_of_intent && $business_profile && $business_registration && $valid_id && $bir_registration && $financial_statement) {
        $tenant_name = getTenantName($_SESSION['user_id']); // Fetch tenant name based on user ID

        $conn = get_db_connection();

        // Prepare SQL query
        $query = "INSERT INTO tenant_applications 
                  (tenant_name, letter_of_intent, business_profile, business_registration, valid_id, bir_registration, financial_statement, application_sub) 
                  VALUES (:tenant_name, :letter_of_intent, :business_profile, :business_registration, :valid_id, :bir_registration, :financial_statement, NOW())";

        $stmt = $conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':tenant_name', $tenant_name);
        $stmt->bindParam(':letter_of_intent', $letter_of_intent);
        $stmt->bindParam(':business_profile', $business_profile);
        $stmt->bindParam(':business_registration', $business_registration);
        $stmt->bindParam(':valid_id', $valid_id);
        $stmt->bindParam(':bir_registration', $bir_registration);
        $stmt->bindParam(':financial_statement', $financial_statement);

        // Execute the statement
        if ($stmt->execute()) {
            // Send email notification to admin
            $applicationDetails = [
                'letter_of_intent' => $letter_of_intent,
                'business_profile' => $business_profile,
                'business_registration' => $business_registration,
                'valid_id' => $valid_id,
                'bir_registration' => $bir_registration,
                'financial_statement' => $financial_statement
            ];

            if (sendAdminNotification($tenant_name, $applicationDetails)) {
                $success_message = "Application submitted successfully and admin notified!";
            } else {
                $error_message = "Error sending notification to admin.";
            }
        } else {
            $error_message = "Error submitting your application: " . $stmt->errorInfo()[2];
        }
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
    <title>Xentro Mall Tenant Application</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9; /* Light gray background */
        }
        .header {
            background-color: #28a745; /* Green background */
            color: white;
            text-align: center;
            padding: 15px 0;
            font-size: 20px;
            font-weight: bold;
        }
        .main-content {
            width: 50%;
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff; /* White form background */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border: 2px solid #28a745; /* Green border */
        }
        h1 {
            color: #28a745; /* Green color for heading */
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px; /* Spacing between form elements */
        }
        label {
            color: #28a745;
            font-weight: bold;
            font-size: 14px;
        }
        input[type="file"], input[type="submit"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        input[type="file"] {
            background-color: #f8f8f8;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #218838; /* Darker green on hover */
        }
        .success, .error {
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        button {
    padding: 10px 20px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 14px;
    cursor: pointer;
    margin-top: 20px; /* Add some space above the button */
}

button:hover {
    background-color: #218838;
}

    </style>
</head>
<body>
    <div class="header">
        Xentro Mall Tenant Application
    </div>

    <div class="main-content">
        <h1>Submit Your Application</h1>

        <?php if ($success_message): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="application_submission.php" method="POST" enctype="multipart/form-data">
            <label for="letter_of_intent">Letter of Intent</label>
            <input type="file" name="letter_of_intent" required>

            <label for="business_profile">Business Profile</label>
            <input type="file" name="business_profile" required>

            <label for="business_registration">Business Registration</label>
            <input type="file" name="business_registration" required>

            <label for="valid_id">Valid ID (Image)</label>
            <input type="file" name="valid_id" required>

            <label for="bir_registration">BIR Registration</label>
            <input type="file" name="bir_registration" required>

            <label for="financial_statement">Financial Statement</label>
            <input type="file" name="financial_statement" required>

            <input type="submit" value="Submit Application">
        </form>

        <button onclick="history.back()">Back</button>
    </div>
</body>
</html>
