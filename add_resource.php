<?php 
include 'db.php'; 
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
$username = $_SESSION['username'];
$user_type = $_SESSION['user_type'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Resource - Educational Resource Management System</title>
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

        .header h1 a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .header h1 a:hover {
            color: #3498db;
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

        .dashboard-content {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .dashboard-content h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: center;
        }

        .add-resource-form {
            max-width: 600px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #2980b9;
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
            <h2>Add New Resource</h2>
            
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $name = $_POST['name'];
                $type = $_POST['type'];
                $institution = $_POST['institution'];
                $department = $_POST['department'];
                $resource_file = $_FILES['resource_file']['name'];

                // Save the uploaded file to the uploads directory
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["resource_file"]["name"]);

                if (move_uploaded_file($_FILES["resource_file"]["tmp_name"], $target_file)) {
                    // Insert the resource details into the database
                    $sql = "INSERT INTO resources (name, type, institution, department, resource_file) 
                            VALUES ('$name', '$type', '$institution', '$department', '$resource_file')";

                    if ($conn->query($sql) === TRUE) {
                        echo "<div style='color: green; text-align: center; margin-bottom: 15px;'>Resource added successfully!</div>";
                    } else {
                        echo "<div style='color: red; text-align: center; margin-bottom: 15px;'>Error: " . $conn->error . "</div>";
                    }
                } else {
                    echo "<div style='color: red; text-align: center; margin-bottom: 15px;'>Sorry, there was an error uploading your file.</div>";
                }
            }
            ?>

            <form action="add_resource.php" method="POST" enctype="multipart/form-data" class="add-resource-form">
                <div class="form-group">
                    <label for="name">Resource Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="type">Resource Type:</label>
                    <select id="type" name="type" required>
                        <option value="" disabled selected>Select Resource Type</option>
                        <option value="Textbooks">Textbooks</option>
                        <option value="Digital Materials">Digital Materials</option>
                        <option value="Library Books">Library Books</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="institution">Institution:</label>
                    <select id="institution" name="institution" onchange="updateDepartments()" required>
                        <option value="" disabled selected>Select Institution</option>
                        <option value="MIT Institute of Design">MIT Institute of Design</option>
                        <option value="MIT College of Management">MIT College of Management</option>
                        <option value="MIT School of Food Technology">MIT School of Food Technology</option>
                        <option value="MIT School of Film and Theatre">MIT School of Film and Theatre</option>
                        <option value="MIT School of Indian Civil Services">MIT School of Indian Civil Services</option>
                        <option value="MIT School of Computing">MIT School of Computing</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="department">Department:</label>
                    <select id="department" name="department" required>
                        <option value="" disabled selected>Select Department</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="resource_file">Upload Resource (PDF/Video):</label>
                    <input type="file" id="resource_file" name="resource_file" required>
                </div>

                <button type="submit" class="submit-btn">Add Resource</button>
            </form>
        </div>
    </div>

    <script>
        // Departments for each institution
        const departments = {
            "MIT Institute of Design": [
                "Bachelor of Design",
                "Master of Design (Animation Design)",
                "Master of Design (Design Management)",
                "Master of Design (Fashion Management and Marketing)"
            ],
            "MIT College of Management": [
                "Bachelor of Commerce (HONORS)",
                "Bachelor of Computer Applications",
                "Master of Business Administration (Executive)"
            ],
            "MIT School of Food Technology": [
                "B. Tech. (Food Technology)",
                "M. Tech. (Food Safety and Quality Management)",
                "M. Tech. (Food Technology)"
            ],
            "MIT School of Film and Theatre": [
                "B.A. in Direction & Screenplay Writing",
                "B.Sc. in Filmmaking",
                "Bachelor of Arts (Dramatics)",
                "M.A. in Direction & Screenplay Writing"
            ],
            "MIT School of Indian Civil Services": [
                "B.A. (Administration)",
                "M.A. (Administration)"
            ],
            "MIT School of Computing": [
                "Bachelor of Technology (Computer Science & Engineering)",
                "Master of Science (Computer Science/Artificial Intelligence & Machine Learning)",
                "Master of Technology (Computer Science & Engineering - Intelligent Systems & Analysis)",
                "Master of Technology (Information Technology - Cyber Security)"
            ]
        };

        // Function to update department options based on selected institution
        function updateDepartments() {
            const institutionSelect = document.getElementById("institution");
            const departmentSelect = document.getElementById("department");
            const selectedInstitution = institutionSelect.value;

            // Clear the current options in the department dropdown
            departmentSelect.innerHTML = "<option value='' disabled selected>Select Department</option>";

            // Get departments for the selected institution
            if (departments[selectedInstitution]) {
                departments[selectedInstitution].forEach(department => {
                    const option = document.createElement("option");
                    option.value = department;
                    option.text = department;
                    departmentSelect.appendChild(option);
                });
            }
        }
    </script>
</body>
</html>