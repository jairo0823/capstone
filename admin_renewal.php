<?php
session_start();
include 'db_config.php'; // Make sure this file contains your database connection details

// Check if the user is an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php'); // Redirect to login if not an admin
    exit();
}

// Handle form submissions for approving, declining, or editing requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['approve'])) {
        $request_id = $_POST['request_id'];
        $status = 'Approved';
        $stmt = $conn->prepare("UPDATE lease_renewal_requests SET status = ? WHERE request_id = ?");
        $stmt->bind_param("si", $status, $request_id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['decline'])) {
        $request_id = $_POST['request_id'];
        // Delete the request instead of updating the status
        $stmt = $conn->prepare("DELETE FROM lease_renewal_requests WHERE request_id = ?");
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['edit'])) {
        $request_id = $_POST['request_id'];
        $new_date = $_POST['new_date'];
        $stmt = $conn->prepare("UPDATE lease_renewal_requests SET renewal_date = ? WHERE request_id = ?");
        $stmt->bind_param("si", $new_date, $request_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch renewal requests from the database along with tenant details
$query = "SELECT r.request_id, r.tenant_id, r.renewal_date, r.status, t.firstname, t.lastname, t.email 
          FROM lease_renewal_requests r 
          JOIN tenants t ON r.tenant_id = t.id"; // Adjust the table name as necessary
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lease Renewal Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('img/bg.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            height: 100vh;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .action-buttons form {
            display: inline;
        }
        input[type="date"] {
            padding: 5px;
            margin-right: 5px;
        }
        .edit-button {
            background-color: #28a745; /* Green */
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .edit-button:hover {
            background-color: #218838; /* Darker green */
        }
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .back-btn {
            position: absolute;
            left: 10px;
            bottom: 10px;
            padding: 10px;
            background-color:rgb(16, 183, 66);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .back-btn:hover {
            background-color:rgb(7, 95, 39);
        }
    </style>
    <script>
        function openModal(requestId, currentDate) {
            document.getElementById('modal').style.display = "block";
            document.getElementById('request_id').value = requestId;
            document.getElementById('new_date').value = currentDate;
        }

        function closeModal() {
            document.getElementById('modal').style.display = "none";
        }
    </script>
</head>
<body>

<div class="container">
    <h1>Lease Renewal Requests</h1>
    <table>
        <thead>
            <tr>
                <th>Request ID</th>
                <th>Tenant ID</th>
                <th>Tenant Name</th>
                <th>Email</th>
                <th>Desired Renewal Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['request_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['tenant_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['renewal_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td class='action-buttons'>";
                    echo "<form method='post' action=''>";
                    echo "<input type='hidden' name='request_id' value='" . htmlspecialchars($row['request_id']) . "'>";
                    echo "<button type='submit' name='approve' class='edit-button'>Approve</button>";
                    echo "<button type='submit' name='decline' class='edit-button'>Decline</button>";
                    echo "<button type='button' class='edit-button' onclick=\"openModal('" . htmlspecialchars($row['request_id']) . "', '" . htmlspecialchars($row['renewal_date']) . "')\">Edit</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No requests found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal for editing renewal date -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Edit Renewal Date</h2>
        <form method="post" action="">
            <input type="hidden" id="request_id" name="request_id">
            <label for="new_date">New Renewal Date:</label>
            <input type="date" id="new_date" name="new_date" required>
            <button type="submit" name="edit" class="edit-button">Save Changes</button>
        </form>
    </div>
</div>

<!-- Back button at the bottom-left -->
<a href="admin_dashboard.php">
    <button class="back-btn">Back</button>
</a>

</body>
</html>
