<?php
session_start();
include 'db_config.php'; // Include database connection

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data and sanitize
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check in tenants table first
    $query = "SELECT * FROM tenants WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username); // Bind username parameter
    $stmt->execute();
    $result = $stmt->get_result();

    // If user is found in tenants table
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);

            // Store user info in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = 'tenant';
            $_SESSION['tenant_firstname'] = $user['firstname'];

            // Redirect to tenant dashboard
            header("Location: tenant_dashboard.php");
            exit();
        } else {
            die("Incorrect password.");
        }
    }

    // Check in admins table if not found in tenants
    $query = "SELECT * FROM admins WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // If user is found in admins table
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);

            // Store user info in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = 'admin';

            // Redirect to admin dashboard
            header("Location: admin_dashboard.php");
            exit();
        } else {
            die("Incorrect password.");
        }
    }

    // If not found in either table
    die("User not found.");
} else {
    // If form isn't submitted, redirect to login page
    header("Location: login.php");
    exit();
}
?>
