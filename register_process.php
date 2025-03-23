<?php
include 'db_config.php'; // Include database connection

// Get form data
$user_role = $_POST['user_role'];
$username = $_POST['username'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$password = $_POST['password']; // Get plain password
$confirm_password = $_POST['confirm_password'];

// Check if passwords match
if ($password !== $confirm_password) {
    die("Passwords do not match.");
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare SQL query based on user role
$table = ($user_role === 'tenants') ? 'tenants' : 'admins';

// Check if email or username already exists
$query = "SELECT * FROM $table WHERE email = ? OR username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $email, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    die("Email or Username is already registered.");
}

// Insert user data into the corresponding table
$query = "INSERT INTO $table (username, firstname, lastname, email, phone, password) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssssss", $username, $firstname, $lastname, $email, $phone, $hashed_password);

if ($stmt->execute()) {
    echo "Registration successful!";
    // Optionally, debug: print the last inserted ID
    echo "User ID: " . $stmt->insert_id;
    header("Location: login.php"); // Redirect to login page
} else {
    echo "Error: " . $stmt->error;
}
?>
