<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
include '../db.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $event_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($event = $result->fetch_assoc()) {
        $count_stmt = $conn->prepare("SELECT COUNT(*) as total_registrations FROM registrations WHERE event_id = ?");
        $count_stmt->bind_param("i", $event_id);
        $count_stmt->execute();
        $count_result = $count_stmt->get_result();
        $registration_count = $count_result->fetch_assoc()['total_registrations'];
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <title>Event Details</title>
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
            text-align: center;
            animation: fadeIn 0.6s ease;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        p {
            color: #555;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .button {
            padding: 12px 20px;
            background-color: #74ebd5;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
            display: inline-block;
        }
        .button:hover {
            background-color: #ACB6E5;
        }
        .button.red {
            background-color: #f44336;
        }
        .button.red:hover {
            background-color: #d32f2f;
        }
        .button.blue {
            background-color: #2196F3;
        }
        .button.blue:hover {
            background-color: #1976D2;
        }
        .button.green {
            background-color: #4CAF50;
        }
        .button.green:hover {
            background-color: #388E3C;
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
            p {
                font-size: 16px;
            }
            .button {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($event['name']); ?></h1>
        <p><?php echo htmlspecialchars($event['description']); ?></p>
        <p>Date: <?php echo $event['date']; ?> Time: <?php echo $event['time']; ?></p>
        <p>Location: <?php echo htmlspecialchars($event['location']); ?></p>
        <p>Registrations: <?php echo $registration_count; ?> / <?php echo $event['max_participants']; ?></p>
        <form method='POST' action='delete_event.php' onsubmit='return confirm("Are you sure you want to delete this event?");'>
            <input type='hidden' name='event_id' value='<?php echo $event['id']; ?>'>
            <button type='submit' class="button red">Delete Event</button>
        </form>
        <a href='edit_event.php?id=<?php echo $event['id']; ?>' class="button blue">Edit Event</a>
        <a href='dashboard.php?id=<?php echo $event['id']; ?>' class="button green">Back To Dashboard</a>
    </div>
</body>
</html>
<?php
        $count_stmt->close();
    } else {
        echo "Event not found.";
    }
    $stmt->close();
} else {
    echo "No event ID provided.";
}
?>
