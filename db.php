<?php
// db.php - Database connection
$servername = "127.0.0.1"; // Use IP instead of "localhost"
$username = "root";
$password = ""; // If you have a password, enter it here
$dbname = "resource_management";
$port = 3307; // Specify the correct port

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
