<?php
include('db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO programs (name, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $description);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Program created successfully!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $_SESSION['error'] = "Error: " . $stmt->error;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    $stmt->close();
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Create Health Program</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #eef2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
    

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            width: 600px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            resize: none;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            border: none;
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message {
            text-align: center;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
        }

        .success {
            color: green;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .error {
            color: red;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

    <div class="container">
      
              <?php if (isset($_SESSION['success'])): ?>
          <script>
              Swal.fire({
                  icon: 'success',
                  title: 'Success',
                  text: '<?php echo $_SESSION["success"]; ?>',
                  confirmButtonColor: '#28a745'
              });
          </script>
          <?php unset($_SESSION['success']); endif; ?>

          <?php if (isset($_SESSION['error'])): ?>
          <script>
              Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: '<?php echo $_SESSION["error"]; ?>',
                  confirmButtonColor: '#dc3545'
              });
          </script>
          <?php unset($_SESSION['error']); endif; ?>


        <h2>Create Health Program</h2>
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Program Name" required>
            <textarea name="description" rows="4" placeholder="Program Description" required></textarea>
            <input type="submit" value="Create Program">
        </form>
    </div>
</body>
</html>
