<?php
include('db.php');
$result = $conn->query("SELECT * FROM programs");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Health Programs</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f8fc;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            background: #fff;
            margin-top: 60px;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            width: 600px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background: #eaf2ff;
            margin-bottom: 15px;
            padding: 15px 20px;
            border-left: 6px solid #007bff;
            border-radius: 6px;
        }

        li strong {
            display: block;
            color: #007bff;
            font-size: 18px;
            margin-bottom: 5px;
        }

        li span {
            color: #333;
            font-size: 15px;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

    <div class="container">
        <h2>Available Health Programs</h2>
        <ul>
            <?php while ($row = $result->fetch_assoc()): ?>
                <li>
                    <strong><?= htmlspecialchars($row['name']) ?></strong>
                    <span><?= htmlspecialchars($row['description']) ?></span>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>
</html>
