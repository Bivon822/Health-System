<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // Optional: Allow public access

// === Configuration ===
$valid_api_key = "my_secret_key_123";  // API key
$client_id = isset($_GET['id']) ? intval($_GET['id']) : null;
$api_key = $_GET['key'] ?? null;

// === Validate API Key ===
if (!$api_key || $api_key !== $valid_api_key) {
    http_response_code(403);
    echo json_encode(["error" => "Access denied. Invalid API key."]);
    exit();
}

// === Validate Client ID ===
if (!$client_id) {
    http_response_code(400);
    echo json_encode(["error" => "Client ID not provided."]);
    exit();
}

try {
    // === Database Connection ===
    $conn = new mysqli("localhost", "root", "", "health_system");
    if ($conn->connect_error) {
        throw new Exception("Database connection failed.");
    }

    // === Fetch Client Info ===
    $stmt = $conn->prepare("SELECT id, name, email, age, gender, contact FROM clients WHERE id = ?");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $client = $result->fetch_assoc();
    $stmt->close();

    if (!$client) {
        http_response_code(404);
        echo json_encode(["error" => "Client not found."]);
        exit();
    }

    // === Fetch Enrolled Programs ===
    $stmt = $conn->prepare("
        SELECT p.name FROM programs p
        JOIN enrollments e ON e.program_id = p.id
        WHERE e.client_id = ?
    ");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $programs_result = $stmt->get_result();
    $stmt->close();

    $programs = [];
    while ($row = $programs_result->fetch_assoc()) {
        $programs[] = $row['name'];
    }

    // === Final Output ===
    $client['programs'] = $programs;
    echo json_encode($client, JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
?>
