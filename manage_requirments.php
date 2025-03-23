<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['admin_logged_in'])) {
    // Redirect to login page
}

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

// Handle Approve/Decline actions
if (isset($_GET['action']) && isset($_GET['application_id'])) {
    $application_id = $_GET['application_id'];
    $action = $_GET['action'];
    $status = ($action == 'approve') ? 'Approved' : 'Declined';
    
    // Update the status in the database
    $updateStatusSql = "UPDATE tenant_applications SET status='$status' WHERE application_id=$application_id";
    if ($conn->query($updateStatusSql) === TRUE) {
        echo "<script>alert('Application $status successfully.'); window.location.href='manage_requirments.php';</script>";
        $conn->close(); // Close the database connection here to avoid closing it again later

    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Fetch all applications
$sql = "SELECT * FROM tenant_applications ORDER BY application_sub DESC";
$result = $conn->query($sql);

// // Fetch all maintenance requests
// $maintenanceSql = "SELECT * FROM maintenance_requests ORDER BY date_from DESC";
// $maintenanceResult = $conn->query($maintenanceSql);
// ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tenant Applications and Maintenance Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            text-align: center;
        }

        h1 {
            margin: 0;
            font-size: 24px;
        }

        .container {
            padding: 20px;
            max-width: 1200px;
            margin: auto;
        }

            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .action-buttons {
            position: relative;
        }

        .thumbnail {
            width: 100px;
            height: 100px;
            object-fit: cover;
            cursor: pointer;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
        }

        .modal-content {
            margin: 5% auto;
            display: block;
            width: 80%;
            max-width: 700px;
            border: 2px solid white;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
        }

        .close {
            color: white;
            position: absolute;
            top: 10px;
            right: 25px;
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
        }

        .no-applications {
            text-align: center;
            font-size: 18px;
            color: #555;
            margin-top: 50px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .thumbnail {
                width: 75px;
                height: 75px;
            }

            th, td {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<header>
<!-- Home Button -->
<button class="home-btn" onclick="goHome()">
    <span class="material-icons">Back</span>
</button>

<script>
    // Function to go to the home page
    function goHome() {
        window.location.href = 'admin_dashboard.php'; // Change to your home page URL
    }
</script>
    <h1>Manage Tenant Applications</h1>
</header>

<div class="container mt-3">
    <h2>Application List</h2>
    
    <?php if ($result->num_rows > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Tenant Name</th>
                    <th>Letter of Intent</th>
                    <th>Business Profile</th>
                    <th>Business Registration</th>
                    <th>Valid ID</th>
                    <th>BIR Registration</th>
                    <th>Financial Statement</th>
                    <th>Application Date</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['tenant_name']) ?></td>
                        <td><img src="<?= htmlspecialchars($row['letter_of_intent']) ?>" class="thumbnail" alt="Letter of Intent" onclick='openModal("<?= htmlspecialchars($row['letter_of_intent_path']) ?>")'></td>
                        <td><img src="<?= htmlspecialchars($row['business_profile']) ?>" class="thumbnail" alt="Business Profile" onclick='openModal("<?= htmlspecialchars($row['business_profile_path']) ?>")'></td>
                        <td><img src="<?= htmlspecialchars($row['business_registration']) ?>" class="thumbnail" alt="Business Registration" onclick='openModal("<?= htmlspecialchars($row['business_registration_path']) ?>")'></td>
                        <td><img src="<?= htmlspecialchars($row['valid_id']) ?>" class="thumbnail" alt="Valid ID" onclick='openModal("<?= htmlspecialchars($row['valid_id_path']) ?>")'></td>
                        <td><img src="<?= htmlspecialchars($row['bir_registration']) ?>" class="thumbnail" alt="BIR Registration" onclick='openModal("<?= htmlspecialchars($row['bir_registration_path']) ?>")'></td>
                        <td><img src="<?= htmlspecialchars($row['financial_statement']) ?>" class="thumbnail" alt="Financial Statement" onclick='openModal("<?= htmlspecialchars($row['financial_statement_path']) ?>")'></td>
                        <td><?= htmlspecialchars($row['application_sub']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= htmlspecialchars($row['remarks']) ?></td>
                        <td class='action-buttons'>
                            <a href='appdecapplication.php?application_id=<?= htmlspecialchars($row['application_id']) ?>' class="btn btn-sm btn-success">Update</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class='no-applications'>No applications submitted yet.</div>
    <?php endif; ?>

</div>

<script>
function openModal(imagePath) {
    // Implement your modal opening logic here
    alert("Open modal for: " + imagePath);
}
</script>

</body>
</html>

<?php 
$conn->close(); // Close the database connection at the end of the script


?>

<!-- Modal -->
<div id="photoModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImg">
</div>

<script>
    // Open the modal with the clicked image
    function openModal(photoPath) {
        document.getElementById('photoModal').style.display = "block";
        document.getElementById('modalImg').src = photoPath;
    }

    // Close the modal
    function closeModal() {
        document.getElementById('photoModal').style.display = "none";
    }
</script>

</body>
</html>
<style> 
.home-btn {
    display: flex;
    align-items: center;
    background-color: #00796b;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    font-size: 20px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.home-btn:hover {
    background-color: #004d40;
}

.home-btn .material-icons {
    font-size: 24px; /* Adjust the size of the home icon */
}
</style>
</body>
</html>

<?php $conn->close(); ?>