<!DOCTYPE html>
<html>
<head>
    <title>Login & Registration System</title>
    <!-- CSS styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .container label {
            display: block;
            margin-bottom: 10px;
            color: #333;
        }

        .container input[type="text"],
        .container input[type="password"] {
            width: 90%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .container input[type="submit"] {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .container input[type="submit"]:hover {
            background-color: #555;
        }

        .container .message {
            margin-top: 20px;
            color: #333;
        }

        .container .registration-form {
            display: none;
        }

        .container .show-registration-form {
            display: block;
            margin-bottom: 20px;
            color: #333;
            text-decoration: underline;
            cursor: pointer;
            padding-top:10px;
        }

        .container .show-registration-form:hover {
            color: #555;
        }

        .container .toggle-icon {
            float: right;
            margin-top: -20px;
            cursor: pointer;
            font-size: 24px; /* Updated size of the toggle icon */
        }
    </style>
    <!-- JavaScript -->
    <script>
        
        function toggleRegistrationForm() {
            var registrationForm = document.getElementById("registrationForm");
            var toggleIcon = document.getElementById("toggleIcon");

            if (registrationForm.style.display === "none") {
                registrationForm.style.display = "block";
                toggleIcon.innerText = "-";
            } else {
                registrationForm.style.display = "none";
                toggleIcon.innerText = "+";
            }
        }
    </script>
</head>
<body>
     
    <div class="container">
        <h2>Login</h2>
        <form method="post" action="login.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required ><br><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            <input type="submit" value="Login">
        </form>

        <span class="show-registration-form" onclick="toggleRegistrationForm()">
            Register
            <span id="toggleIcon" class="toggle-icon">+</span>
        </span>

        <form id="registrationForm" class="registration-form" method="post" action="register.php">
            <label for="username">Username:</label>
            <input type="text" name="username" required><br><br>
            <label for="password">Password:</label>
            <input type="password" name="password" required><br><br>
            <input type="submit" value="Register">
        </form>

        <?php
        if (isset($_GET['registration_success'])) {
            echo '<p class="message">Registration successful! Please login to continue.</p>';
        }
        ?>
    </div>
</body>
</html>
