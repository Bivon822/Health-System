<?php
header("Content-Type: application/json");

// Connect to DB
$conn = new mysqli("localhost", "root", "", "health_system");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed."]);
    exit();
}

// Check API key
if (!isset($_GET['key'])) {
    http_response_code(403);
    echo json_encode(["error" => "Missing API key."]);
    exit();
}

$api_key = $conn->real_escape_string($_GET['key']);
$key_check = $conn->query("SELECT * FROM api_keys WHERE api_key = '$api_key' AND is_active = 1");

if ($key_check->num_rows === 0) {
    http_response_code(403);
    echo json_encode(["error" => "Invalid or inactive API key."]);
    exit();
}

// Check for client ID
if (!isset($_GET['id'])) {
    echo json_encode(["error" => "Client ID not provided."]);
    exit();
}

$client_id = intval($_GET['id']);

// Get client info
$stmt = $conn->prepare("SELECT id, name, email, age, gender, contact FROM clients WHERE id = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
$client = $result->fetch_assoc();
$stmt->close();

if (!$client) {
    echo json_encode(["error" => "Client not found."]);
    exit();
}

// Get enrolled programs
$stmt = $conn->prepare("
    SELECT p.name FROM programs p
    JOIN enrollments e ON e.program_id = p.id
    WHERE e.client_id = ?
");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$programs_result = $stmt->get_result();

$programs = [];
while ($row = $programs_result->fetch_assoc()) {
    $programs[] = $row['name'];
}
$stmt->close();

$client['programs'] = $programs;

echo json_encode($client, JSON_PRETTY_PRINT);
?>
