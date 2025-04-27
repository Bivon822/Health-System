<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Responsive Admin Sidebar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- SweetAlert2 CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    /* Your same CSS */
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f4f7fc;
      margin: 0;
      padding: 0;
      transition: padding-left 0.3s ease;
    }

    .sidebar {
      height: 100vh;
      background-color: #007bff;
      padding: 20px 10px;
      color: white;
      position: fixed;
      top: 0;
      left: 0;
      width: 250px;
      transition: width 0.3s ease;
      overflow-x: hidden;
      z-index: 1000;
    }

    .sidebar.collapsed {
      width: 80px;
    }

    .sidebar h3 {
      margin-bottom: 30px;
      font-weight: bold;
      font-size: 1.5rem;
      white-space: nowrap;
      transition: opacity 0.3s;
    }

    .sidebar.collapsed h3 {
      opacity: 0;
    }

    .sidebar a {
      display: flex;
      align-items: center;
      color: white;
      text-decoration: none;
      margin-bottom: 20px;
      font-size: 1.1rem;
      padding: 10px;
      border-radius: 5px;
      white-space: nowrap;
      transition: background 0.3s, padding-left 0.3s;
    }

    .sidebar a:hover {
      background-color: #0056b3;
      padding-left: 15px;
    }

    .sidebar i {
      margin-right: 10px;
      font-size: 1.5rem;
    }

    .sidebar.collapsed i {
      margin: 0 auto;
    }

    .sidebar.collapsed span {
      display: none;
    }

    .content {
      padding: 20px;
      margin-left: 250px;
      transition: margin-left 0.3s ease;
    }

    .sidebar.collapsed ~ .content {
      margin-left: 80px;
    }

    .toggle-btn {
      position: fixed;
      top: 20px;
      left: 260px;
      background-color: #007bff;
      color: white;
      border: none;
      padding: 8px 12px;
      cursor: pointer;
      border-radius: 4px;
      z-index: 1100;
      transition: left 0.3s ease;
    }

    .sidebar.collapsed ~ .toggle-btn {
      left: 90px;
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 80px;
      }
      .sidebar.collapsed {
        width: 0;
      }
      .sidebar.collapsed ~ .content {
        margin-left: 0;
      }
      .content {
        margin-left: 80px;
      }
      .toggle-btn {
        left: 90px;
      }
    }
  </style>
</head>

<body>

<div class="sidebar" id="sidebar">
  <h3>Admin</h3>
  <a href="admin_dashboard.php"><i>🏠</i> <span>Dashboard</span></a>
  <a href="search_client.php"><i>🧑‍⚕️</i> <span>Search Clients</span></a>
  <a href="admin_register_client.php"><i>🧑</i> <span>Register Clients</span></a>
  <a href="enroll_client.php"><i>👨‍💼</i> <span>Enroll Clients</span></a>
  <a href="add_program.php"><i>📅</i> <span>Add Programs</span></a>
  <a href="programs_list.php"><i>📅</i> <span>Programs</span></a>
  <a href="#"><i>⚙️</i> <span>Settings</span></a>
  
  <!-- Make Logout a button to trigger SweetAlert -->
  <a href="#" id="logoutBtn"><i>🚪</i> <span>Logout</span></a>
</div>

<button class="toggle-btn" id="toggleBtn">☰</button>

<div class="content" id="content">
  <!-- Your content goes here -->
</div>

<script>
  const toggleBtn = document.getElementById('toggleBtn');
  const sidebar = document.getElementById('sidebar');
  const content = document.getElementById('content');

  toggleBtn.addEventListener('click', function () {
    sidebar.classList.toggle('collapsed');
  });

  // SweetAlert for Logout
  document.getElementById('logoutBtn').addEventListener('click', function (e) {
    e.preventDefault(); // Prevent default link behavior
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
        window.location.href = 'logout.php'; // Redirect if confirmed
      }
    });
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
