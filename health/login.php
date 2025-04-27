<?php
session_start();
include('db.php'); // Ensure db.php is correctly set up to connect to your database

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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Client Login | Dynamic Landing Page</title>
  <!-- SweetAlert2 CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body, html {
      height: 100%;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f5f7fa;
    }

    .container {
      display: flex;
      height: 100vh;
      overflow: hidden;
    }

    .image-area {
      flex: 2;
      position: relative;
    }

    .image-area img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: opacity 1s ease-in-out;
      position: absolute;
      top: 0;
      left: 0;
    }

    .overlay-text {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      color: #fff;
      text-align: center;
      font-size: 2.8rem;
      font-weight: bold;
      background: rgba(0, 0, 0, 0.4);
      padding: 25px 45px;
      border-radius: 15px;
      z-index: 2;
    }

    .login-area {
      flex: 1;
      background-color: #fff;
      padding: 60px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      box-shadow: -5px 0 15px rgba(0, 0, 0, 0.05);
      z-index: 10;
    }

    .login-area h2 {
      font-size: 28px;
      color: #333;
      margin-bottom: 10px;
    }

    .login-area p {
      color: #777;
      margin-bottom: 30px;
    }

    .login-area input {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
    }

    .login-area button {
      width: 100%;
      padding: 12px;
      background-color: #007bff;
      border: none;
      color: white;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .login-area button:hover {
      background-color: #0056b3;
    }
    .signup-link a {
      color: #007bff;
      text-decoration: none;
      font-weight: bold;
      transition: color 0.3s;
    }

    .signup-link a:hover {
      color: #0056b3;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }
      .image-area {
        display: none;
      }
      .login-area {
        flex: none;
        width: 100%;
        padding: 40px 20px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- LEFT: Image section with overlay text -->
    <div class="image-area">
      <img src="img/h1.jpeg" id="sliderImage" alt="Landing Image"/>
      <div class="overlay-text">Welcome to Health System</div>
    </div>

    <!-- RIGHT: Login form only -->
    <div class="login-area">
      <h2>Welcome Back</h2>
      <p>Please log in to your account</p>
      <form id="loginForm">
        <input type="email" id="email" name="email" placeholder="Email" required />
        <input type="password" id="password" name="password" placeholder="Password" required />
        <button type="submit">Login</button>
      </form>
      <div class="signup-link">
        Don't have an account? <a href="client_reigistration.php">Create one</a>
      </div>
    </div>
  </div>

  <script>
    // Image slider only for landing page images in img/ folder
    const images = ['img/p1.jpg', 'img/p3.jpg', 'img/p4.jpg', 'img/p5.jpg'];
    let index = 0;
    const imgElement = document.getElementById('sliderImage');

    // Preload images
    const preloadedImages = [];
    images.forEach(src => {
      const img = new Image();
      img.src = src;
      preloadedImages.push(img);
    });

    // Change image with fade
    function changeImage() {
      imgElement.style.opacity = 0;
      setTimeout(() => {
        index = (index + 1) % images.length;
        imgElement.src = images[index];
        imgElement.style.opacity = 1;
      }, 500);
    }

    setInterval(changeImage, 5000);

    // Login with SweetAlert2 and real database check
    document.getElementById('loginForm').addEventListener('submit', function (e) {
      e.preventDefault();
      const email = document.getElementById('email').value.trim();
      const password = document.getElementById('password').value.trim();

      fetch('login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === "success") {
          Swal.fire({
            icon: 'success',
            title: 'Login Successful',
            text: data.message,
            confirmButtonColor: '#007bff'
          }).then(() => {
            window.location.href = 'dashboard.php'; // Redirect to dashboard after successful login
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Login Failed',
            text: data.message,
            confirmButtonColor: '#d33'
          });
        }
      })
      .catch(error => {
        console.error('Error:', error);
      });
    });
  </script>
</body>
</html>
