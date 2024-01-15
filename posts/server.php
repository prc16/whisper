<?php
include '../config.php';

// Function to get all posts from the database
function getPosts() {
    global $conn;
    $sql = "SELECT * FROM posts";
    $result = $conn->query($sql);

    $posts = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
    }

    return $posts;
}

// Function to create a new post in the database
function createPost($title, $content) {
    global $conn;
    $title = $conn->real_escape_string($title);
    $content = $conn->real_escape_string($content);

    $sql = "INSERT INTO posts (title, content, votes) VALUES ('$title', '$content', 0)";
    $conn->query($sql);
}

// Function to upvote a post in the database
function upvotePost($postId) {
    global $conn;
    $postId = (int)$postId;

    $sql = "UPDATE posts SET votes = votes + 1 WHERE id = $postId";
    $conn->query($sql);
}

// Function to downvote a post in the database
function downvotePost($postId) {
    global $conn;
    $postId = (int)$postId;

    $sql = "UPDATE posts SET votes = votes - 1 WHERE id = $postId";
    $conn->query($sql);
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'create') {
            if (isset($_POST['title']) && isset($_POST['content'])) {
                createPost($_POST['title'], $_POST['content']);
            }
        } elseif ($_POST['action'] === 'upvote') {
            if (isset($_POST['postId'])) {
                upvotePost($_POST['postId']);
            }
        } elseif ($_POST['action'] === 'downvote') {
            if (isset($_POST['postId'])) {
                downvotePost($_POST['postId']);
            }
        }
    }
}

// Return posts as JSON
header('Content-Type: application/json');
echo json_encode(getPosts());

$conn->close();
?>
