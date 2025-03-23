<?php
session_start();

// Database connection function
function get_db_connection() {
    include 'db_config.php';
    return $conn;
}

// Check if user is logged in and is a tenant
function check_login_status() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'tenant') {
        header("Location: login.php"); // Redirect to login if not a tenant
        exit();
    }
}

// Fetch tenant data from the database
function get_tenant_data($tenant_id) {
    $conn = get_db_connection();
    $query = "SELECT firstname, lastname, email, phone FROM tenants WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $tenant_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

check_login_status(); // Ensure user is logged in and a tenant

$id = $_SESSION['user_id'];
$tenant = get_tenant_data($id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Dashboard</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #81c784, #388e3c);
        }
        .dashboard-container {
            display: flex;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }
        .sidebar {
            width: 280px;
            background-color: #2e7d32;
            padding: 30px 20px;
            color: #fff;
            box-shadow: 4px 0 12px rgba(0, 0, 0, 0.1);
            text-align: left;
            border-radius: 0 10px 10px 0;
        }
        .sidebar .profile {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .sidebar img {
            width: 120px; /* Adjusted logo size */
            height: 120px; /* Adjusted logo size */
            border-radius: 50%;
            margin-right: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        .sidebar .profile h2 {
            font-size: 20px;
            font-weight: 500;
        }
        .sidebar button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            background-color: #388e3c;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            justify-content: start;
        }
        .sidebar button:hover {
            background-color: #2e7d32;
        }
        .sidebar button .material-icons {
            font-size: 20px;
            margin-right: 10px;
        }
        .sidebar .logout {
            background-color: #d32f2f;
        }
        .sidebar .logout:hover {
            background-color: #b71c1c;
        }
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: #fafafa;
            padding: 20px;
            overflow-y: auto;
        }
        .top-bar {
            display: flex;
            justify-content: flex-end;
            padding: 20px;
            background-color: #e8f5e9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .top-bar button {
            display: flex;
            align-items: center;
            padding: 10px 18px;
            background-color: #388e3c;
            color: white;
            border: none;
            border-radius: 50px;
            margin-left: 12px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .top-bar button:hover {
            background-color: #2e7d32;
        }
        .top-bar button .material-icons {
            font-size: 20px;
            margin-right: 8px;
        }
        .main-content .content {
            padding: 30px;
        }
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            width: 450px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .modal-header {
            font-size: 24px;
            margin-bottom: 20px;
            color: #388e3c;
        }
        .modal input {
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }
        .modal button {
            padding: 12px 30px;
            background-color: #388e3c;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .modal button:hover {
            background-color: #2e7d32;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <!-- Profile Section -->
            <div class="profile">
                <img src="img/logo.jpg" alt="profile">
                <h2><?php echo htmlspecialchars($tenant['firstname'] . ' ' . $tenant['lastname']); ?></h2>
            </div>
            <button onclick="location.href='application_submission.php'">
                <span class="material-icons">assignment</span>Application Submission
            </button>
            <button onclick="location.href='workpermitform.php'">
                <span class="material-icons">build</span>Maintenance Requests
            </button>
            <button onclick="location.href='Renewal Request Page.php'">
                <span class="material-icons">autorenew</span>Renewal
            </button>
            <button onclick="location.href='payment.php'">
                <span class="material-icons">payment</span>Payment
            </button>
          
            <button class="logout" onclick="location.href='logout.php'">
                <span class="material-icons">exit_to_app</span>Logout
            </button>
        </div>
        <div class="main-content">
            <!-- Top Bar for Notification, Settings, and Logout -->
            <div class="top-bar">
                <button onclick="location.href='notifications.php'">
                    <span class="material-icons">notifications</span>Notification
                </button>
                <button onclick="openModal()">
                    <span class="material-icons">settings</span>Settings
                </button>
                <button onclick="location.href='logout.php'">
                    <span class="material-icons">exit_to_app</span>Logout
                </button>
            </div>

            <!-- Main Content -->
            <div class="content">
                <!-- Removed Welcome message and description -->
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="settingsModal">
        <div class="modal-content">
            <h2 class="modal-header">Account Settings</h2>
            <h3>Profile</h3>
            <p>Name: <?php echo htmlspecialchars($tenant['firstname'] . ' ' . $tenant['lastname']); ?></p>
            <p>Email: <?php echo htmlspecialchars($tenant['email']); ?></p>
            <p>Phone: <?php echo htmlspecialchars($tenant['phone']); ?></p>

            <h3>Change Password</h3>
            <form action="change_password.php" method="POST">
                <input type="password" name="current_password" placeholder="Current Password" required>
                <input type="password" name="new_password" placeholder="New Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit">Update Password</button>
            </form>
            <button onclick="closeModal()">Close</button>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('settingsModal').style.display = 'flex';
        }
        function closeModal() {
            document.getElementById('settingsModal').style.display = 'none';
        }
    </script>
</body>
</html>
