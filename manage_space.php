<?php
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

// Fetch space data from the database
$spaces_query = "SELECT id, space_name, id, status, business_type, created_at, updated_at FROM spaces ORDER BY space_name ASC";
$spaces_result = $conn->query($spaces_query);
$spaces = $spaces_result->fetch_all(MYSQLI_ASSOC);

// Handle add, edit, or delete operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_space'])) {
        $space_name = $_POST['space_name'];
        $id = $_POST['id'];
        $status = $_POST['status'];
        $business_type = $_POST['business_type'];
        $insert_query = "INSERT INTO spaces (space_name, id, status, business_type) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ssss", $space_name, $id, $status, $business_type);
        $stmt->execute();
        header("Location: manage_space.php"); // Reload page after action
    }

    if (isset($_POST['edit_space'])) {
        $space_id = $_POST['space_id'];
        $space_name = $_POST['space_name'];
        $id = $_POST['id'];
        $status = $_POST['status'];
        $business_type = $_POST['business_type'];
        $update_query = "UPDATE spaces SET space_name = ?, id = ?, status = ?, business_type = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssssi", $space_name, $id, $status, $business_type, $space_id);
        $stmt->execute();
        header("Location: manage_space.php"); // Reload page after action
    }

    if (isset($_POST['delete_space'])) {
        $space_id = $_POST['space_id'];
        $delete_query = "DELETE FROM spaces WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $space_id);
        $stmt->execute();
        header("Location: manage_space.php"); // Reload page after action
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Space</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('img/bg.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .dashboard-container {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 220px;
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 30px 20px;
            position: fixed;
            height: 100%;
            box-shadow: 4px 0 12px rgba(0, 0, 0, 0.5);
        }

        .sidebar h1 {
            font-size: 24px;
            margin-bottom: 40px;
            font-weight: bold;
            text-align: center;
            color: #ffffff;
        }

        .sidebar a {
            text-decoration: none;
            color: #fff;
            font-size: 18px;
            margin: 15px 0;
            padding: 10px;
            border-radius: 5px;
            transition: all 0.3s ease;
            display: block;
        }

        .sidebar a:hover {
            background-color: #45a049;
            transform: translateX(10px);
        }

        .sidebar a:active {
            background-color: #388e3c;
            transform: translateX(15px);
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            background-color: #e8f5e9;
            overflow-y: auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            height: 100vh;
        }

        h2 {
            color: #388e3c;
            font-size: 24px;
            margin-bottom: 20px;
        }

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
            padding: 12px;
            text-align: left;
        }

        table th {
            background-color: #388e3c;
            color: white;
            text-align: center;
        }

        table td {
            background-color: #ffffff;
        }

        button {
            background-color: #388e3c;
            color: white;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        button:active {
            background-color: #2e7d32;
            transform: scale(1);
        }

        .form-container {
            margin-bottom: 20px;
        }

        .form-container input, .form-container select {
            padding: 12px;
            width: 100%;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        /* Back Button Style */
        .back-btn {
            padding: 10px 20px;
            background-color: #388e3c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
            text-align: center;
            display: inline-block;
        }

        .back-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h1>Manage Space</h1>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="manage_space.php">Manage Space</a>
            <a href="logout.php">Logout</a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Back Button -->
            <button class="back-btn" onclick="history.back()">Back</button>

            <h2>Space List</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Space Name</th>
                        <th>Tenant ID</th>
                        <th>Status</th>
                        <th>Business Type</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($spaces as $space): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($space['id']); ?></td>
                        <td><?php echo htmlspecialchars($space['space_name']); ?></td>
                        <td><?php echo htmlspecialchars($space['id']); ?></td>
                        <td><?php echo htmlspecialchars($space['status']); ?></td>
                        <td><?php echo htmlspecialchars($space['business_type']); ?></td>
                        <td><?php echo htmlspecialchars($space['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($space['updated_at']); ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="space_id" value="<?php echo htmlspecialchars($space['id']); ?>">
                                <button type="submit" name="edit_space">Edit</button>
                                <button type="submit" name="delete_space">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Add Space Form -->
            <h2>Add New Space</h2>
            <form method="post" class="form-container">
                <input type="text" name="space_name" placeholder="Space Name" required>
                <select name="id" required>
                    <option value="">Select Tenant</option>
                    <?php
                    $tenants_query = "SELECT id, firstname, lastname FROM tenants";
                    $tenants_result = $conn->query($tenants_query);
                    while ($tenant = $tenants_result->fetch_assoc()) {
                        echo "<option value='" . $tenant['id'] . "'>" . $tenant['firstname'] . " " . $tenant['lastname'] . "</option>";
                    }
                    ?>
                </select>
                <select name="status" required>
                    <option value="Occupied">Occupied</option>
                    <option value="Available">Available</option>
                </select>
                <input type="text" name="business_type" placeholder="Business Type" required>
                <button type="submit" name="add_space">Add Space</button>
            </form>
        </div>
    </div>
</body>
</html>
