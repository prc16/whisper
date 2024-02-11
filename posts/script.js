// Function to handle AJAX requests
function makeRequest(method, url, data, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    // Callback function for state change
    xhr.onreadystatechange = function () {
        // Check if request is completed
        if (xhr.readyState === 4) {
            // Call the specified callback with status and response text
            callback(xhr.status, xhr.responseText);
        }
    };
    xhr.send(data); // Send the request with the provided data
}

// Function to display posts with updated styles for upvote/downvote buttons
function displayPosts(posts) {
    const postsContainer = document.getElementById('postsContainer');
    
    // Clear the contents of the posts container
    postsContainer.innerHTML = '';

    posts.forEach(post => {
        // Create a new div element for each post
        const postElement = document.createElement('div');
        postElement.className = 'post';
        postElement.innerHTML = `
            <h2>${post.user_id}</h2>
            <p>${post.content}</p>
            <p>Votes: ${post.votes}</p>
            <button onclick="vote('upvote', ${post.id})"
                style="background-color: ${post.vote_type === 'upvote' ? 'orange' : 'white'}">Upvote</button>
            <button onclick="vote('downvote', ${post.id})"
                style="background-color: ${post.vote_type === 'downvote' ? 'orange' : 'white'}">Downvote</button>
        `;

        // Add the post element to the posts container
        postsContainer.appendChild(postElement);
    });
}

// Function to handle post creation and votes
function vote(type, postId) {
    // Create data string for the vote action
    const data = `action=${type}&postId=${postId}`;
    makeRequest('POST', '../posts/server.php', data, function (status, responseText) {
        if (status === 200) {
            const posts = JSON.parse(responseText);
            displayPosts(posts); // Display the updated posts
        } else if (status === 401) {
            alert(`You need to log in to ${type === 'upvote' ? 'upvote' : 'downvote'} a post.`);
        }
    });
}

// Function to create a new post
function createPost() {
    const postContent = document.getElementById('postContent').value;

    // Check for empty postContent
    if (!postContent.trim()) {
        alert('Post content cannot be empty');
        return;
    }

    // Create data string for creating a new post
    const data = `action=create&content=${encodeURIComponent(postContent)}`;
    makeRequest('POST', '../posts/server.php', data, function (status, responseText) {
        if (status === 200) {
            const posts = JSON.parse(responseText);
            displayPosts(posts); // Display the updated posts
        } else if (status === 401) {
            alert('You need to log in to create a post.');
        }
    });

    // Clear the form fields
    document.getElementById('postContent').value = '';
}

// Initial display of posts
makeRequest('GET', '../posts/server.php', null, function (status, responseText) {
    if (status === 200) {
        const posts = JSON.parse(responseText);
        displayPosts(posts);
    }
});
