<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
include '../db.php';
$event_id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($event = $result->fetch_assoc()) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = htmlspecialchars($_POST['name']);
        $description = htmlspecialchars($_POST['description']);
        $date = $_POST['date'];
        $time = $_POST['time'];
        $location = htmlspecialchars($_POST['location']);
        
        $update_stmt = $conn->prepare("UPDATE events SET name = ?, description = ?, date = ?, time = ?, location = ? WHERE id = ?");
        $update_stmt->bind_param("sssssi", $name, $description, $date, $time, $location, $event_id);
        
        if ($update_stmt->execute()) {
            header("Location: event_detail.php?id=" . $event_id); // Redirect ke halaman detail event
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
        $update_stmt->close();
    }
} else {
    echo "Event not found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <title>Edit Event</title>
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
            max-width: 600px;
            width: 100%;
            animation: fadeIn 0.6s ease;
            overflow: hidden;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-size: 16px;
            color: #555;
            margin-bottom: 8px;
        }
        input, textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            background-color: #f9f9f9;
            transition: border 0.3s ease, box-shadow 0.3s ease;
        }
        input:focus, textarea:focus {
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
        a {
            display: block;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            color: #74ebd5;
            font-size: 14px;
        }
        a:hover {
            color: #ACB6E5;
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
            button, input, textarea {
                font-size: 14px;
                padding: 10px;
            }
        }
        @media (max-width: 480px) {
            h1 {
                font-size: 22px;
            }
            input, textarea {
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
        <h1>Edit Event</h1>
        <form method="POST" action="edit_event.php?id=<?php echo $event_id; ?>">
            <label for="name">Event Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($event['name']); ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($event['description']); ?></textarea>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" value="<?php echo $event['date']; ?>" required>

            <label for="time">Time:</label>
            <input type="time" id="time" name="time" value="<?php echo $event['time']; ?>" required>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" required>

            <button type="submit">Save Changes</button>
        </form>
        <a href="event_detail.php?id=<?php echo $event_id; ?>">Back to Event Details</a>
    </div>
</body>
</html>
