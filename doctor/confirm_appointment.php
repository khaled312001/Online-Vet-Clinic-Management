<?php
session_start();
include '../backend/database.php'; // Database connection

// Ensure user is a doctor
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_role'] !== 'doctor') {
    header('Location: ../public/login.html?error=unauthorized');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['appointment_id'])) {
    $appointment_id = intval($_POST['appointment_id']);
    
    // Update appointment status
    $query = "UPDATE appointments SET status = 'confirmed' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $appointment_id);
    if ($stmt->execute()) {
        header('Location: appointments.php?success=confirmed');
        exit();
    } else {
        header('Location: appointments.php?error=db_error');
        exit();
    }
}

header('Location: appointments.php?error=invalid_request');
exit();
