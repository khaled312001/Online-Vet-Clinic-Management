<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || !isset($_SESSION['user_id'])) {
    header('Location: ../public/login.php?error=not_logged_in');
    exit();
}

include '../backend/database.php'; // Database connection

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'User';

// Handle new post submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_post'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $query = "INSERT INTO forum_posts (user_id, title, content, date) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iss', $user_id, $title, $content);
    $stmt->execute();
    header('Location: forum.php?success=post_created');
    exit();
}

// Fetch forum posts with likes and comments count
$query = "SELECT f.id, f.title, f.content, u.name AS author, u.avatar, f.date, 
                 (SELECT COUNT(*) FROM forum_likes WHERE post_id = f.id) AS like_count,
                 (SELECT COUNT(*) FROM forum_comments WHERE post_id = f.id) AS comment_count
          FROM forum_posts f
          JOIN users u ON f.user_id = u.id
          ORDER BY f.date DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();


$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Forum</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .forum-container {
            width: 60%;
            margin: auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .post-form {
            margin-bottom: 20px;
        }
        .post-form textarea {
            width: 100%;
            height: 100px;
            resize: none;
        }
        .forum-post {
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .forum-post h3 {
            margin: 0 0 10px;
        }
        .forum-post .meta {
            font-size: 0.9em;
            color: #777;
        }
        .forum-post .like-btn, .forum-post .comment-btn {
            color: blue;
            text-decoration: underline;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
        }
        .forum-post .like-btn:hover, .forum-post .comment-btn:hover {
            color: darkblue;
        }
        .comments-section {
            display: none;
            margin-top: 10px;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        .comment {
            margin-bottom: 5px;
            font-size: 14px;
        }
        .comment strong {
            color: #333;
        }
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .post-header {
            display: flex;
            align-items: center;
        }
    </style>
    <script>
         <script>
         <?php
session_start();
include '../backend/database.php';

if (!isset($_SESSION['user_logged_in']) || !isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = intval($_POST['post_id']);

    // Check if user already liked the post
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

        // Get updated like count
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

        // Get updated like count
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

    </script>
    <script>
    function toggleLike(postId) {
        fetch('like.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'post_id=' + postId
        })
        .then(response => response.json())
        .then(data => {
            let likeBtn = document.getElementById('like-btn-' + postId);
            let likeCount = document.getElementById('like-count-' + postId);
            likeBtn.textContent = (data.status === 'liked') ? '👍 Liked' : '👍 Like';
            likeCount.textContent = data.like_count;
        })
        .catch(error => console.error('Error:', error));
    }

    function toggleComments(postId) {
        let section = document.getElementById('comments-' + postId);
        if (section) {
            section.style.display = (section.style.display === 'none' || section.style.display === '') ? 'block' : 'none';
        }
    }
</script>

</head>
<body>
    <header>
        <h1>Community Forum</h1>
        <nav>
            <a href="../public/index.php">Home Page</a>
            <a href="profile.php">Profile</a>
            <a href="pets.php">My Pets</a>
            <a href="appointments.php">Appointments</a>
            <a href="emergency.php">Emergency</a>
            <a href="forum.php">Forum</a>
            <a href="../backend/logout.php">Logout</a>
        </nav>
    </header>

    <div class="forum-container">
        <section>
            <h2>Start a Discussion</h2>
            <form action="" method="POST" class="post-form">
                <input type="text" name="title" placeholder="Title..." required>
                <textarea name="content" placeholder="What's on your mind?" required></textarea>
                <button type="submit" name="submit_post">Post</button>
            </form>
        </section>

        <section>
            <h2>Recent Discussions</h2>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                // Check if user liked the post
                $like_query = "SELECT COUNT(*) AS user_liked FROM forum_likes WHERE post_id = ? AND user_id = ?";
                $like_stmt = $conn->prepare($like_query);
                $like_stmt->bind_param('ii', $row['id'], $user_id);
                $like_stmt->execute();
                $like_result = $like_stmt->get_result();
                $like_data = $like_result->fetch_assoc();
                $user_liked = $like_data['user_liked'] ?? 0;
                ?>
                <div class="forum-post">
                    <div class="post-header">
                        <div>
                            <h3><?= htmlspecialchars($row['title']) ?></h3>
                            <p class="meta"><strong><?= htmlspecialchars($row['author']) ?></strong> • <?= htmlspecialchars($row['date']) ?></p>
                        </div>
                    </div>
                    <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>

                    <!-- Like & Comment Buttons -->
                    <div>
                        <span id="like-btn-<?= $row['id'] ?>" class="like-btn" onclick="toggleLike(<?= $row['id'] ?>)">
                            <?= ($user_liked > 0) ? '👍 Liked' : '👍 Like' ?>
                        </span>
                        <span id="like-count-<?= $row['id'] ?>"><?= $row['like_count'] ?></span> Likes

                        <span class="comment-btn" onclick="toggleComments(<?= $row['id'] ?>)">
                            💬 <span id="comment-count-<?= $row['id'] ?>"><?= $row['comment_count'] ?></span> Comments
                        </span>
                    </div>

                    <!-- Comments Section -->
                    <div class="comments-section" id="comments-<?= $row['id'] ?>" style="display: none;">
                        <h4>Comments</h4>
                        <form action="comment.php" method="POST">
                            <input type="hidden" name="post_id" value="<?= $row['id'] ?>">
                            <textarea name="comment" placeholder="Write a comment..." required></textarea>
                            <button type="submit">Comment</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </section>
    </div>
    <footer>
        <p>&copy; 2025 Veterinary Platform. All Rights Reserved.</p>
    </footer>
</body>
</html>
