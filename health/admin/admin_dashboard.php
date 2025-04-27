<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$admin_name = $_SESSION['admin_name'] ?? 'Admin';

// Database connection
$conn = new mysqli("localhost", "root", "", "health_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Count clients
$clients_result = $conn->query("SELECT COUNT(*) AS total_clients FROM clients");
$clients = $clients_result->fetch_assoc()['total_clients'] ?? 0;

// Count programs
$programs_result = $conn->query("SELECT COUNT(*) AS total_programs FROM programs");
$programs = $programs_result->fetch_assoc()['total_programs'] ?? 0;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <style>
   body {
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6f9;
    margin: 0;
    padding: 0;
    display: flex;
    min-height: 100vh;
}

.sidebar {
    width: 240px;
    background: #007bff;
    color: #fff;
    padding: 20px;
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
}

.main-content {
    padding: 20px;
    width: 100%;
    background: #f4f6f9;
    display: flex;
    flex-direction: column;
    gap: 20px;
    margin-left: 0; /* No margin now */
    margin-right: 0;
    padding-left: 250px; /* To avoid the content overlapping the sidebar */
}

.navbar {
    background: #ffffff;
    padding: 15px 25px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 10px;
}

.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.card {
    background: #ffffff;
    padding: 30px 20px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    text-align: center;
    transition: transform 0.3s;
}

.card:hover {
    transform: translateY(-5px);
}

.card h3 {
    font-size: 28px;
    margin-bottom: 10px;
    color: #007bff;
}

.card p {
    font-size: 18px;
    color: #666;
}

.navbar-left {
    font-size: 24px;
    font-weight: bold;
    color: #007bff;
}

.navbar-right {
    display: flex;
    align-items: center;
    gap: 15px;
}

.navbar-right span {
    font-size: 16px;
    color: #333;
}

.logout-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 22px;
    color: #dc3545;
}

.logout-btn:hover {
    color: #c82333;
}

</style>
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <div class="main-content">
        <div class="navbar">
            <div class="navbar-left">Dashboard</div>
            <div class="navbar-right">
                <span>Welcome, <?= htmlspecialchars($admin_name) ?> 👋</span>
                <button type="button" class="logout-btn" id="logoutBtn" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </div>
        </div>

        <div class="dashboard-cards">
            <div class="card">
                <h3><?= $clients ?></h3>
                <p>Total Clients</p>
            </div>

            <div class="card">
                <h3><?= $programs ?></h3>
                <p>Total Programs</p>
            </div>
        </div>
    </div>

    <!-- SweetAlert for Login Success -->
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

    <!-- SweetAlert Logout Confirmation -->
    <script>
    document.getElementById('logoutBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, logout'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'logout.php';
            }
        })
    });
    </script>
</body>

