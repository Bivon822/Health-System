<?php
if (!isset($_GET['id'])) {
    echo "No client selected.";
    exit();
}

$client_id = $_GET['id'];

$conn = new mysqli("localhost", "root", "", "health_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get client info
$stmt = $conn->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$result = $stmt->get_result();
$client = $result->fetch_assoc();
$stmt->close();

if (!$client) {
    echo "Client not found.";
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
$programs = $stmt->get_result();
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Client Profile</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f7f9fc;
            padding: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .profile-box {
            width: 600px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .info p {
            margin: 8px 0;
        }

        ul {
            padding-left: 20px;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

    <div class="profile-box">
        <h2>Client Profile</h2>
        <div class="info">
            <p><strong>Name:</strong> <?= htmlspecialchars($client['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($client['email']) ?></p>
            <p><strong>Age:</strong> <?= $client['age'] ?></p>
            <p><strong>Gender:</strong> <?= $client['gender'] ?></p>
            <p><strong>Contact:</strong> <?= $client['contact'] ?></p>
        </div>

        <h3>Enrolled Programs</h3>
        <ul>
            <?php
            if ($programs->num_rows > 0) {
                while ($row = $programs->fetch_assoc()) {
                    echo "<li>" . htmlspecialchars($row['name']) . "</li>";
                }
            } else {
                echo "<li>No programs enrolled.</li>";
            }
            ?>
        </ul>

        <p style="text-align: center;"><a href="search_client.php">← Back to Search</a></p>
    </div>
</body>
</html>
