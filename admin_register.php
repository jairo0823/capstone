<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('img/bg.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .registration-container {
            background-color: rgba(144, 238, 144, 0.85); /* Light green overlay */
            width: 400px;
            margin: 100px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .registration-container h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #000;
        }
        .role-buttons button {
            width: 45%;
            padding: 10px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .role-buttons .tenant-btn {
            background-color: #4caf50;
            color: white;
        }
        .role-buttons .tenant-btn:hover {
            background-color: #3e8e41;
        }
        .role-buttons .admin-btn {
            background-color: #2196f3;
            color: white;
        }
        .role-buttons .admin-btn:hover {
            background-color: #1976d2;
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
            background-color: #008cba;
            color: white;
            border: none;
            width: 95%;
            padding: 10px;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #005f73;
        }

        /* Back Button Style */
        .back-btn {
            padding: 10px 20px;
            background-color: #388e3c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            text-align: center;
            display: inline-block;
        }

        .back-btn:hover {
            background-color: #45a049;
        }

        /* Password Strength Indicator */
        .password-strength {
            margin-top: 10px;
            font-size: 14px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <h1>Admin Registration</h1>
        <form action="register_process.php" method="POST" id="registration-form">
            <!-- Role Selection Dropdown -->
            <label for="user_role">Role:</label>
            <select name="user_role" id="user_role" required>
                <option value="admins">Admins</option>
            </select>

            <!-- Form Fields -->
            <input type="text" name="username" placeholder="Username" required>
            <input type="text" name="firstname" placeholder="Firstname" required>
            <input type="text" name="lastname" placeholder="Lastname" required>
            <input type="email" name="email" placeholder="Email" required>

            <!-- Phone Number Field with Validation -->
            <input type="tel" name="phone" id="phone" placeholder="Phone Number" required maxlength="11" pattern="^\d{11}$" title="Please enter exactly 11 digits" oninput="validatePhone()">

            <!-- Password Field with Strong Password Validation -->
            <input type="password" name="password" id="password" placeholder="Password" required oninput="checkPasswordStrength()">
            <div class="password-strength" id="password-strength"></div>

            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            
            <button type="submit" class="submit-btn">Submit</button>
        </form>

        <!-- Back Button -->
        <button class="back-btn" onclick="history.back()">Back</button>
    </div>

    <script>
        // Phone number validation (only numeric input and limit to 11 digits)
        function validatePhone() {
            var phoneInput = document.getElementById('phone');
            var value = phoneInput.value;
            // Remove non-numeric characters
            value = value.replace(/\D/g, '');
            // If the value exceeds 11 digits, trim it
            if (value.length > 11) {
                value = value.slice(0, 11);
            }
            phoneInput.value = value;
        }

        // Password Strength Check
        function checkPasswordStrength() {
            var password = document.getElementById('password').value;
            var strengthMessage = document.getElementById('password-strength');
            var regexLower = /[a-z]/;
            var regexUpper = /[A-Z]/;
            var regexDigit = /\d/;
            var regexSpecial = /[!@#$%^&*(),.?":{}|<>]/;

            var strength = "";

            if (password.length < 8) {
                strength = "Password must be at least 8 characters long.";
            } else if (!regexLower.test(password)) {
                strength = "Password must contain at least one lowercase letter.";
            } else if (!regexUpper.test(password)) {
                strength = "Password must contain at least one uppercase letter.";
            } else if (!regexDigit.test(password)) {
                strength = "Password must contain at least one number.";
            } else if (!regexSpecial.test(password)) {
                strength = "Password must contain at least one special character.";
            } else {
                strength = "Password is strong.";
                strengthMessage.style.color = "green";
            }

            strengthMessage.textContent = strength;
        }

        // Validate if the role is selected before form submission
        document.querySelector("form").addEventListener("submit", function(event) {
            var role = document.getElementById('user_role').value;
            if (!role) {
                alert("Please select a role ( Admin).");
                event.preventDefault(); // Prevent form submission
            }
        });
    </script>
</body>
</html>
