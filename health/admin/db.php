<?php
// Define the database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "health_system";

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
