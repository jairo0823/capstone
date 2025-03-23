<?php
require "emailLibrary/EmailLibrary.php";  // Ensure this is the correct path for PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection
$conn = new mysqli('localhost', 'root', '', 'tesys');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get the count of pending maintenance requests
$sql_count = "SELECT COUNT(*) AS pending_count FROM maintenance_requests WHERE request_status = 'Pending'";
$result_count = $conn->query($sql_count);

// Check if query succeeded
if ($result_count) {
    $pending_requests = $result_count->fetch_assoc();
    $pending_count = $pending_requests['pending_count'];
} else {
    echo "Error: " . $conn->error;  // To check if there's an error in the query
    $pending_count = 0;  // Default to 0 if there's an error
}

// Handle form submission
if (isset($_POST['submit'])) {
    $request_id = $_POST['request_id'];
    $action_type = $_POST['action_type']; // Approve or Decline
    $feedback = $_POST['feedback'];

    // Update maintenance request status and add feedback
    $sql = "UPDATE maintenance_requests SET request_status = ?, feedback = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $action_type, $feedback, $request_id);
    $stmt->execute();

    // Fetch tenant's email from the tenants table based on tenant_name in maintenance_requests
    $sql = "SELECT tenants.email FROM maintenance_requests
            INNER JOIN tenants ON maintenance_requests.tenant_name = tenants.id
            WHERE maintenance_requests.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $tenants = $result->fetch_assoc();

    if ($tenants) {
        // Ensure tenant email is not empty
        $tenantEmail = $tenants['email'];
        if (empty($tenantEmail)) {
            echo "Error: Tenant email not found.";
        } else {
            // Send email with feedback to the tenant
            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'jairopogirobiso@gmail.com'; // Your Gmail address
                $mail->Password = 'wedi stuc gbbz qisl'; // Your Gmail app password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('jairopogirobiso@gmail.com', 'Mailer');
                $mail->addAddress($tenantEmail); // Tenant's email fetched from the database

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Maintenance Request Update: ' . $action_type;
                $mail->Body    = "Dear Tenant,<br><br>Your maintenance request has been {$action_type}.<br><br>Feedback: {$feedback}<br><br>Thank you.";

                // Send the email
                if ($mail->send()) {
                    echo 'Email sent successfully';
                } else {
                    echo 'Email failed to send.';
                }
            } catch (Exception $e) {
                echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }

    // Redirect after processing
    header('Location: maintenance.php');
    exit;
    
}

// Fetch maintenance requests
$sql = "SELECT * FROM maintenance_requests ORDER BY date_requested DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin: Maintenance Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('img/bg.jpg'); /* Replace with your image path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #333;
        }
        .container {
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .status.Pending {
            color: orange;
        }
        .status.In-Progress {
            color: blue;
        }
        .status.Completed {
            color: green;
        }
        img.thumbnail {
            max-width: 100px;
            height: auto;
            margin: 5px;
            cursor: pointer;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 30px;
            width: 60%;
            max-width: 700px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .modal-header h2 {
            font-size: 24px;
        }
        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: black;
        }
        .modal-footer {
            text-align: right;
            margin-top: 20px;
            border-top: 2px solid #ddd;
            padding-top: 10px;
        }
        textarea,
        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        textarea {
            resize: vertical;
        }
        button {
            padding: 10px 20px;
            background-color:rgb(10, 159, 80);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button.save {
            background-color: #28a745;
        }
        button.cancel {
            background-color: #dc3545;
        }
        .notification-badge {
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 5px 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <button class="go-back-btn" onclick="goBack()">
        <span class="material-icons">Back</span>
    </button>

    <script>
        // Function to go back to the previous page
        function goBack() {
            window.history.back();
        }
    </script>

    <div class="container">
        <h1>Maintenance Requests Dashboard</h1>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2>Admin Dashboard</h2>
            <div class="notification">
                <span class="notification-badge"><?= $pending_count ?></span> Pending Requests
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Tenant</th>
                    <th>Unit</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Urgency</th>
                    <th>Photos</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['tenant_name']) ?></td>
                            <td><?= htmlspecialchars($row['unit_number']) ?></td>
                            <td><?= htmlspecialchars($row['request_description']) ?></td>
                            <td><?= htmlspecialchars($row['category']) ?></td>
                            <td><?= htmlspecialchars($row['urgency']) ?></td>
                            <td>
                                <?php
                                $photos = explode(',', $row['photos']);
                                foreach ($photos as $photo) {
                                    $photo = trim($photo);
                                    $photoPath = strpos($photo, 'uploads/') === false ? 'uploads/' . $photo : $photo;
                                    if (file_exists($photoPath)) {
                                        echo '<a href="#" class="photo-link" data-photo="' . $photoPath . '">';
                                        echo '<img src="' . $photoPath . '" alt="Photo" class="thumbnail">';
                                        echo '</a>';
                                    } else {
                                        echo '<p>Image not found: ' . $photoPath . '</p>';
                                    }
                                }
                                ?>
                            </td>
                            <td class="status <?= str_replace(' ', '-', $row['request_status']) ?>"><?= $row['request_status'] ?></td>
                            <td><?= date('F j, Y, g:i a', strtotime($row['date_requested'])) ?></td>
                            <td>
                                <button class="action-btn" data-id="<?= $row['id'] ?>" data-tenant="<?= htmlspecialchars($row['tenant_name']) ?>">Action</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align:center;">No requests found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for actions -->
    <div id="actionModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Request Actions</h2>
                <span class="close">&times;</span>
            </div>
            <form id="actionForm" method="POST">
                <input type="hidden" name="request_id" id="requestId">
                <p><strong>Tenant:</strong> <span id="tenantName"></span></p>
                <label for="actionType">Select Action:</label>
                <select name="action_type" id="actionType">
                    <option value="Approve">Approve</option>
                    <option value="Decline">Decline</option>
                </select>
                <label for="feedback">Feedback:</label>
                <textarea name="feedback" id="feedback" placeholder="Provide feedback here..."></textarea>
                <div class="modal-footer">
                    <button type="submit" class="save" name="submit">Submit</button>
                    <button type="button" class="cancel">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('actionModal');
        const closeBtn = modal.querySelector('.close');
        const cancelBtn = modal.querySelector('.cancel');
        const actionBtns = document.querySelectorAll('.action-btn');
        const tenantName = document.getElementById('tenantName');
        const requestId = document.getElementById('requestId');

        // Open the modal when the "Take Action" button is clicked
        actionBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                requestId.value = this.getAttribute('data-id');
                tenantName.textContent = this.getAttribute('data-tenant'); // Display tenant's name, not the ID
                modal.style.display = 'block';
            });
        });

        // Close the modal when the close button or cancel button is clicked
        closeBtn.onclick = () => { modal.style.display = 'none'; };
        cancelBtn.onclick = () => { modal.style.display = 'none'; };

        // Close modal when clicked outside
        window.onclick = (event) => {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        };
    </script>
</body>
</html>
