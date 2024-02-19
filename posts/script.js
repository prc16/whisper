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

function displayPosts(posts) {
    const postsContainer = document.getElementById('postsContainer');

    // Check if the posts container exists
    if (!postsContainer) {
        console.error("Posts container not found");
        return;
    }

    // Clear the contents of the posts container
    while (postsContainer.firstChild) {
        postsContainer.removeChild(postsContainer.firstChild);
    }

    posts.forEach(post => {
        // Create a new div element for each post
        const postElement = document.createElement('div');
        postElement.className = 'post';

        // Set user_id and content using textContent to prevent HTML injection
        const h2 = document.createElement('h2');
        h2.textContent = post.user_id;
        postElement.appendChild(h2);

        const pContent = document.createElement('p');
        pContent.textContent = post.content;
        postElement.appendChild(pContent);

        const pVotes = document.createElement('p');
        pVotes.textContent = 'Votes: ' + post.votes;
        postElement.appendChild(pVotes);

        const btnUpvote = document.createElement('button');
        btnUpvote.textContent = 'Upvote';
        btnUpvote.style.backgroundColor = post.vote_type === 'upvote' ? 'orange' : 'white';
        btnUpvote.addEventListener('click', () => vote('upvote', post.post_id));
        postElement.appendChild(btnUpvote);

        const btnDownvote = document.createElement('button');
        btnDownvote.textContent = 'Downvote';
        btnDownvote.style.backgroundColor = post.vote_type === 'downvote' ? 'orange' : 'white';
        btnDownvote.addEventListener('click', () => vote('downvote', post.post_id));
        postElement.appendChild(btnDownvote);

        // Add the post element to the posts container
        postsContainer.appendChild(postElement);
    });
}


function vote(type, postId) {
    // Validate type parameter
    if (type !== 'upvote' && type !== 'downvote') {
        console.error('Invalid vote type');
        return;
    }

    // Validate postId parameter
    if (!postId || typeof postId !== 'string' || postId.length !== 16) {
        console.error('Invalid postId');
        return;
    }

    // Create data string for the vote action
    const data = `action=${encodeURIComponent(type)}&postId=${encodeURIComponent(postId)}`;
    makeRequest('POST', '../posts/server.php', data, function (status, responseText) {
        if (status === 200) {
            try {
                const posts = JSON.parse(responseText);
                displayPosts(posts); // Display the updated posts
            } catch (error) {
                console.error('Error parsing response:', error);
            }
        } else {
            console.error(`Error: Status ${status}`);
            // Handle other status codes if needed
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
