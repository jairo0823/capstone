<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xentro Mall - Tenant Portal</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: url('img/bg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #2e7d32;
            line-height: 1.6;
        }

        /* Navbar */
        .navbar {
            background-color: rgba(46, 125, 50, 0.9);
            color: white;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .navbar .logo {
            display: flex;
            align-items: center;
        }

        .navbar .logo img {
            height: 50px;
            margin-right: 10px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-size: 1em;
            padding: 10px 20px;
            border-radius: 30px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .navbar a:hover {
            background-color: #66bb6a;
            transform: scale(1.1);
        }

        /* Container */
        .container {
            text-align: center;
            margin: 50px auto;
            padding: 20px;
            max-width: 1200px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .container h1 {
            font-size: 3em;
            margin-bottom: 20px;
            color: #1b5e20;
            font-weight: bold;
        }

        .container p {
            font-size: 1.3em;
            color: #4caf50;
            margin-bottom: 40px;
        }

        .benefits {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .benefit-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 250px;
        }

        .benefit-box h3 {
            color: #1b5e20;
            margin-bottom: 10px;
        }

        /* Footer */
        .footer {
            margin-top: 50px;
            background-color: #1b5e20;
            color: white;
            text-align: center;
            padding: 20px 0;
            font-size: 1em;
        }

        .footer a {
            color: #c8e6c9;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">
            <img src="img/logo.jpg" alt="Xentro Mall Logo">
            <span>Xentro Mall</span>
        </div>
        <div>
            <a href="login.php">Login</a>
            <a href="form.php">Signup</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <h1>Welcome to Xentro Mall Tenant Portal</h1>
        <p>Join our vibrant commercial hub and grow your business today!</p>
        
        <div class="benefits">
            <div class="benefit-box">
                <h3>Prime Location</h3>
                <p>Strategically located for maximum foot traffic and exposure.</p>
            </div>
            <div class="benefit-box">
                <h3>Modern Facilities</h3>
                <p>State-of-the-art amenities and security for a seamless business experience.</p>
            </div>
            <div class="benefit-box">
                <h3>Marketing Support</h3>
                <p>Get featured in our promotional campaigns to reach more customers.</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; 2024 Xentro Mall. All Rights Reserved. | <a href="privacy-policy.html">Privacy Policy</a>
    </div>
</body>
</html>
