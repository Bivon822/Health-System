<?php
// Include database connection
include('db.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form values
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Password validation (ensure passwords match)
    if ($password !== $confirm_password) {
        echo "<p class='error'>Passwords do not match!</p>";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert client details into database
        $stmt = $conn->prepare("INSERT INTO clients (name, age, gender, contact, email, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissss", $name, $age, $gender, $contact, $email, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            echo "<p>Client registered successfully!</p>";
        } else {
            echo "<p class='error'>Error: " . $stmt->error . "</p>";
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>
