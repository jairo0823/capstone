<?php
// manage_requests.php
$conn = new mysqli("localhost", "root", "", "tesys");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Function to delete a request
function deleteRequest($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM maintenance_requests WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Function to update the request status
function updateRequestStatus($id, $status) {
    global $conn;
    $stmt = $conn->prepare("UPDATE maintenance_requests SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $stmt->close();
}

// Handle form submissions (Delete or Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        deleteRequest($id);
        $message = "Delete successfully!";
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $status = $_POST['status'];
        updateRequestStatus($id, $status);
        $message = "Update successfully!";
    }
}

// Fetch all requests
$result = $conn->query("SELECT * FROM maintenance_requests");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<button class="go-back-btn" onclick="goBack()">
    <span class="material-icons">back</span>
</button>

<script>
    // Function to go back to the previous page
    function goBack() {
        window.history.back();
    }
</script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('background.jpg'); /* Set your background image here */
            background-size: cover;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            background: rgba(255, 255, 255, 0.8); /* White background with transparency */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-danger {
            background-color:rgb(53, 220, 142);
            color: #fff;
        }
        .btn-primary {
            background-color:rgb(11, 155, 88);
            color: #fff;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Requests</h1>

        <!-- Display success message -->
        <?php if ($message): ?>
            <div class="message"><?= $message; ?></div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Tenant Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['tenant_name']); ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <select name="status">
                                <option value="Pending" <?= $row['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="Approved" <?= $row ['status'] === 'Approved' ? 'selected' : ''; ?>>Approved</option>
                                <option value="Rejected" <?= $row['status'] === 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                            </select>
                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                            <button type="submit" name="update" class="btn btn-primary">Update</button>
                        </form>
                    </td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                            <button type="submit" name="delete" class="btn btn-danger">Decline</button>
                        </form>
                    </td>
                    <td>
                        <button class="btn btn-primary" onclick="openModal(<?= $row['id']; ?>, '<?= $row['tenant_name']; ?>', '<?= $row['status']; ?>')">View Details</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for viewing request details -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Request Details</h2>
            <p id="modalTenantName">Tenant Name: </p>
            <p id="modalRequestStatus">Status: </p>
        </div>
    </div>

    <script>
        // Open the modal with request details
        function openModal(id, tenantName, status) {
            document.getElementById('modalTenantName').innerText = 'Tenant Name: ' + tenantName;
            document.getElementById('modalRequestStatus').innerText = 'Status: ' + status;
            document.getElementById('myModal').style.display = 'block';
        }

        // Close the modal
        function closeModal() {
            document.getElementById('myModal').style.display = 'none';
        }

        // Close the modal if clicked outside of it
        window.onclick = function(event) {
            if (event.target == document.getElementById('myModal')) {
                closeModal();
            }
        }
    </script>
</body>
</html>
