<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "health_system";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$client_id = $_POST['client_id'];
$program_ids = $_POST['program_ids'];

// Remove existing enrollments for this client (optional)
// $conn->query("DELETE FROM enrollments WHERE client_id = $client_id");

// Insert new enrollments
foreach ($program_ids as $program_id) {
    $stmt = $conn->prepare("INSERT IGNORE INTO enrollments (client_id, program_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $client_id, $program_id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
echo "Client successfully enrolled in selected program(s).";
?>
