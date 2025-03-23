<?php
session_start();

// Database connection function
function get_db_connection() {
    include 'db_config.php'; // Your DB connection details here
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
    return $result->fetch_assoc(); // Return tenant data
}

// Fetch payment data (if needed)
function get_payments($tenant_id) {
    $conn = get_db_connection();
    $query = "SELECT * FROM payments WHERE tenant_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $tenant_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC); // Return all payments for the tenant
}

// Fetch maintenance requests (if needed)
function get_maintenance_requests($tenant_id) {
    $conn = get_db_connection();
    $query = "SELECT * FROM maintenance_requests WHERE tenant_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $tenant_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC); // Return all maintenance requests for the tenant
}
?>
