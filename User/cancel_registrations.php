<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: ../index.php");
    exit();
}
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_id = $_POST['event_id'];
    $user_id = $_SESSION['user_id'];

    $check_stmt = $conn->prepare("SELECT * FROM registrations WHERE user_id = ? AND event_id = ?");
    $check_stmt->bind_param("ii", $user_id, $event_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    $is_registered = $check_stmt->num_rows > 0;
    $success = false;

    if ($is_registered) {
        $stmt = $conn->prepare("DELETE FROM registrations WHERE user_id = ? AND event_id = ?");
        $stmt->bind_param("ii", $user_id, $event_id);
        $success = $stmt->execute();
        $stmt->close();
    }

    $check_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Event Registration</title>
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
            max-width: 500px;
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
            margin-bottom: 20px;
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
            margin-top: 10px;
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
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($is_registered && $success) {
                echo "<h1>Successfully Canceled!</h1>";
                echo "<p>Your registration for the event has been canceled.</p>";
            } elseif ($is_registered) {
                echo "<h1>Error</h1>";
                echo "<p>There was an error processing your request. Please try again later.</p>";
            } else {
                echo "<h1>Not Registered</h1>";
                echo "<p>You are not registered for this event.</p>";
            }
        }
        ?>
        <form method="POST" action="events.php">
            <button type="submit">Back to Dashboard</button>
        </form>
    </div>
</body>
</html>
