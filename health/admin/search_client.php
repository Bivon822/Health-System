<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_name = $_SESSION['admin_name'] ?? 'Admin';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Client</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f2f6fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        form {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 20px;
            width: 600px;
        }

        input[type="text"] {
            padding: 10px;
            width: 70%;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0069d9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f0f0f0;
        }
    </style>
    

</head>
<body>
<?php include 'sidebar.php'; ?>

                <!-- SweetAlert -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <?php if (isset($_SESSION['login_success'])): ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Login Successful!',
                    text: 'Welcome, <?= htmlspecialchars($admin_name) ?> 👋',
                    showConfirmButton: false,
                    timer: 2500
                });
            </script>
            <?php unset($_SESSION['login_success']); ?>
            <?php endif; ?>
            <!--end of SweetAlert -->

           
        
    <div class="container">
        <h2>Search Client</h2>
        <form method="GET">
            <input type="text" name="query" placeholder="Enter name or email..." required>
            <input type="submit" value="Search">
        </form>

        <?php
        if (isset($_GET['query'])) {
            $conn = new mysqli("localhost", "root", "", "health_system");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $query = $conn->real_escape_string($_GET['query']);
            $sql = "SELECT * FROM clients WHERE name LIKE '%$query%' OR email LIKE '%$query%'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Name</th><th>Email</th><th>Contact</th><th>View</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['contact']) . "</td>";
                    echo "<td><a href='view_client.php?id=" . $row['id'] . "'>View Profile</a></td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No matching clients found.</p>";
            }

            $conn->close();
        }
        ?>
    </div>
</body>
</html>
