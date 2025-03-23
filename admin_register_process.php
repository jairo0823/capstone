

<?php
session_start();
include 'db_config.php'; // Include your database connection script

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: admin_register.php");
        exit();
    }

    // Check if username already exists
    $check_query = "SELECT * FROM admins WHERE username = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Username already exists. Please choose another.";
        header("Location: admin_register.php");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert admin into the database
    $insert_query = "INSERT INTO admins (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Admin registered successfully!";
        header("Location: admin_register.php");
        exit();
    } else {
        $_SESSION['error'] = "Error: " . $stmt->error;
        header("Location: admin_register.php");
        exit();
    }
} else {
    header("Location: admin_register.php");
    exit();
}
?>
