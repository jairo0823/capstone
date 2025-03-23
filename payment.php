<?php
session_start();
include 'db_config.php'; // Include your database configuration

// Check if user is logged in and is a tenant
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'tenant') {
    header("Location: login.php");
    exit();
}

$uploadMessage = ""; // Variable to hold the upload message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tenant_id = $_SESSION['user_id'];
    $target_dir = "uploads/"; // Directory where images will be uploaded
    $target_file = $target_dir . basename($_FILES["payment_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["payment_image"]["tmp_name"]);
    if ($check === false) {
        $uploadMessage = "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (limit to 2MB)
    if ($_FILES["payment_image"]["size"] > 2000000) {
        $uploadMessage = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        $uploadMessage = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $uploadMessage = "Sorry, your file was not uploaded.";
    } else {
        // If everything is ok, try to upload file
        if (move_uploaded_file($_FILES["payment_image"]["tmp_name"], $target_file)) {
            // Insert into database
            $query = "INSERT INTO payments (tenant_id, payment_image) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("is", $tenant_id, $target_file);
            if ($stmt->execute()) {
                $uploadMessage = "The file " . htmlspecialchars(basename($_FILES["payment_image"]["name"])) . " has been uploaded.";
            } else {
                $uploadMessage = "Error: " . $stmt->error;
            }
        } else {
            $uploadMessage = "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Payment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4fdf4; /* Light green background */
            color: #388e3c; /* Green text color */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 90%;
            max-width: 450px;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: bold;
            color: #388e3c; /* Green title */
        }
        .message {
            margin-bottom: 20px;
            font-size: 16px;
            color: #28a745; /* Green color for success messages */
        }
        label {
            font-size: 16px;
            margin-bottom: 10px;
            display: block;
        }
        input[type="file"] {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #388e3c; /* Green border */
            border-radius: 5px;
            background: #fff;
            color: #333;
            width: 100%;
            transition: border 0.3s;
        }
        input[type="file"]:hover {
            border: 2px solid #28a745; /* Green border on hover */
        }
        input[type="submit"] {
            background: #388e3c; /* Green button */
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 18px;
            transition: background 0.3s, transform 0.2s;
        }
        input[type="submit"]:hover {
            background: #2c6e28; /* Darker green on hover */
            transform: scale(1.05);
        }
        .back-btn {
            background:rgba(0, 255, 119, 0.56); /* Blue button */
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 18px;
            margin-top: 10px;
        }
        .back-btn:hover {
            background:rgb(0, 179, 63); /* Darker blue on hover */
            transform: scale(1.05);
        }
        @media (max-width: 600px) {
            h1 {
                font-size: 24px;
            }
            input[type="submit"] {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!empty($uploadMessage)): ?>
            <div class="message"><?php echo $uploadMessage; ?></div>
        <?php endif; ?>
        <h1>Upload Payment Image</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="payment_image">Select image to upload:</label>
            <input type="file" name="payment_image" id="payment_image" required>
            <input type="submit" value="Upload Image" name="submit">
        </form>
        
        <!-- Back Button -->
        <button class="back-btn" onclick="window.history.back()">Go Back</button>
    </div>
</body>
</html>
