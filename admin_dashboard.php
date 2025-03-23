<?php
// API Endpoint for fetching tenant applications
if (isset($_GET['api']) && $_GET['api'] === 'tenant_applications') {
    header('Content-Type: application/json');
    
    // Fetch tenant applications from the database
    $applications_query = "SELECT * FROM tenant_applications";
    $applications_result = $conn->query($applications_query);
    
    $applications = [];
    while ($row = $applications_result->fetch_assoc()) {
        $applications[] = $row;
    }
    
    echo json_encode($applications);
    exit(); // Stop further execution
}

session_start(); 
include 'db_config.php';  

// Check if the user is logged in and if they are an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {     
    header("Location: login.php");     
    exit(); 
}  

// Fetch admin details for personalization 
$admin_id = $_SESSION['user_id']; 
$query = "SELECT firstname, lastname FROM admins WHERE id = ?"; 
$stmt = $conn->prepare($query); 
$stmt->bind_param("i", $admin_id); 
$stmt->execute(); 
$result = $stmt->get_result(); 
$admin = $result->fetch_assoc();  

// Fetch tenant list from the database 
$tenants_query = "SELECT id, firstname, lastname, email, phone FROM tenants ORDER BY lastname ASC"; 
$tenants_result = $conn->query($tenants_query); 
$tenants = $tenants_result->fetch_all(MYSQLI_ASSOC);  

$total_tenants_query = "SELECT COUNT(*) AS total_tenants FROM tenants"; 
$total_tenants_result = $conn->query($total_tenants_query); 
$total_tenants_count = $total_tenants_result->fetch_assoc()['total_tenants'];  

// Fetch pending maintenance requests count 
$pending_query = "SELECT COUNT(*) AS pending_count FROM maintenance_requests WHERE request_status = 'Pending'"; 
$pending_result = $conn->query($pending_query); 
$pending_count = $pending_result->fetch_assoc()['pending_count'];  

// Fetch application submission data (correct table name: tenant_application) 
$applications_query = "SELECT MONTH(submitted_at) AS month, COUNT(*) AS total_submissions 
FROM tenant_applications 
GROUP BY MONTH(submitted_at) 
ORDER BY MONTH(submitted_at)"; 
$applications_result = $conn->query($applications_query); 
$applications_data = []; 

while ($row = $applications_result->fetch_assoc()) {     
    $applications_data[] = $row; 
}  

// Preparing data for the chart 
$application_labels = array_map(function($data) { 
    return date('F', mktime(0, 0, 0, $data['month'], 10)); 
}, $applications_data); 

$application_counts = array_map(function($data) { 
    return $data['total_submissions']; 
}, $applications_data); 

$total_submissions = array_sum($application_counts);  // Total submissions for all months 


?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* General Styles */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5; /* White background */
        }

        .dashboard-container {
            display: flex;
            height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 20%;
            background-color: #4CAF50; /* Green background */
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        /* Header Section Styling */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #388E3C; /* Darker green for header */
        }

        .header h1 {
            font-size: 36px;
            font-weight: bold;
            margin: 0;
            color: #fff;
        }

        .profile-container {
            display: flex;
            align-items: center;
        }

        .profile-container .material-icons {
            font-size: 30px;
            margin-right: 10px;
        }

        .profile-name {
            font-size: 18px;
            color: #fff;
            font-weight: 400;
        }

        /* Admin Dashboard Title */
        .admin-dashboard-title {
            color: #388E3C; /* Matching the header color */
            font-size: 28px;
            font-weight: bold;
            margin: 20px 0;
        }

        /* Sidebar Links */
        .sidebar a, .dropdown-btn {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            margin-bottom: 15px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
            cursor: pointer;
            font-weight: 500;
        }

        /* Hover Effect for Sidebar Links */
        .sidebar a:hover, .dropdown-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

        /* Sidebar Dropdown */
        .dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            flex-direction: column;
            background-color: #388E3C; /* Green for dropdown */
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .dropdown:hover .dropdown-content {
            display: flex;
        }

        .dropdown-content a {
            padding: 8px;
            color: white;
            text-decoration: none;
            margin-bottom: 5px;
            border-radius: 5px;
            font-size: 14px;
        }

        .dropdown-content a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .sidebar .material-icons {
            margin-right: 10px;
            font-size: 20px;
        }

        /* Main Content Styling */
        .main-content {
            flex: 1;
            background-color: #fff; /* White background */
            padding: 20px;
            overflow-y: auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            font-size: 28px;
        }

        /* Button Styles for Links */
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
            cursor: pointer;
        }

        /* Hover Effect for Buttons */
        button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }

        table th {
            background-color: #4CAF50; /* Green header for table */
            color: white;
        }

        /* Content Display */
        .content {
            display: none;
        }

        #tenant-list {
            display: none;
        }

        /* Badge Style for Pending Requests */
        .maintenance-link {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            padding: 12px 15px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
            font-weight: 500;
        }

        .pending-badge {
            background-color: red;
            color: white;
            font-size: 14px;
            padding: 5px 10px;
            border-radius: 50%;
            font-weight: bold;
            position: absolute;
            top: -5px;
            right: -5px;
            min-width: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="header">
                <h1>TMS</h1>
                <div class="profile-container">
                    <span class="material-icons">account_circle</span>
                    <div class="profile-name">
                        <?php echo htmlspecialchars($admin['firstname']) . " " . htmlspecialchars($admin['lastname']); ?>
                    </div>
                </div>
            </div>
            <a href="admin_tracking.php"><span class="material-icons">account_circle</span>Tracking Submission Form</a>
            <div class="dropdown">
                <button class="dropdown-btn">
                    <span class="material-icons">home</span>Tenant Info &#9662;
                </button>
                <div class="dropdown-content">
                    <a href="#" onclick="showTenantList()">Tenant List</a>
                    <a href="manage_requirments.php">Manage Requirements</a>
                </div>
            </div>
            <a href="maintenance.php" class="maintenance-link">
                Maintenance
                <span class="pending-badge"><?php echo $pending_count; ?></span>
            </a>
            <div class="dropdown">
                <button class="dropdown-btn">
                    <span class="material-icons">folder_special</span> Manage Lease &#9662;
                </button>
                <div class="dropdown-content">
                    <a href="admin_maintenance_request.php">Manage Requests</a>
                    <a href="manage_space.php"><span class="material-icons">home</span>Manage Space</a>
                    <a href="update_lease_status.php">Lease Status</a>
                    <a href="manage_payment.php">Manage Payment</a>
                </div>
            </div>
            <a href="send_announcement.php"><span class="material-icons">announcement</span>Send Announcement</a>
            <a href="admin_renewal.php"><span class="material-icons">update</span>Process Renewals</a>
            <a href="chat.php"><span class="material-icons">chat</span>Messaging</a>
            <a href="admin_register.php"><span class="material-icons">person_add</span>Registration</a>
            <a href="settings.php"><span class="material-icons">settings</span>Settings</a>
            <a href="logout.php"><span class="material-icons">logout</span>Logout</a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="admin-dashboard-title">
                Admin Dashboard
            </div>

            <div id="tenant-list" class="content">
                <h2>Tenant List</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tenants as $tenant): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($tenant['id']); ?></td>
                            <td><?php echo htmlspecialchars($tenant['firstname']); ?></td>
                            <td><?php echo htmlspecialchars($tenant['lastname']); ?></td>
                            <td><?php echo htmlspecialchars($tenant['email']); ?></td>
                            <td><?php echo htmlspecialchars($tenant['phone']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Chart Section -->
            <div id="chart-container" class="content">
                <h2>Dashboard Statistics</h2> <canvas id="myChart" width="300" height="150"></canvas>
                <h2>Application Submissions</h2>
                <canvas id="applicationChart" width="300" height="150"></canvas>
                <h2>Total Tenants</h2>
                <canvas id="totalTenantsChart" width="300" height="150"></canvas>
                <h2>Process Renewals</h2>
                <canvas id="renewalsChart" width="300" height="150"></canvas>
                <p id="total-applications" style="font-size: 18px; font-weight: bold;">Total Applications Submitted: <?php echo $total_submissions; ?></p>
            </div>
        </div>
    </div>

    <script>
        function showTenantList() {
            document.querySelectorAll('.content').forEach(content => content.style.display = 'none');
            document.getElementById('tenant-list').style.display = 'block';
        }

        window.onload = function() {
            document.getElementById('chart-container').style.display = 'block';

            // Maintenance Requests Chart (Example data, can be customized later)
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May'], // Example months
                    datasets: [{
                        label: 'Maintenance Requests',
                        data: [12, 19, 3, 5, 2], // Example data
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Other charts and content as usual...
        }
    </script>
</body>
</html>
