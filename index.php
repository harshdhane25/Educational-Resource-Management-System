<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Educational Resource Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            background-color: #f4f7f6;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .header h1 {
            margin: 0;
            font-size: 1.8rem;
        }

        .header a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .header a:hover {
            color: #3498db;
        }

        .user-welcome {
            background-color: #ecf0f1;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .navigation {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .nav-list {
            display: flex;
            list-style-type: none;
            gap: 20px;
        }

        .nav-list li a {
            text-decoration: none;
            color: #2c3e50;
            padding: 10px 15px;
            border-radius: 6px;
            transition: all 0.3s ease;
            background-color: #ecf0f1;
            display: inline-block;
        }

        .nav-list li a:hover {
            background-color: #3498db;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .logout-btn {
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        .dashboard-content {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .dashboard-content h2 {
            color: #2c3e50;
            margin-bottom: 15px;
            text-align: center;
        }

        .quick-stats {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .stat-box {
            background-color: #ecf0f1;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            width: 200px;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
            }

            .nav-list {
                flex-direction: column;
                align-items: center;
            }

            .quick-stats {
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <?php
    session_start();
    if (!isset($_SESSION['username'])) {
        header('Location: login.php');
        exit();
    }
    $username = $_SESSION['username'];
    $user_type = $_SESSION['user_type'];
    ?>

    <div class="container">
        <div class="header">
            <h1><a href="index.php">Educational Resource Management System</a></h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <div class="user-welcome">
            <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
            <p>You are logged in as a <?php echo htmlspecialchars($user_type); ?></p>
        </div>

        <nav>
            <ul class="nav-list">
                <?php if ($user_type === 'admin'): ?>
                    <li><a href="add_resource.php">Add Resource</a></li>
                <?php endif; ?>
                <li><a href="view_resources.php">View Resources</a></li>
            </ul>
        </nav>

        <div class="dashboard-content">
            <h2>Dashboard Overview</h2>
            <div class="quick-stats">
                <div class="stat-box">
                    <h3>Total Resources</h3>
                    <?php
                    include 'db.php';
                    $resource_count_query = "SELECT COUNT(*) as count FROM resources";
                    $resource_count_result = $conn->query($resource_count_query);
                    $resource_count = $resource_count_result->fetch_assoc()['count'];
                    echo "<p>$resource_count</p>";
                    ?>
                </div>
                <div class="stat-box">
                    <h3>User Type</h3>
                    <p><?php echo ucfirst(htmlspecialchars($user_type)); ?></p>
                </div>
                <div class="stat-box">
                    <h3>Last Login</h3>
                    <p><?php echo date('Y-m-d H:i:s'); ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>