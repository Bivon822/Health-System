<?php
session_start();
include('db.php'); // Make sure your db.php connects correctly

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password FROM clients WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['client_id'] = $row['id'];
            $_SESSION['client_name'] = $row['name'];

            echo json_encode(["status" => "success", "message" => "Welcome, " . $row['name'] . "!"]);
            exit();
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid email or password!"]);
            exit();
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No user found with that email!"]);
        exit();
    }
}
?>
