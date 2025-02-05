<?php
session_start();
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pet_id = $_POST['pet_id'];
    $doctor_id = $_POST['doctor_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $query = "INSERT INTO appointments (pet_id, doctor_id, date, time) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iiss', $pet_id, $doctor_id, $date, $time);

    if ($stmt->execute()) {
        header('Location: ../user/appointments.php?success=appointment_booked');
        exit();
    } else {
        header('Location: ../user/appointments.php?error=appointment_failed');
        exit();
    }
}
?>
