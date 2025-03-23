<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 20%;
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sidebar h1 {
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            width: 100%;
        }

        .sidebar ul li {
            margin: 15px 0;
            display: flex;
            align-items: center;
        }

        .sidebar ul li img {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
        }

        .sidebar ul li a:hover {
            text-decoration: underline;
        }

        .main-content {
            width: 80%;
            position: relative;
        }

        .search-bar {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            align-items: center;
        }

        .search-bar input {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px 0 0 5px;
        }

        .search-bar button {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            background-color: #3498db;
            color: white;
            cursor: pointer;
            border-radius: 0 5px 5px 0;
        }

        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }

        .background-overlay img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.7;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h1>Dashboard</h1>
            <ul>
                <li>
                    <img src="assets/icon-tenant.png" alt="Tenant Icon">
                    <a href="#">Tenant List</a>
                </li>
                <li>
                    <img src="assets/icon-view.png" alt="View Icon">
                    <a href="#">View Page</a>
                </li>
                <li>
                    <img src="assets/icon-edit.png" alt="Edit Icon">
                    <a href="#">Edit Page</a>
                </li>
                <li>
                    <img src="assets/icon-maintenance.png" alt="Maintenance Icon">
                    <a href="#">Maintenance Section View</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="search-bar">
                <input type="text" placeholder="Search">
                <button>&#128269;</button>
            </div>
            <div class="background-overlay">
                <img src="img/bg.jpg" alt="Mall Background">
            </div>
        </div>
    </div>
</body>
</html>
