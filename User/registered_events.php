<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: ../index.php");
    exit();
}
include '../db.php';

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT events.name AS event_name, events.date, events.time, events.location FROM registrations JOIN events ON registrations.event_id = events.id WHERE registrations.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Registered Events</title>
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
            height: 100vh;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            animation: fadeIn 0.6s ease;
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
            background-color: #74ebd5;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            transition: background-color 0.3s ease;
            text-align: center;
            cursor: pointer;
            border: none;
            margin-top: 20px;
        }
        .button:hover {
            background-color: #ACB6E5;
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
        @media (max-width: 480px) {
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
        <h1>Your Registered Events</h1>
        <?php
        if ($result && $result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Event Name</th><th>Date</th><th>Time</th><th>Location</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['event_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>You have not registered for any events yet.</p>";
        }
        $stmt->close();
        $conn->close();
        ?>
        <form method="POST" action="events.php">
            <button class="button" type="submit">Back to Dashboard</button>
        </form>
    </div>
</body>
</html>
