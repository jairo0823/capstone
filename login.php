<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Xentro Mall Calapan</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('img/bg.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .container {
            display: flex;
            width: 600px; /* Adjusted width */
            height: 600px; /* Increased height for better fit */
            background-color: rgba(255, 255, 255, 0.9); /* White background with transparency */
            border-radius: 15px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .container:hover {
            transform: scale(1.02); /* Slight zoom effect on hover */
        }

        /* Left Section with Circular Logo */
        .left-section {
            width: 50%;
            background: url('img/mall-background.jpg') no-repeat center center;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            border-right: 5px solid #2E8B57; /* Green border for separation */
        }

        .logo {
            width: 150px; /* Adjusted logo size */
            height: 150px; /* Adjusted logo size */
            border-radius: 50%;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .logo img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover; /* Ensures the image covers the logo area */
        }

        /* Right Section: Login Form */
        .right-section {
            width: 60%;
            padding: 40px 30px; /* Adjusted padding */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        h1 {
            text-align: center;
            font-size: 32px; /* Increased font size */
            margin-bottom: 20px;
            color: #2E8B57; /* Green color for the title */
        }

        label {
            font-size: 15px; /* Adjusted font size */
            color: #333; /* Darker color for form labels */
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #2E8B57; /* Green focus color */
            box-shadow: 0 0 5px rgba(46, 139, 87, 0.5); /* Subtle shadow on focus */
        }

        button.submit {
            width: 100%;
            padding: 12px;
            background-color: #2E8B57; /* Green button */
            border: none;
            border-radius: 8px;
            color: #fff; /* White text color */
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button.submit:hover {
            background-color: #245d3a; /* Darker green on hover */
            transform: translateY(-2px); /* Slight lift effect on hover */
        }

        .forgot-password {
            text-align: center;
            margin-top: 15px;
        }

        .forgot-password a {
            color: #2E8B57; /* Green color for the link */
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: #245d3a; /* Darker green on hover */
        }

        /* Back Button Style */
        .back-btn {
            margin-top: 20px;
            padding: 12px;
            background-color: #2E8B57; /* Green color matching the form */
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            text-align: center;
            font-size: 16px;
        }

        .back-btn:hover {
            background-color: #245d3a; /* Darker green on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Left Section with Circular Logo -->
        <div class="left-section">
            <div class="logo">
                <img src="img/logo.jpg" alt="Xentro Mall Logo">
            </div>
        </div>

        <!-- Right Section with Login Form -->
        <div class="right-section">
            <h1>Login</h1>
            <form action="login_process.php" method="POST">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <button type="submit" class="submit">Log in</button>
            </form>

            <div class="forgot-password">
                <a href="forgot_pass.php">Forgot password?</a>
            </div>

            <!-- Back Button -->
            <button class="back-btn" onclick="window.location.href='index.php'">Back to Home</button>
        </div>
    </div>
</body>
</html>
