<?php
// Start session (optional: for added security)
session_start();

// Check if the user is logged in (you can create a login page to manage admin access later)
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

$conn = new mysqli("localhost", "root", "", "health_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle new API key creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_key'])) {
    $description = $_POST['description'];
    $api_key = bin2hex(random_bytes(16));  // Generates a secure 32-character API key

    // Insert the new API key into the database
    $stmt = $conn->prepare("INSERT INTO api_keys (api_key, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $api_key, $description);
    $stmt->execute();
    $stmt->close();
    echo "<p>New API Key created: $api_key</p>";
}

// Handle disabling or deleting API keys
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['api_key'])) {
    $action = $_POST['action'];
    $api_key = $_POST['api_key'];

    if ($action == 'disable') {
        $stmt = $conn->prepare("UPDATE api_keys SET is_active = 0 WHERE api_key = ?");
        $stmt->bind_param("s", $api_key);
        $stmt->execute();
        $stmt->close();
        echo "<p>API Key $api_key has been disabled.</p>";
    } elseif ($action == 'delete') {
        $stmt = $conn->prepare("DELETE FROM api_keys WHERE api_key = ?");
        $stmt->bind_param("s", $api_key);
        $stmt->execute();
        $stmt->close();
        echo "<p>API Key $api_key has been deleted.</p>";
    }
}

$result = $conn->query("SELECT * FROM api_keys");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage API Keys</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f7f9fc;
            padding: 30px;
        }

        .container {
            max-width: 800px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f0f0f0;
        }

        input[type="text"], input[type="submit"] {
            padding: 10px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .actions form {
            display: inline-block;
        }

        .actions input[type="submit"] {
            width: auto;
            background-color: #ff6347;
            color: white;
            cursor: pointer;
        }

        .actions input[type="submit"]:hover {
            background-color: #e5533d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage API Keys</h2>

        <!-- Create New API Key -->
        <form method="POST">
            <label for="description">Description:</label>
            <input type="text" name="description" id="description" required>
            <input type="submit" name="create_key" value="Create New API Key">
        </form>

        <h3>Existing API Keys</h3>
        <table>
            <tr>
                <th>API Key</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['api_key']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td><?= $row['is_active'] ? 'Active' : 'Disabled' ?></td>
                    <td class="actions">
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="api_key" value="<?= $row['api_key'] ?>">
                            <input type="submit" name="action" value="disable" <?= !$row['is_active'] ? 'disabled' : '' ?>>
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="api_key" value="<?= $row['api_key'] ?>">
                            <input type="submit" name="action" value="delete">
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>
