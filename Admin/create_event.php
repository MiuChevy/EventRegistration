<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
include '../db.php';

$event_created = false; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = htmlspecialchars($_POST['location']);
    $max_participants = $_POST['max_participants'];
    $status = $_POST['status'];
    
    $stmt = $conn->prepare("INSERT INTO events (name, description, date, time, location, max_participants, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $name, $description, $date, $time, $location, $max_participants, $status);
    
    if ($stmt->execute()) {
        $event_created = true; 
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
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
            max-width: 700px;
            width: 100%;
            animation: fadeIn 0.6s ease;
            overflow: hidden;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 28px;
        }
        label {
            display: block;
            font-size: 16px;
            color: #555;
            margin-bottom: 5px;
        }
        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            background-color: #f9f9f9;
            transition: border 0.3s ease, box-shadow 0.3s ease;
        }
        input:focus, textarea:focus, select:focus {
            border-color: #74ebd5;
            box-shadow: 0 0 10px rgba(116, 235, 213, 0.5);
            outline: none;
        }
        button {
            width: 100%;
            padding: 15px;
            background-color: #74ebd5;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #ACB6E5;
        }
        .notification {
            background-color: #e7f7f7;
            color: #333;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-bottom: 20px;
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
        @media (max-width: 768px) {
            .container {
                padding: 20px;
                width: 100%;
                max-width: 100%;
            }
            button, input, textarea, select {
                font-size: 14px;
                padding: 10px;
            }
            h1 {
                font-size: 24px;
            }
        }
        @media (max-width: 480px) {
            h1 {
                font-size: 22px;
            }
            input, textarea, select {
                padding: 10px;
                font-size: 13px;
            }
            button {
                padding: 12px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create Event</h1>
        <?php if ($event_created): ?>
            <div class="notification">
                Event created successfully.<br>
                <a href="dashboard.php" class="button">Back to Dashboard</a>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="create_event.php">
            <label>Event Name:</label>
            <input type="text" name="name" required>
            <label>Description:</label>
            <textarea name="description" required></textarea>
            <label>Date:</label>
            <input type="date" name="date" required>
            <label>Time:</label>
            <input type="time" name="time" required>
            <label>Location:</label>
            <input type="text" name="location" required>
            <label>Max Participants:</label>
            <input type="number" name="max_participants" required>
            <label>Status:</label>
            <select name="status">
                <option value="open">Open</option>
                <option value="closed">Closed</option>
                <option value="canceled">Canceled</option>
            </select>
            <button type="submit">Create Event</button>
        </form>
    </div>
</body>
</html>
