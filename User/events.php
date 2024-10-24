<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: ../index.php");
    exit();
}
include '../db.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name);
$stmt->fetch();
$stmt->close();

$result = $conn->query("SELECT * FROM events WHERE status = 'open'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Events</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            padding: 20px;
            background: linear-gradient(135deg, #74ebd5, #ACB6E5);
        }
        .container {
            max-width: 1000px;
            width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 28px;
            color: #333;
        }
        .header .nav-links {
            display: flex;
            align-items: center;
        }
        .header .nav-links a {
            margin-left: 20px;
            padding: 10px 20px;
            background-color: #74ebd5;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }
        .header .nav-links a:hover {
            background-color: #ACB6E5;
        }
        .event-card {
            background-color: #f9f9f9;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }
        .event-card:hover {
            transform: translateY(-10px);
        }
        .event-card h2 {
            font-size: 22px;
            margin-bottom: 15px;
            color: #333;
        }
        .event-card a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #74ebd5;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }
        .event-card a:hover {
            background-color: #ACB6E5;
        }
        .footer-links {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .footer-links a {
            color: #74ebd5;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .footer-links a:hover {
            color: #ACB6E5;
        }
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            .event-card {
                padding: 15px;
            }
            .event-card h2 {
                font-size: 18px;
            }
            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Hi, <?php echo htmlspecialchars($name); ?></h1>
            <div class="nav-links">
                <a href="profile.php">View Profile</a>
                <a href="../logout.php">Logout</a>
            </div>
        </div>
        <div class="event-list">
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<div class='event-card'>";
                echo "<h2>" . htmlspecialchars($row['name']) . "</h2>";
                echo "<a href='event_detail.php?id=" . $row['id'] . "'>View Details</a>";
                echo "</div>";
            }
            ?>
        </div>
        <div class="footer-links">
            <a href="registered_events.php">Cek Event yang Teregistrasi</a>
        </div>
    </div>
</body>
</html>
