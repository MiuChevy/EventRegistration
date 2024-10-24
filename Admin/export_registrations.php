<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
include '../db.php';

$result = $conn->query("SELECT events.name AS event_name, users.name AS username, users.email, registrations.registration_date FROM registrations JOIN events ON registrations.event_id = events.id JOIN users ON registrations.user_id = users.id");

if ($result && $result->num_rows > 0) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=registrations.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, array('Event Name', 'User Name', 'Email', 'Registration Date'));
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, array($row['event_name'], $row['username'], $row['email'], $row['registration_date']));
    }
    fclose($output);
    exit();
} else {
    echo "No registrations found.";
}
?>
