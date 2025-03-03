<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db.php';

    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $user_type = $_POST['user_type'];

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (username, password, user_type) VALUES ('$username', '$hashed_password', '$user_type')";
    if ($conn->query($sql) === TRUE) {
        header('Location: login.php');
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #1d3557, #457b9d);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
        }

        .tabs {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }

        .tabs button {
            background: none;
            border: none;
            padding: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }

        .tabs button.active {
            color: #1d3557;
            border-bottom: 2px solid #1d3557;
        }

        .form {
            display: none;
        }

        .form.active {
            display: block;
        }

        .form input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form button {
            width: 95%;
            padding: 10px;
            margin: 10px 0;
            background: #1d3557;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form button:hover {
            background: #457b9d;
        }

        .form a {
            display: block;
            color: #1d3557;
            text-decoration: none;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Signup Form</h2>

        <div class="tabs">
            <button id="adminTab" class="active" onclick="showForm('admin')">Admin Signup</button>
            <button id="studentTab" onclick="showForm('student')">Student Signup</button>
        </div>

        <form id="adminForm" class="form active" method="POST" action="">
            <input type="text" name="username" placeholder="Admin Username" required>
            <input type="password" name="password" id="adminPassword" placeholder="Password" required>
            <input type="password" name="confirm_password" id="adminConfirmPassword" placeholder="Confirm Password" required>
            <input type="hidden" name="user_type" value="admin">
            <button type="submit" onclick="return validatePasswords('adminPassword', 'adminConfirmPassword')">Signup</button>
            <a href="login.php">Already have an account? Login</a>
        </form>

        <form id="studentForm" class="form" method="POST" action="">
            <input type="text" name="username" placeholder="Student Username" required>
            <input type="password" name="password" id="studentPassword" placeholder="Password" required>
            <input type="password" name="confirm_password" id="studentConfirmPassword" placeholder="Confirm Password" required>
            <input type="hidden" name="user_type" value="student">
            <button type="submit" onclick="return validatePasswords('studentPassword', 'studentConfirmPassword')">Signup</button>
            <a href="login.php">Already have an account? Login</a>
        </form>

    </div>

    <script>
        function showForm(type) {
            const adminTab = document.getElementById('adminTab');
            const studentTab = document.getElementById('studentTab');
            const adminForm = document.getElementById('adminForm');
            const studentForm = document.getElementById('studentForm');

            if (type === 'admin') {
                adminTab.classList.add('active');
                studentTab.classList.remove('active');
                adminForm.classList.add('active');
                studentForm.classList.remove('active');
            } else {
                studentTab.classList.add('active');
                adminTab.classList.remove('active');
                studentForm.classList.add('active');
                adminForm.classList.remove('active');
            }
        }

        function validatePasswords(passwordId, confirmPasswordId) {
            const password = document.getElementById(passwordId).value;
            const confirmPassword = document.getElementById(confirmPasswordId).value;
            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>