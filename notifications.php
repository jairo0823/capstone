<?php
// Connection to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tesys";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all announcements
$sql = "SELECT * FROM announcements ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Dashboard</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color:rgb(43, 244, 123);
        }

        .sidebar {
            background-color:rgb(13, 21, 28);
            color: #ffffff;
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 40px 20px;
            box-shadow: 2px 0 20px rgba(0, 0, 0, 0.15);
            transition: width 0.3s;
        }

        .sidebar h2 {
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 40px;
        }

        .menu a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color:rgb(11, 151, 81);
            margin: 15px 0;
            padding: 12px 20px;
            border-radius: 8px;
            transition: background-color 0.3s, transform 0.3s ease;
        }

        .menu a:hover {
            background-color:rgb(26, 126, 86);
            transform: translateX(10px);
        }

        .menu i {
            margin-right: 15px;
            font-size: 22px;
        }

        .active-link {
            background-color: #1a237e;
            font-weight: bold;
            transform: translateX(10px);
        }

        .main {
            margin-left: 250px;
            padding: 40px;
            width: calc(100% - 250px);
            transition: margin-left 0.3s, width 0.3s;
        }

        .header h1 {
            font-size: 32px;
            font-weight: 600;
            color:rgb(45, 72, 61);
            margin-bottom: 30px;
        }

        .notifications {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
        }

        .notification-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            padding: 25px;
            width: calc(33.33% - 25px);
            min-width: 280px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .notification-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .notification-card h2 {
            font-size: 22px;
            color:rgb(43, 176, 101);
            margin-bottom: 15px;
        }

        .notification-card p {
            font-size: 16px;
            color:rgb(10, 102, 42);
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .notification-card small {
            font-size: 14px;
            color: #a0aec0;
        }

        .back-button {
            background-color: #006400;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 20px;
            transition: background-color 0.3s;
        }

        .back-button:hover {
            background-color: #004d00;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 220px;
                padding: 30px 15px;
            }

            .main {
                margin-left: 220px;
                width: calc(100% - 220px);
            }

            .notification-card {
                width: calc(50% - 25px);
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                position: static;
                width: 100%;
                height: auto;
 padding: 20px;
                box-shadow: none;
            }

            .main {
                margin-left: 0;
                width: 100%;
            }

            .notifications {
                flex-direction: column;
                gap: 20px;
            }

            .notification-card {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Tenant Dashboard</h2>
        <div class="menu">
        
        
            <a href="#"><i class="material-icons">Notifications</i></a>
          
        </div>
    </div>

    <div class="main">
        <!-- Back Button -->
        <a href="javascript:history.back()" class="back-button">Back</a>

        <div class="header">
            <h1>Announcements</h1>
        </div>

        <div class="notifications">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="notification-card">
                        <h2>Announcement #<?= $row['id'] ?></h2>
                        <p><?= htmlspecialchars($row['announcement']) ?></p>
                        <small>Posted on <?= $row['created_at'] ?></small>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No announcements yet.</p>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
