// Function to handle AJAX requests
function makeRequest(method, url, data, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            callback(xhr.status, xhr.responseText);
        }
    };
    xhr.send(data);
}

// Function to display posts with updated styles for upvote/downvote buttons
function displayPosts(posts) {
    const postsContainer = document.getElementById('postsContainer');
    postsContainer.innerHTML = '';

    posts.forEach(post => {
        const postElement = document.createElement('div');
        postElement.className = 'post';
        postElement.innerHTML = `
            <h2>${post.title}</h2>
            <p>${post.content}</p>
            <p>Votes: ${post.votes}</p>
            <button onclick="vote('upvote', ${post.id})"
                style="background-color: ${post.vote_type === 'upvote' ? 'orange' : 'white'}">Upvote</button>
            <button onclick="vote('downvote', ${post.id})"
                style="background-color: ${post.vote_type === 'downvote' ? 'orange' : 'white'}">Downvote</button>
        `;
        postsContainer.appendChild(postElement);
    });
}

// Function to handle post creation and votes
function vote(type, postId) {
    // Check if user is logged in
    const userLoggedIn = true; // Replace with your authentication logic
    if (!userLoggedIn) {
        alert(`You need to log in to ${type === 'upvote' ? 'upvote' : 'downvote'} a post.`);
        return;
    }

    const data = `action=${type}&postId=${postId}`;
    makeRequest('POST', '../posts/server.php', data, function (status, responseText) {
        if (status === 200) {
            posts = JSON.parse(responseText);
            displayPosts(posts);
        } else if (status === 401) {
            alert(`You need to log in to ${type === 'upvote' ? 'upvote' : 'downvote'} a post.`);
        }
    });
}

// Function to create a new post
function createPost() {
    const postTitle = document.getElementById('postTitle').value;
    const postContent = document.getElementById('postContent').value;

    // Check if user is logged in
    const userLoggedIn = true; // Replace with your authentication logic
    if (!userLoggedIn) {
        alert('You need to log in to create a post.');
        return;
    }

    const data = `action=create&title=${encodeURIComponent(postTitle)}&content=${encodeURIComponent(postContent)}`;
    makeRequest('POST', '../posts/server.php', data, function (status, responseText) {
        if (status === 200) {
            posts = JSON.parse(responseText);
            displayPosts(posts);
        } else if (status === 401) {
            alert('You need to log in to create a post.');
        }
    });

    // Clear the form fields
    document.getElementById('postTitle').value = '';
    document.getElementById('postContent').value = '';
}

// Initial display of posts
makeRequest('GET', '../posts/server.php', null, function (status, responseText) {
    if (status === 200) {
        posts = JSON.parse(responseText);
        displayPosts(posts);
    }
});
