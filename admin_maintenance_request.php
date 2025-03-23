<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the form submission
    $admin_name = $_POST['admin_name'];
    $maintenance_details = $_POST['maintenance_details'];
    $date_from = $_POST['date_from'];
    $date_to = $_POST['date_to'];
    $time_from = $_POST['time_from'];
    $time_to = $_POST['time_to'];
    $tenant_email = $_POST['tenant_email']; // Email to send the completed form

    // Database configuration
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tesys"; // Replace with your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert maintenance request into the database
    $insertSql = "INSERT INTO maintenance_requests (admin_name, maintenance_details, date_from, date_to, time_from, time_to, tenant_email) VALUES ('$admin_name', '$maintenance_details', '$date_from', '$date_to', '$time_from', '$time_to', '$tenant_email')";

    if ($conn->query($insertSql) === TRUE) {
        // Prepare email details
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your_email@gmail.com'; // Your Gmail address
            $mail->Password = 'your_password'; // Your Gmail password or App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('your_email@gmail.com', 'Admin');
            $mail->addAddress($tenant_email); // Send to tenant's email

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Maintenance Request Confirmation';
            $mail->Body    = "Admin Name: $admin_name<br>Maintenance Details: $maintenance_details<br>Permit Valid From: $date_from<br>Time From: $time_from";

            // Send the email
            $mail->send();
            echo 'Email sent successfully';
        } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        // Redirect or display a success message
        header('Location: manage_requirments.php'); 
        exit; // Ensure no further code is executed after redirection
    } else {
        echo "Error: " . $insertSql . "<br>" . $conn->error;
    }

    $conn->close(); // Close the database connection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Maintenance Request Form</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; }
        .container { width: 70%; margin: auto; border: 2px solid #28a745; padding: 20px; background: white; border-radius: 10px; }
        .header { text-align: center; font-size: 24px; font-weight: bold; color: #28a745; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #28a745; padding: 8px; text-align: left; }
        input, textarea { width: 100%; padding: 5px; border: 1px solid #28a745; border-radius: 5px; }
        button { background-color: #28a745; color: white; border: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Admin Maintenance Request Form</div>
        <form method="POST">
            <table>
                <tr>
                    <th>Admin Name</th>
                    <td><input type="text" name="admin_name" required></td>
                </tr>
                <tr>
                    <th>Maintenance Details</th>
                    <td><textarea name="maintenance_details" required></textarea></td>
                </tr>
                <tr>
                    <th>Permit Valid From</th>
                    <td>From <input type="date" name="date_from" required></td>
                    <th>To</th>
                    <td><input type="date" name="date_to" required></td>
                </tr>
                <tr>
                    <th>Time From</th>
                    <td>From <input type="time" name="time_from" required></td>
                    <th>To</th>
                    <td><input type="time" name="time_to" required></td>
                </tr>
                <tr>
                    <th>Tenant Email</th>
                    <td><input type="email" name="tenant_email" required></td>
                </tr>
            </table>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
