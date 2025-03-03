<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db.php';

    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_type = $_POST['user_type'];

    $sql = "SELECT * FROM users WHERE username = '$username' AND user_type = '$user_type'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_type'] = $row['user_type'];
            header('Location: index.php');
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    } else {
        $error = "Invalid username, password, or user type.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        <h2>Login Form</h2>
        <div class="tabs">
            <button id="adminTab" class="active">Admin Login</button>
            <button id="studentTab">Student Login</button>
        </div>

        <form id="adminForm" class="form active" method="POST" action="">
            <input type="text" placeholder="Admin Username" name="username" required><br>
            <input type="password" placeholder="Password" name="password" required><br>
            <input type="hidden" name="user_type" value="admin">
            <button type="submit">Login</button>
            <a href="signup.php">Don't have an account? Sign up</a>
        </form>

        <form id="studentForm" class="form" method="POST" action="">
            <input type="text" placeholder="Student Username" name="username" required><br>
            <input type="password" placeholder="Password" name="password" required><br>
            <input type="hidden" name="user_type" value="student">
            <button type="submit">Login</button>
            <a href="signup.php">Don't have an account? Sign up</a>
        </form>

        <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
    </div>

    <script>
        const adminTab = document.getElementById('adminTab');
        const studentTab = document.getElementById('studentTab');
        const adminForm = document.getElementById('adminForm');
        const studentForm = document.getElementById('studentForm');

        adminTab.addEventListener('click', () => {
            adminTab.classList.add('active');
            studentTab.classList.remove('active');
            adminForm.classList.add('active');
            studentForm.classList.remove('active');
        });

        studentTab.addEventListener('click', () => {
            studentTab.classList.add('active');
            adminTab.classList.remove('active');
            studentForm.classList.add('active');
            adminForm.classList.remove('active');
        });
    </script>
</body>
</html>
