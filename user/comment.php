<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || !isset($_SESSION['user_id'])) {
    header('Location: ../public/login.php?error=not_logged_in');
    exit();
}

include '../backend/database.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id']) && isset($_POST['comment'])) {
    $post_id = intval($_POST['post_id']);
    $user_id = $_SESSION['user_id'];
    $comment = trim($_POST['comment']);

    // Validate input
    if (empty($comment)) {
        header("Location: forum.php?error=empty_comment");
        exit();
    }

    // Insert comment into the database
    $query = "INSERT INTO forum_comments (post_id, user_id, content, date) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iis', $post_id, $user_id, $comment);

    if ($stmt->execute()) {
        header("Location: forum.php?success=comment_posted#post-$post_id");
        exit();
    } else {
        header("Location: forum.php?error=comment_failed");
        exit();
    }
}

// Redirect to the forum if accessed directly
header('Location: forum.php');
exit();
?>
