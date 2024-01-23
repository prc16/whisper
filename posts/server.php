<?php
include '../config.php';

// Function to get all posts from the database
function getPosts()
{
    global $conn;
    $sql = "SELECT * FROM posts";
    $result = $conn->query($sql);

    $posts = array();
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }

    return $posts;
}

// Function to create a new post in the database
function createPost($title, $content)
{
    global $conn;

    // Retrieve user_id from session variable
    session_start();
    $user_id = $_SESSION['user_id'];

    $title = $conn->real_escape_string($title);
    $content = $conn->real_escape_string($content);

    $sql = "INSERT INTO posts (title, content, votes, user_id) VALUES ('$title', '$content', 0, '$user_id')";
    $conn->query($sql);
}

// Function to update post votes in the database
function updatePostVotes($postId, $voteIncrement)
{
    global $conn;
    $postId = (int)$postId;

    $sql = "UPDATE posts SET votes = votes + $voteIncrement WHERE id = $postId";
    $conn->query($sql);
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start(); // Start the session

    if (isset($_POST['action']) && isset($_SESSION['user_id'])) {
        // Check if user is logged in
        switch ($_POST['action']) {
            case 'create':
                if (isset($_POST['title']) && isset($_POST['content'])) {
                    createPost($_POST['title'], $_POST['content']);
                }
                break;
            case 'upvote':
            case 'downvote':
                if (isset($_POST['postId'])) {
                    updatePostVotes($_POST['postId'], ($_POST['action'] === 'upvote' ? 1 : -1));
                }
                break;
        }
    } else {
        // Handle unauthorized access (e.g., redirect to login page or return an error)
        header('HTTP/1.0 401 Unauthorized');
        exit();
    }
}

// Return posts as JSON
header('Content-Type: application/json');
echo json_encode(getPosts());

$conn->close();
