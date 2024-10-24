<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = $_POST['event_id'];

    if (!empty($event_id)) {
        $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
        $stmt->bind_param("i", $event_id);

        if ($stmt->execute()) {
            header("Location: dashboard.php?message=Event+deleted+successfully");
            exit();
        } else {
            echo "Error: Could not delete event. Please try again later.";
        }
        
        $stmt->close();
    } else {
        echo "Invalid event ID.";
    }
} else {
    header("Location: dashboard.php");
    exit();
}

$conn->close();
?>
