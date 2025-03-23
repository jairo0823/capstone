<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xentro Mall Registration Page</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f9f4; /* Light green background */
        }
        .header {
            background-color: #28a745; /* Green header */
            color: white;
            padding: 15px 0;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }
        .registration-container {
            background-color: #ffffff; /* White background for the form */
            width: 400px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .registration-container h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #28a745; /* Green color for header */
        }
        .registration-container input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .submit-btn {
            background-color: #28a745; /* Green button */
            color: white;
            border: none;
            width: 95%;
            padding: 10px;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #218838; /* Darker green on hover */
        }
        .password-requirements {
            font-size: 12px;
            color: #555;
            text-align: left;
            margin-top: 10px;
        }
        .password-requirements li {
            color: red;
        }
    </style>
</head>
<body>
    <div class="header">
        Xentro Mall Registration
    </div>

    <div class="registration-container">
        <h1>Registration</h1>
        <form action="register_process.php" method="POST">
            <!-- Form Fields -->
            <input type="text" name="username" placeholder="Username" required>
            <input type="text" name="firstname" placeholder="Firstname" required>
            <input type="text" name="lastname" placeholder="Lastname" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="tel" name="phone" placeholder="Phone Number" required 
                   pattern="\d{11}" maxlength="11" 
                   oninput="this.value = this.value.replace(/[^\d]/g, '').slice(0, 11);">
            <input type="password" name="password" placeholder="Password" required id="password">
            <input type="password" name="confirm_password" placeholder="Confirm Password" required id="confirm_password">

            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>

    <script>
        // Password validation checks
        document.querySelector("form").addEventListener("submit", function(event) {
            var password = document.querySelector('input[name="password"]').value;
            var confirmPassword = document.querySelector('input[name="confirm_password"]').value;

            // Regex for checking the password strength
            var lowercase = /[a-z]/.test(password);
            var uppercase = /[A-Z]/.test(password);
            var number = /\d/.test(password);
            var specialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password);
            var length = password.length >= 8;

            // Check if all password conditions are met
            if (!lowercase || !uppercase || !number || !specialChar || !length) {
                event.preventDefault(); // Prevent form submission
                alert("Password must meet all the complexity requirements.");
            }

            // Check if passwords match
            if (password !== confirmPassword) {
                event.preventDefault();
                alert("Passwords do not match.");
            }
        });
    </script>
</body>
</html>
