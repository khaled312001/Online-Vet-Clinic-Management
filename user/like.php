<?php
session_start();
include '../backend/database.php'; // Database connection

if (!isset($_SESSION['user_logged_in']) || !isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = intval($_POST['post_id']);

    // Check if user has already liked the post
    $check_query = "SELECT * FROM forum_likes WHERE post_id = ? AND user_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param('ii', $post_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Unlike the post
        $delete_query = "DELETE FROM forum_likes WHERE post_id = ? AND user_id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param('ii', $post_id, $user_id);
        $stmt->execute();

        // Return updated like count
        $count_query = "SELECT COUNT(*) AS like_count FROM forum_likes WHERE post_id = ?";
        $stmt = $conn->prepare($count_query);
        $stmt->bind_param('i', $post_id);
        $stmt->execute();
        $count_result = $stmt->get_result();
        $count = $count_result->fetch_assoc()['like_count'];

        echo json_encode(['status' => 'unliked', 'like_count' => $count]);
    } else {
        // Insert new like
        $insert_query = "INSERT INTO forum_likes (post_id, user_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param('ii', $post_id, $user_id);
        $stmt->execute();

        // Return updated like count
        $count_query = "SELECT COUNT(*) AS like_count FROM forum_likes WHERE post_id = ?";
        $stmt = $conn->prepare($count_query);
        $stmt->bind_param('i', $post_id);
        $stmt->execute();
        $count_result = $stmt->get_result();
        $count = $count_result->fetch_assoc()['like_count'];

        echo json_encode(['status' => 'liked', 'like_count' => $count]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
