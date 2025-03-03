<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$user_type = $_SESSION['user_type'];

// Handle delete resource request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_resource'])) {
    $resource_id = (int)$_POST['delete_resource'];
    $delete_sql = "DELETE FROM resources WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param('i', $resource_id);

    if ($stmt->execute()) {
        $success_message = "Resource deleted successfully.";
    } else {
        $error_message = "Error deleting resource: " . $conn->error;
    }
}

// Fetch institutions, departments, and resource types for dropdown filters
$institutions = $conn->query("SELECT DISTINCT institution FROM resources");
$departments = isset($_POST['institution']) ? $conn->query("SELECT DISTINCT department FROM resources WHERE institution = '" . $conn->real_escape_string($_POST['institution']) . "'") : null;
$resource_types = $conn->query("SELECT DISTINCT type FROM resources");

// Fetch resources based on filters
$filters = [];
$sql = "SELECT * FROM resources WHERE 1=1";

if (isset($_POST['institution']) && $_POST['institution'] !== '') {
    $filters['institution'] = $_POST['institution'];
    $sql .= " AND institution = ?";
}
if (isset($_POST['department']) && $_POST['department'] !== '') {
    $filters['department'] = $_POST['department'];
    $sql .= " AND department = ?";
}
if (isset($_POST['resource_type']) && $_POST['resource_type'] !== '') {
    $filters['resource_type'] = $_POST['resource_type'];
    $sql .= " AND type = ?";
}

$stmt = $conn->prepare($sql);
if ($filters) {
    $stmt->bind_param(str_repeat('s', count($filters)), ...array_values($filters));
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Resources - Educational Resource Management System</title>
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

        .filter-form {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .filter-form select {
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .action-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .download-btn {
            background-color: #3498db;
            color: white;
        }

        .download-btn:hover {
            background-color: #2980b9;
        }

        .delete-btn {
            background-color: #e74c3c;
            color: white;
        }

        .delete-btn:hover {
            background-color: #c0392b;
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

            .filter-form {
                flex-direction: column;
            }

            .filter-form select {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
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
            <h2>View Resources</h2>

            <?php
            if (isset($success_message)) {
                echo "<p style='color: green;'>$success_message</p>";
            }
            if (isset($error_message)) {
                echo "<p style='color: red;'>$error_message</p>";
            }
            ?>

            <form method="POST" action="" class="filter-form">
                <select name="institution" onchange="this.form.submit()">
                    <option value="">Select Institution</option>
                    <?php while ($row = $institutions->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($row['institution']); ?>" 
                            <?php if (isset($_POST['institution']) && $_POST['institution'] === $row['institution']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($row['institution']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <select name="department" onchange="this.form.submit()">
                    <option value="">Select Department</option>
                    <?php if (isset($departments)): ?>
                        <?php while ($row = $departments->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($row['department']); ?>" 
                                <?php if (isset($_POST['department']) && $_POST['department'] === $row['department']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($row['department']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>

                <select name="resource_type" onchange="this.form.submit()">
                    <option value="">Select Resource Type</option>
                    <?php while ($row = $resource_types->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($row['type']); ?>" 
                            <?php if (isset($_POST['resource_type']) && $_POST['resource_type'] === $row['type']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($row['type']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </form>

            <?php if ($result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Resource Name</th>
                        <th>Resource Type</th>
                        <th>Institution</th>
                        <th>Department</th>
                        <?php if ($user_type === 'admin'): ?>
                            <th>Download</th>
                            <th>Delete</th>
                        <?php else: ?>
                            <th>Download</th>
                        <?php endif; ?>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['type']); ?></td>
                            <td><?php echo htmlspecialchars($row['institution']); ?></td>
                            <td><?php echo htmlspecialchars($row['department']); ?></td>
                            <td>
                                <a href="uploads/<?php echo htmlspecialchars($row['resource_file']); ?>" download class="action-btn download-btn">Download</a>
                            </td>
                            <?php if ($user_type === 'admin'): ?>
                                <td>
                                    <form method="POST" action="" style="display: inline;">
                                        <button type="submit" name="delete_resource" value="<?php echo $row['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this resource?')">Delete</button>
                                    </form>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php else: ?>
                <p>No resources available.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
