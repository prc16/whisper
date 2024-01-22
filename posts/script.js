// Dummy data for initial posts
let posts = [];

// Function to display posts
function displayPosts() {
    const postsContainer = document.getElementById('postsContainer');
    postsContainer.innerHTML = '';

    posts.forEach(post => {
        const postElement = document.createElement('div');
        postElement.className = 'post';
        postElement.innerHTML = `
            <h2>${post.title}</h2>
            <p>${post.content}</p>
            <p>Votes: ${post.votes}</p>
            <button onclick="upvote(${post.id})">Upvote</button>
            <button onclick="downvote(${post.id})">Downvote</button>
        `;
        postsContainer.appendChild(postElement);
    });
}

// Function to create a new post
function createPost() {
    const postTitle = document.getElementById('postTitle').value;
    const postContent = document.getElementById('postContent').value;

    // AJAX request to create a new post
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../posts/server.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            posts = JSON.parse(xhr.responseText);
            displayPosts();
        }
    };
    xhr.send(`action=create&title=${encodeURIComponent(postTitle)}&content=${encodeURIComponent(postContent)}`);

    // Clear the form fields
    document.getElementById('postTitle').value = '';
    document.getElementById('postContent').value = '';
}

// Function to upvote a post
function upvote(postId) {
    // AJAX request to upvote a post
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../posts/server.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            posts = JSON.parse(xhr.responseText);
            displayPosts();
        }
    };
    xhr.send(`action=upvote&postId=${postId}`);
}

// Function to downvote a post
function downvote(postId) {
    // AJAX request to downvote a post
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../posts/server.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            posts = JSON.parse(xhr.responseText);
            displayPosts();
        }
    };
    xhr.send(`action=downvote&postId=${postId}`);
}

// Initial display of posts
const xhr = new XMLHttpRequest();
xhr.open('GET', '../posts/server.php', true);
xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
        posts = JSON.parse(xhr.responseText);
        displayPosts();
    }
};
xhr.send();
