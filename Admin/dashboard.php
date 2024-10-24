<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
include '../db.php';

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../index.php");
    exit();
}

$result = $conn->query("SELECT * FROM events");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <title>Admin Dashboard</title>
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
            align-items: flex-start;
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            width: 100%;
            animation: fadeIn 0.6s ease;
            margin-top: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
        }
        .event-item {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }
        .event-item:hover {
            transform: translateY(-10px);
        }
        .event-item h2 {
            font-size: 22px;
            color: #333;
            margin-bottom: 10px;
        }
        .event-item a {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #74ebd5;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }
        .event-item a:hover {
            background-color: #ACB6E5;
        }
        .links {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .links a {
            padding: 12px 20px;
            background-color: #74ebd5;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }
        .links a:hover {
            background-color: #ACB6E5;
        }
        .logout-container {
            text-align: center;
            margin-top: 20px;
        }
        .logout-container form {
            display: inline-block;
        }
        .logout-container button {
            padding: 12px 20px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }
        .logout-container button:hover {
            background-color: #d32f2f;
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
            .links {
                flex-direction: column;
                align-items: center;
            }
            .links a {
                width: 100%;
                text-align: center;
                margin-bottom: 10px;
            }
        }
       
        @media (max-width: 480px) {
            h1 {
                font-size: 24px;
            }
            .event-item h2 {
                font-size: 18px;
            }
            .event-item a {
                font-size: 14px;
                padding: 8px;
            }
            .links a {
                font-size: 14px;
                padding: 10px;
            }
            .container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='event-item'>";
                if (isset($row['name'])) {
                    echo "<h2>" . htmlspecialchars($row['name']) . "</h2>";
                    echo "<a href='event_detail.php?id=" . $row['id'] . "'>View Details</a>";
                } else {
                    echo "<h2>Event name not found</h2>";
                }
                echo "</div>";
            }
        } else {
            echo "<p>No events found.</p>";
        }
        ?>
        <div class="links">
            <a href='create_event.php'>Create New Event</a>
            <a href='manage_users.php'>Manage Users</a>
            <a href='view_registrations.php'>View Registrations</a>
        </div>

        <div class="logout-container">
            <form method="POST" action="">
                <button type="submit" name="logout">Log Out</button>
            </form>
        </div>
    </div>
</body>
</html>
