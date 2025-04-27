<?php
include('db.php'); 

$username = 'admin';
$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if admin already exists
$stmt = $conn->prepare("SELECT id FROM admins WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Admin already exists.";
} else {
    // Insert new admin
    $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        echo "Admin created successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
$stmt->close();
$conn->close();
?>
