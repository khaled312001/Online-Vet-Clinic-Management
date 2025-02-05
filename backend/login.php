<?php
session_start();
include 'database.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        header('Location: /VetDirect/public/login.php?error=empty_fields');
        exit();
    }

    // Fetch user data from database
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $hashed_password = hash('sha256', $password);

        // Verify hashed password
        if ($hashed_password === $user['password']) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];

            // Correct Redirect Based on Role
            switch ($user['role']) {
                case 'admin':
                    header('Location: /VetDirect/admin/dashboard.php');
                    exit();
                case 'doctor':
                    header('Location: /VetDirect/doctor/dashboard.php');
                    exit();
                default:
                    header('Location: /VetDirect/user/profile.php');
                    exit();
            }
        } else {
            header('Location: /VetDirect/public/login.php?error=wrong_password');
            exit();
        }
    } else {
        header('Location: /VetDirect/public/login.php?error=user_not_found');
        exit();
    }
}

// Prevent direct access
header('Location: /VetDirect/public/login.php?error=access_denied');
exit();
?>
