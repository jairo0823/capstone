<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* General Layout */
        body {
            display: flex;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: #2C3E50;
            color: #fff;
            padding: 20px;
            position: fixed;
            height: 100%;
        }

        .sidebar .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar .logo h2 {
            color: #fff;
        }

        .sidebar .menu {
            list-style: none;
            padding: 0;
        }

        .sidebar .menu li {
            padding: 10px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .sidebar .menu li:hover {
            background: #34495E;
        }

        .sidebar .menu li a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px;
        }

        /* Content Area */
        .content {
            margin-left: 300px;
            padding: 20px;
            background-color: #f4f7fc;
            min-height: 100vh;
        }

        /* Section Styles */
        .section {
            display: none;
            margin-bottom: 30px;
        }

        h2 {
            color: #2C3E50;
            margin-bottom: 20px;
        }

        /* Stats for Dashboard */
        .stats {
            display: flex;
            justify-content: space-between;
        }

        .stat-card {
            background-color: #2980B9;
            padding: 20px;
            color: white;
            text-align: center;
            border-radius: 5px;
            flex: 1;
            margin-right: 10px;
        }

        .stat-card:last-child {
            margin-right: 0;
        }

        /* Tenant Table */
        table {
            width: 300%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #34495E;
            color: #fff;
        }

        /* Buttons */
        button {
            padding: 10px 20px;
            background-color: #2980B9;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 10px;
        }

        button:hover {
            background-color: #1D6F9F;
        }

        /* Form Fields */
        input[type="text"], input[type="email"] {
            width: 120%;
            padding: 10px;
            border: 1px solid #ddd;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        /* Tabs */
        .tabs button {
            padding: 10px 20px;
            margin-right: 10px;
            background: #2980B9;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .tabs button.active {
            background: #1D6F9F;
        }

        .tabs button:hover {
            background: #1D6F9F;
        }

        .notification-item {
            padding: 10px;
            background: #fff;
            border-bottom: 1px solid #ddd;
        }

        .notification-item span {
            font-size: 0.85em;
            color: #999;
        }

        /* Clear Notifications */
        .clear-notifications {
            background: #E74C3C;
            padding: 10px 20px;
            border: none;
            color: white;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 10px;
        }

        .clear-notifications:hover {
            background: #C0392B;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <h2>Admin Dashboard</h2>
        </div>
        <ul class="menu">
            <li><a href="#" onclick="showSection('dashboard')">Dashboard</a></li>
            <li><a href="#" onclick="showSection('tenant-info')">Tenant Information</a></li>
            <li><a href="#" onclick="showSection('notifications')">Notifications</a></li>
            <li><a href="#" onclick="showSection('manage-tenants')">Manage Tenants</a></li>
            <li><a href="#" onclick="showSection('lease-agreements')">Lease Agreements</a></li>
            <li><a href="#" onclick="showSection('reports')">Reports</a></li>
            <li><a href="#" onclick="showSection('settings')">Settings</a></li>
        </ul>
    </div>

    <div class="content">
        <!-- Dashboard -->
        <div class="section" id="dashboard">
            <h2>Dashboard</h2>
            <div class="stats">
                <div class="stat-card">Total Tenants: 50</div>
                <div class="stat-card">Pending Requests: 3</div>
                <div class="stat-card">Active Leases: 40</div>
            </div>
        </div>

        <!-- Tenant Information -->
        <div class="section" id="tenant-info">
            <h2>Tenant Information</h2>
            <input type="text" placeholder="Search Tenant..." class="search-bar">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Apartment</th>
                        <th>Lease Status</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Add tenant data dynamically -->
                    <tr>
                        <td>John Doe</td>
                        <td>Apt 101</td>
                        <td>Active</td>
                        <td>Paid</td>
                    </tr>
                    <tr>
                        <td>Jane Smith</td>
                        <td>Apt 102</td>
                        <td>Active</td>
                        <td>Unpaid</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Notifications -->
        <div class="section" id="notifications">
            <h2>Notifications</h2>
            <div class="notification-list">
                <div class="notification-item">New lease signed <span>5 mins ago</span></div>
                <div class="notification-item">Payment reminder sent <span>10 mins ago</span></div>
            </div>
            <button class="clear-notifications">Clear All</button>
        </div>

        <!-- Manage Tenants -->
        <div class="section" id="manage-tenants">
            <h2>Manage Tenants</h2>
            <button class="add-tenant-btn">Add Tenant</button>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Apartment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Add dynamic tenant list here -->
                </tbody>
            </table>
        </div>

        <!-- Lease Agreements -->
        <div class="section" id="lease-agreements">
            <h2>Lease Agreements</h2>
            <button class="add-lease-btn">Add Lease</button>
            <table>
                <thead>
                    <tr>
                        <th>Tenant Name</th>
                        <th>Apartment</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Add lease agreement data dynamically -->
                </tbody>
            </table>
        </div>

        <!-- Reports -->
        <div class="section" id="reports">
            <h2>Reports</h2>
            <div class="filters">
                <!-- Add filters for reports here -->
            </div>
            <div class="report-graphs">
                <!-- Add graphs or charts for reports here -->
            </div>
            <button class="download-report">Download Report</button>
        </div>

        <!-- Settings -->
        <div class="section" id="settings">
            <h2>Settings</h2>
            <div class="tabs">
                <button class="tab-btn active">Account Settings</button>
                <button class="tab-btn">Notification Settings</button>
                <button class="tab-btn">Privacy Settings</button>
            </div>
            <div class="tab-content">
                <label for="username">Username</label>
                <input type="text" id="username" name="username">
                <label for="email">Email</label>
                <input type="email" id="email" name="email">
            </div>
            <button class="save-settings">Save Changes</button>
        </div>
    </div>

    <script>
        // Function to show the relevant section when clicking on sidebar items
        function showSection(section) {
            const sections = document.querySelectorAll('.section');
            sections.forEach(sec => sec.style.display = 'none');
            document.getElementById(section).style.display = 'block';
        }

        // Set initial view to Dashboard
        showSection('dashboard');
    </script>
</body>
</html>
