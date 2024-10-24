<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

include '../db.php';

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        header("Location: manage_users.php?delete=success");
    } else {
        header("Location: manage_users.php?delete=failed");
    }

    $stmt->close();
} else {
    header("Location: manage_users.php");
    exit();
}
?>
