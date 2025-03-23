<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Tenant</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fc;
            color: #333;
            display: flex;
            height: 100vh;
        }

        /* Dashboard Layout */
        .dashboard {
            display: flex;
            width: 100%;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #0062cc, #004080);
            color: white;
            padding: 30px;
            height: 100%;
            position: sticky;
            top: 0;
            border-radius: 10px 0 0 10px;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            font-size: 1.8em;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .sidebar ul {
            list-style-type: none;
            padding-left: 0;
            margin-bottom: 40px;
        }

        .sidebar ul li {
            margin: 12px 0;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: white;
            font-size: 16px;
            font-weight: 500;
            padding: 12px;
            border-radius: 6px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: block;
        }

        .sidebar ul li a:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .btn {
            display: inline-block;
            padding: 14px 30px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            background-color: #fff;
            padding: 30px;
            border-radius: 0 10px 10px 0;
            margin-left: 20px;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }

        /* Add Tenant Form */
        .form-box {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        .form-box:hover {
            transform: scale(1.03);
        }

        .form-box h3 {
            font-size: 1.8em;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #0062cc;
        }

        .form-group button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 20px;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .btn {
                padding: 10px 25px;
                font-size: 14px;
            }

            .form-box h3 {
                font-size: 1.5em;
            }

            .form-box label {
                font-size: 14px;
            }

            .form-group input,
            .form-group select {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

    <div class="dashboard">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Tenant Leases</h2>
            <ul>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="add_tenant.php">Add New Tenant</a></li>
                <li><a href="#">View Tenants</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h1>Add New Tenant</h1>
            <div class="form-box">
                <h3>Tenant Information</h3>
                <form action="submit_tenant.php" method="POST">
                    <div class="form-group">
                        <label for="tenantName">Tenant Name</label>
                        <input type="text" id="tenantName" name="tenantName" required placeholder="Enter tenant's name">
                    </div>

                    <div class="form-group">
                        <label for="unitNumber">Unit Number</label>
                        <input type="text" id="unitNumber" name="unitNumber" required placeholder="Enter unit number">
                    </div>

                    <div class="form-group">
                        <label for="leaseStartDate">Lease Start Date</label>
                        <input type="date" id="leaseStartDate" name="leaseStartDate" required>
                    </div>

                    <div class="form-group">
                        <label for="leaseEndDate">Lease End Date</label>
                        <input type="date" id="leaseEndDate" name="leaseEndDate" required>
                    </div>

                    <div class="form-group">
                        <label for="monthlyRent">Monthly Rent (₱)</label>
                        <input type="number" id="monthlyRent" name="monthlyRent" required placeholder="Enter monthly rent" min="1">
                    </div>

                    <div class="form-group">
                        <label for="paymentStatus">Payment Status</label>
                        <select id="paymentStatus" name="paymentStatus" required>
                            <option value="Paid">Paid</option>
                            <option value="Unpaid">Unpaid</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn">Add Tenant</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
