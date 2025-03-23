<?php 
require "emailLibrary/EmailLibrary.php"; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
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

try { 
    $pdo = new PDO($dsn, $username, $password, $options); 
} catch (PDOException $e) { 
    echo $e; 
}

// Handle form submission
if (isset($_POST['submit'])) { 
    $application_id = $_POST['application_id']; 
    $status = $_POST['status']; 
    $remarks = $_POST['remarks']; 

    // Update the database
    $sql = 'UPDATE tenant_applications SET status = :status, remarks = :remarks WHERE application_id = :application_id'; 
    $stmt = $pdo->prepare($sql); 
    $data = [ 
        'application_id' => $application_id, 
        'status' => $status, 
        'remarks' => $remarks 
    ]; 
    $stmt->execute($data); 
    echo 'Data Updated Successfully'; 

    // Prepare email details
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'jairopogirobiso@gmail.com'; // Your Gmail address
        $mail->Password = 'wedi stuc gbbz qisl'; // Your Gmail password or App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('jairopogirobiso@gmail.com', 'Mailer');
        $mail->addAddress('jaigalac@gmail.com'); // Change this to the actual recipient's email address

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Application Status Update';
        $mail->Body    = "Application ID: $application_id<br>Status: $status<br>Remarks: $remarks";

        // Send the email
        $mail->send();
        echo 'Email sent successfully';
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    // Redirect after processing
    header('Location: manage_requirments.php'); 
    exit; // Ensure no further code is executed after redirection
} 
?>

<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Form Submission</title> 
    <style> 
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
        } 
        .form-container { 
            max-width: 400px; 
            margin: auto; 
            padding: 20px; 
            border: 1px solid #ccc; 
            border-radius: 5px; 
            background-color: #f9f9f9; 
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); 
        } 
        .form-label { 
            display: block; 
            margin-bottom: 8px; 
            font-weight: bold; 
        } 
        .form-select, 
        input[type="text"] { 
            width: 100%; 
            padding: 8px; 
            margin-bottom: 15px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
        } 
        input[type="submit"] { 
            background-color: #4CAF50; 
            color: white; 
            padding: 10px 15px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
        } 
        input[type="submit"]:hover { 
            background-color: #45a049; 
        } 
    </style> 
</head> 
<body> 

<!-- Home Button -->
<button class="home-btn" onclick="goHome()">
    <span class="material-icons">back</span>
</button>

<script>
    // Function to go to the home page
    function goHome() {
        window.location.href = 'admin_dashboard.php'; // Change to your home page URL
    }
</script>

<div class="form-container"> 
    <form action="appdecapplication.php" method="post"> 
        <div class="mb-3"> 
            <label for="id" class="form-label">ID</label> 
            <input type="text" name="application_id" id="id" value="<?= htmlspecialchars($_GET['application_id']) ?>" readonly> 
        </div> 
        <div class="mb-3"> 
            <label for="status" class="form-label">Status</label> 
            <select class="form-select" name="status" id="status" required> 
                <option selected disabled>Select one</option> 
                <option value="approved">Approved</option> 
                <option value="declined">Declined</option> 
            </select> 
        </div> 
        <div class="mb-3"> 
            <label for="remarks" class="form-label">Remarks</label> 
            <input type="text" name="remarks" id="remarks" placeholder="Enter your remarks here" required> 
        </div> 
        <input type="submit" value="Submit" name="submit"> 
    </form> 
</div> 
</body> 
</html>
