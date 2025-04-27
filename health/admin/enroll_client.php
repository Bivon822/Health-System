<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "health_system";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get clients
$clients = $conn->query("SELECT id, name FROM clients");

// Get programs
$programs = $conn->query("SELECT id, name FROM programs");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enroll Client</title>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f7f9fc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-box {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 700px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        select, input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background-color: blue;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
<?php include 'sidebar.php'; ?>

<div class="form-box">
    <h2>Enroll Client to Programs</h2>

    <form id="enrollForm" method="POST">
        <label>Select Client:</label>
        <select name="client_id" id="clientSelect" required>
            <option value="">-- Select Client --</option>
            <?php while ($c = $clients->fetch_assoc()): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label>Select Programs:</label>
        <select name="program_ids[]" id="programsSelect" multiple size="5" required>
            <?php while ($p = $programs->fetch_assoc()): ?>
                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
            <?php endwhile; ?>
        </select>

        <input type="submit" value="Enroll Client">
    </form>
</div>

<script>
$(document).ready(function() {
    $('#enrollForm').submit(function(e) {
        e.preventDefault(); // prevent default form submission

        var formData = $(this).serialize();

        var clientName = $("#clientSelect option:selected").text(); // Get selected client name
        var selectedPrograms = $("#programsSelect option:selected").length; // Count selected programs

        if (selectedPrograms === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Programs Selected',
                text: 'Please select at least one program!',
            });
            return;
        }

        $.ajax({
            type: 'POST',
            url: 'process_enrollment.php',
            data: formData,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Enrollment Successful!',
                    html: '<b>' + clientName + '</b> has been enrolled into <b>' + selectedPrograms + '</b> program(s)!',
                    timer: 3000,
                    showConfirmButton: false
                });

                $('#enrollForm')[0].reset(); // Clear the form
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Enrollment Failed!',
                    text: 'Something went wrong. Please try again.',
                });
            }
        });
    });
});
</script>

</body>
</html>
