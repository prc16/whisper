// Function to handle AJAX requests
function makeRequest(method, url, data, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.setRequestHeader('Content-Type', 'application/json; charset=utf-8');
    xhr.onreadystatechange = () => {
        if (xhr.readyState === 4) {
            callback(xhr.status, xhr.responseText);
        }
    };
    xhr.send(JSON.stringify(data));
}

// Function to display posts
function displayPosts(posts) {
    const postsContainer = document.getElementById('postsContainer');
    if (!postsContainer) {
        console.error("Posts container not found");
        return;
    }
    postsContainer.innerHTML = '';
    posts.forEach(post => {
        const postElement = document.createElement('div');
        postElement.className = 'post';
        postElement.innerHTML = `
            <h2>${post.username}</h2>
            <p>${post.content}</p>
            <p>Votes: ${post.votes}</p>
            <button class="vote-btn" data-id="${post.post_id}" data-type="upvote" style="background-color: ${post.vote_type === 'upvote' ? 'orange' : 'white'}">Upvote</button>
            <button class="vote-btn" data-id="${post.post_id}" data-type="downvote" style="background-color: ${post.vote_type === 'downvote' ? 'orange' : 'white'}">Downvote</button>
        `;
        postsContainer.appendChild(postElement);
    });
}

// Function to handle voting
function vote(event) {
    const { target } = event;
    if (target.classList.contains('vote-btn')) {
        const type = target.dataset.type;
        const postId = target.dataset.id;
        if (type !== 'upvote' && type !== 'downvote') {
            console.error('Invalid vote type');
            return;
        }
        if (!postId || postId.length !== 16) {
            console.error('Invalid postId');
            return;
        }
        makeRequest('POST', '../posts/posts.php', { action: type, post_id: postId }, (status, responseText) => {
            if (status === 200) {
                try {
                    const posts = JSON.parse(responseText);
                    displayPosts(posts);
                } catch (error) {
                    console.error('Error parsing response:', error);
                }
            } else if (status === 401) {
                alert('You need to log in to vote.');
            } else {
                console.error(`Error: Status ${status}`);
            }
        });
    }
}

// Function to create a new post
function createPost() {
    const postContent = document.getElementById('postContent').value.trim();
    if (!postContent) {
        alert('Post content cannot be empty');
        return;
    }
    makeRequest('POST', '../posts/posts.php', { action: 'create', content: postContent }, (status, responseText) => {
        if (status === 200) {
            const posts = JSON.parse(responseText);
            displayPosts(posts);
        } else if (status === 401) {
            alert('You need to log in to create a post.');
        }
    });
    document.getElementById('postContent').value = '';
}

// Event listener for voting
document.addEventListener('click', vote);

// Initial display of posts
makeRequest('GET', '../posts/posts.php', null, (status, responseText) => {
    if (status === 200) {
        const posts = JSON.parse(responseText);
        displayPosts(posts);
    }
});
