<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the form data with fallback for unset fields
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $age = $_POST['age'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if the email is already registered
        $check = $conn->prepare("SELECT id FROM clients WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $error = "This email is already registered.";
        } else {
            // Hash the password and insert the client into the database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO clients (name, email, age, gender, contact, password) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssisss", $name, $email, $age, $gender, $contact, $hashed_password);

            if ($stmt->execute()) {
                $success = "Client registered successfully!";
            } else {
                $error = "Failed to register client.";
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register New Client</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background: #f5f7fa;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    padding: 20px; /* Added padding to ensure space on small screens */
}

.register-container {
    background-color: #ffffff;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 500px; /* Increased max-width for larger screens */
    min-width: 300px; /* Ensures the form doesn't shrink too much */
    margin: 0 auto; /* Centers the form */
}

h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #333;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #444;
}

input[type="text"],
input[type="email"],
input[type="password"],
input[type="number"],
select {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 15px;
}

input[type="submit"] {
    background-color: #007bff;
    color: white;
    font-weight: bold;
    border: none;
    padding: 12px;
    width: 100%;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s ease;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

.error-message {
    color: red;
    text-align: center;
    margin-top: 10px;
}

/* Responsive design adjustments */
@media (max-width: 600px) {
    .register-container {
        padding: 20px; /* Reduce padding for small screens */
        max-width: 100%; /* Make the form take full width */
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="number"],
    select {
        font-size: 14px; /* Reduce font size on smaller screens */
        padding: 10px; /* Reduce padding */
    }

    input[type="submit"] {
        padding: 10px;
    }
}

    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

    <div class="register-container">
        <h2>Register New Client</h2>
        <form method="POST">
            <label for="name">Full Name</label>
            <input type="text" name="name" required>

            <label for="email">Email Address</label>
            <input type="email" name="email" required>

            <label for="age">Age</label>
            <input type="number" name="age" required>

            <label for="gender">Gender</label>
            <select name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>

            <label for="contact">Contact Number</label>
            <input type="text" name="contact" required>

            <label for="password">Password</label>
            <input type="password" name="password" required>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" name="confirm_password" required>

            <input type="submit" value="Register Client">
        </form>

        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
    </div>

    <?php if (isset($success)): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Registration Successful',
                text: '<?php echo $success; ?>',
                confirmButtonText: 'OK'
            });
        </script>
    <?php elseif (isset($error)): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Registration Failed',
                text: '<?php echo $error; ?>',
                confirmButtonText: 'OK'
            });
        </script>
    <?php endif; ?>
</body>
</html>
