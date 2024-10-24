<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
include '../db.php';

$result = $conn->query("SELECT * FROM users WHERE role='user'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #74ebd5, #ACB6E5);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            width: 100%;
            animation: fadeIn 0.6s ease;
            overflow: auto;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        p {
            text-align: center;
            margin-bottom: 20px;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 16px;
            color: #555;
        }
        th {
            background-color: #f9f9f9;
            font-weight: 500;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .button {
            display: inline-block;
            margin-top: 10px;
            background-color: #74ebd5;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s ease;
            cursor: pointer;
            border: none;
            text-align: center;
        }
        .button:hover {
            background-color: #ACB6E5;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        .dashboard-link {
            display: block;
            text-align: center;
            margin-top: 20px;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            th, td {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Management</h1>
        <?php
        if (isset($_GET['delete'])) {
            if ($_GET['delete'] == 'success') {
                echo "<p style='color:green;'>User deleted successfully.</p>";
            } elseif ($_GET['delete'] == 'failed') {
                echo "<p style='color:red;'>Failed to delete user. Please try again.</p>";
            }
        }
        if ($result && $result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Name</th><th>Email</th><th>Actions</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td><a href='delete_user.php?id=" . $row['id'] . "' class='button' onclick=\"return confirm('Are you sure?');\">Delete User</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No users found.</p>";
        }
        ?>
        <div class="dashboard-link">
            <a href="dashboard.php" class="button">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
