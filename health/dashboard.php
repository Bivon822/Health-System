<?php
session_start();

if (!isset($_SESSION['client_id'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$db = "health_system";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$client_id = $_SESSION['client_id'];

$client_query = "SELECT * FROM clients WHERE id = ?";
$stmt = $conn->prepare($client_query);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$client_result = $stmt->get_result();
$client = $client_result->fetch_assoc();
$stmt->close();

$program_query = "
    SELECT p.name FROM programs p
    JOIN enrollments e ON e.program_id = p.id
    WHERE e.client_id = ?
";
$stmt = $conn->prepare($program_query);
$stmt->bind_param("i", $client_id);
$stmt->execute();
$program_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            margin: 0;
            background-color: #f2f6fa;
            color: #333;
        }

        .navbar {
            background-color: #007bff;
            padding: 15px 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .navbar-right strong {
            font-size: 1rem;
        }

        .logout-btn {
            background: transparent;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            transition: color 0.3s;
        }

        .logout-btn:hover {
            color: #ffc107;
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #007bff;
        }

        .info {
            margin-bottom: 30px;
        }

        .info p {
            font-size: 1rem;
            margin: 10px 0;
        }

        .programs h3 {
            margin-bottom: 15px;
            color: #333;
        }

        ul {
            list-style-type: disc;
            padding-left: 20px;
        }

        ul li {
            margin: 6px 0;
        }

        @media (max-width: 768px) {
            .navbar h1 {
                font-size: 1.2rem;
            }

            .container {
                margin: 20px 10px;
                padding: 15px;
            }

            .info p {
                font-size: 0.95rem;
            }
        }
    </style>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <h1>Health Dashboard</h1>
    <div class="navbar-right">
        <strong><?php echo htmlspecialchars($client['name']); ?></strong>
        <button class="logout-btn" onclick="confirmLogout()" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </button>
    </div>
</div>

<!-- Main Content -->
<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($client['name']); ?>!</h2>

    <div class="info">
        <p><strong>Email:</strong> <?php echo htmlspecialchars($client['email']); ?></p>
        <p><strong>Age:</strong> <?php echo htmlspecialchars($client['age']); ?></p>
        <p><strong>Gender:</strong> <?php echo htmlspecialchars($client['gender']); ?></p>
        <p><strong>Contact:</strong> <?php echo htmlspecialchars($client['contact']); ?></p>
    </div>

    <div class="programs">
        <h3>Enrolled Programs:</h3>
        <ul>
            <?php
            if ($program_result->num_rows > 0) {
                while ($row = $program_result->fetch_assoc()) {
                    echo "<li>" . htmlspecialchars($row['name']) . "</li>";
                }
            } else {
                echo "<li>No programs enrolled yet.</li>";
            }
            ?>
        </ul>
    </div>
</div>

<!-- Logout Confirmation Script -->
<script>
function confirmLogout() {
    Swal.fire({
        title: 'Are you sure?',
        text: "You will be logged out.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#007bff',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, logout!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'logout.php';
        }
    })
}
</script>

</body>
</html>
