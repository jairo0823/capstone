<?php
require_once 'db_config.php'; // Database connection

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query to fetch all tenants
$sql = "SELECT id, username FROM tenants";
if (!empty($search)) {
    $sql .= " WHERE username LIKE '%" . $conn->real_escape_string($search) . "%'";
}

$result = $conn->query($sql);

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM tenants WHERE id = " . (int)$delete_id;
    
    if ($conn->query($delete_sql) === TRUE) {
        echo "Record deleted successfully.";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    
    // Redirect back to the page after deletion
    header("Location: admin_tenant_check.php");
    exit();
}

// Handle clear search
if (isset($_GET['clear_search'])) {
    // Redirect to the same page without search
    header("Location: admin_tenant_check.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Tenants</title>
    <style>
        /* General Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4; /* Light gray background */
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            text-align: center;
            padding: 20px;
            background-color: #388E3C; /* Green background */
            color: #fff;
            margin-bottom: 30px;
        }

        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background-color: #ffffff; /* White background for the content */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 10px;
            width: 70%;
            border: 2px solid #388E3C; /* Green border */
            border-radius: 5px;
            background-color: #e8f5e9; /* Light green background */
            color: #333;
            font-size: 16px;
        }

        button {
            padding: 10px 20px;
            background-color: #388E3C; /* Green background */
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #2c6d33; /* Darker green on hover */
        }

        .clear-btn {
            background-color: #f39c12; /* Orange background */
            color: #fff;
        }

        .clear-btn:hover {
            background-color: #e67e22; /* Darker orange on hover */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #388E3C; /* Green header */
            color: #fff;
        }

        td {
            background-color: #f1f8e9; /* Light green row background */
        }

        .delete-btn {
            color: #e74c3c;
            cursor: pointer;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .delete-btn:hover {
            color: #c0392b;
        }

        /* Back Button */
        .back-btn {
            padding: 10px 20px;
            background-color: #2c3e50; /* Dark gray background */
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
            text-align: center;
            display: inline-block;
        }

        .back-btn:hover {
            background-color: #34495e; /* Darker gray on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Back Button -->
        <button class="back-btn" onclick="history.back()">Back</button>

        <h1>Registered Tenants</h1>
        <div class="form-container">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search by username" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Search</button>
            </form>
            <!-- Clear Search Button -->
            <form method="GET" action="">
                <button type="submit" name="clear_search" class="clear-btn">Clear Search</button>
            </form>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td>No tenants found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
