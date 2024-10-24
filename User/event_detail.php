<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: ../index.php");
    exit();
}
include '../db.php';
$event_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
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
            animation: fadeIn 0.6s ease;
            text-align: center;
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

        button {
            padding: 12px 20px;
            background-color: #74ebd5;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #ACB6E5;
        }

        form {
            display: inline-block;
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

            button {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <?php
        if ($event = $result->fetch_assoc()) {
            echo "<h1>" . htmlspecialchars($event['name']) . "</h1>";
            echo "<p>" . htmlspecialchars($event['description']) . "</p>";
            echo "<p>Date: " . $event['date'] . " | Time: " . $event['time'] . "</p>";
            echo "<p>Location: " . htmlspecialchars($event['location']) . "</p>";

            $check_registration = $conn->prepare("SELECT * FROM registrations WHERE user_id = ? AND event_id = ?");
            $check_registration->bind_param("ii", $user_id, $event_id);
            $check_registration->execute();
            $registration_result = $check_registration->get_result();

            if ($registration_result->num_rows > 0) {
                echo "<form method='POST' action='cancel_registrations.php'>";
                echo "<input type='hidden' name='event_id' value='" . $event['id'] . "'>";
                echo "<button type='submit'>Cancel Registration</button>";
                echo "</form>";
            } else {
                echo "<form method='POST' action='register_event.php'>";
                echo "<input type='hidden' name='event_id' value='" . $event['id'] . "'>";
                echo "<button type='submit'>Register for this Event</button>";
                echo "</form>";
            }

            echo "<form method='POST' action='events.php'>";
            echo "<button type='submit'>Back To Dashboard</button>";
            echo "</form>";

            $check_registration->close();
        } else {
            echo "<p>Event not found.</p>";
        }
        ?>
    </div>

</body>
</html>