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
$query = "SELECT firstname, lastname, email, phone FROM admins WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Open+Sans:400,600,700" rel="stylesheet">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            margin: 0;
            overflow-x: hidden;
            height: 100%;
            background-image: url('img/bg.jpg'); /* Replace with your image */
            background-size: cover;
            background-attachment: fixed;
            animation: backgroundAnimation 10s ease-in-out infinite;
        }

        header {
            background-color: rgba(51, 51, 51, 0.8);
            color: white;
            padding: 15px 30px;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .main-content {
            padding: 20px;
            max-width: 1000px;
            margin: 20px auto;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 3;
        }

        h1 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 24px;
        }

        .settings-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .settings-form input,
        .settings-form button {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .settings-form button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }

        .settings-form button:hover {
            background-color: #45a049;
        }

        button {
            padding: 10px;
            font-size: 18px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #45a049;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        @keyframes backgroundAnimation {
            0% { background-position: 0 0; }
            50% { background-position: 100% 0; }
            100% { background-position: 0 0; }
        }

        @media screen and (max-width: 768px) {
            .main-content {
                padding: 15px;
            }

            .settings-form input,
            .settings-form button {
                font-size: 14px;
                padding: 8px;
            }

            button {
                font-size: 16px;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Settings</h1>
    </div>

    <div class="main-content">
        <h2>Account Profile</h2>
        <p><strong>First Name:</strong> <?php echo htmlspecialchars($admin['firstname']); ?></p>
        <p><strong>Last Name:</strong> <?php echo htmlspecialchars($admin['lastname']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($admin['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($admin['phone']); ?></p>
        <button onclick="document.getElementById('update-profile-modal').style.display='block'">Update Profile</button>
        <button onclick="document.getElementById('change-password-modal').style.display='block'">Change Password</button>
        <button onclick="document.getElementById('change-email-modal').style.display='block'">Change Email</button>
        <button onclick="window.location.href='logout.php'">Logout</button>
    </div>

    <div id="update-profile-modal" class="modal">
        <div class="modal-content">
            <h2>Update Profile</h2>
            <form class="settings-form" action="update_profile.php" method="POST">
                <input type="text" name="firstname" placeholder="First Name" value="<?php echo htmlspecialchars($admin['firstname']); ?>" required>
                <input type="text" name="lastname" placeholder="Last Name" value="<?php echo htmlspecialchars($admin['lastname']); ?>" required>
                <input type="text" name="phone" placeholder="Phone" value="<?php echo htmlspecialchars($admin['phone']); ?>" required>
                <button type="submit">Update Profile</button>
            </form>
 
            <button onclick="document.getElementById('update-profile-modal').style.display='none'">Close</button>
        </div>
    </div>

    <div id="change-password-modal" class="modal">
        <div class="modal-content">
            <h2>Change Password</h2>
            <form class="settings-form" action="change_password.php" method="POST">
                <input type="password" name="old_password" placeholder="Old Password" required>
                <input type="password" name="new_password" placeholder="New Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit">Change Password</button>
            </form>
            <button onclick="document.getElementById('change-password-modal').style.display='none'">Close</button>
        </div>
    </div>

    <div id="change-email-modal" class="modal">
        <div class="modal-content">
            <h2>Change Email</h2>
            <form class="settings-form" action="change_email.php" method="POST">
                <input type="email" name="old_email" placeholder="Old Email" value="<?php echo htmlspecialchars($admin['email']); ?>" required>
                <input type="email" name="new_email" placeholder="New Email" required>
                <input type="email" name="confirm_email" placeholder="Confirm Email" required>
                <button type="submit">Change Email</button>
            </form>
            <button onclick="document.getElementById('change-email-modal').style.display='none'">Close</button>
        </div>
    </div>
</body>
</html>
