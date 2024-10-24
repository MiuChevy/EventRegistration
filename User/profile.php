<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
include '../db.php';
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$reg_stmt = $conn->prepare("SELECT events.name, registrations.registration_date FROM registrations JOIN events ON registrations.event_id = events.id WHERE registrations.user_id = ?");
$reg_stmt->bind_param("i", $user_id);
$reg_stmt->execute();
$reg_result = $reg_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <title>User Profile</title>
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
            max-width: 600px;
            width: 100%;
            animation: fadeIn 0.6s ease;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .profile-info {
            margin-bottom: 30px;
        }
        .profile-info p {
            font-size: 18px;
            color: #555;
            margin-bottom: 10px;
        }
        h2 {
            color: #333;
            margin-bottom: 10px;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        ul li {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }
        ul li:hover {
            background-color: #eef;
        }
        .button {
            display: inline-block;
            margin-top: 20px;
            background-color: #74ebd5;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            transition: background-color 0.3s ease;
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
            h1 {
                font-size: 24px;
            }
            .profile-info p {
                font-size: 16px;
            }
            ul li {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Profile</h1>
        <div class="profile-info">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        </div>
        <h2>Event Registration History</h2>
        <?php
        if ($reg_result->num_rows > 0) {
            echo "<ul>";
            while ($row = $reg_result->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($row['name']) . " - Registered on: " . $row['registration_date'] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No registrations found.</p>";
        }
        ?>
        <a href="edit_profile.php" class="button">Edit Profile</a>
        <a href="events.php" class="button">Back to Main Page</a>
    </div>
</body>
</html>
