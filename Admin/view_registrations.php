<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
include '../db.php';

$result = $conn->query("SELECT events.name AS event_name, users.name AS username, users.email, registrations.registration_date FROM registrations JOIN events ON registrations.event_id = events.id JOIN users ON registrations.user_id = users.id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrations</title>
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
        p {
            font-size: 18px;
            color: #555;
            text-align: center;
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
        <h1>Registrations</h1>
        <form method='POST' action='export_registrations.php'>
            <button class="button" type='submit'>Export to CSV</button>
        </form>
        <?php
        if ($result && $result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Event Name</th><th>User Name</th><th>Email</th><th>Registration Date</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['event_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . $row['registration_date'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No registrations found.</p>";
        }
        ?>
        <a href="dashboard.php" class="button">Back to Dashboard</a>
    </div>
</body>
</html>
