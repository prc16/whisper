const postsFeedContainer = document.getElementById('postsFeedContainer');

// Function to display posts
function displayPosts(posts) {
    if (!postsFeedContainer) {
        console.error("Posts container not found");
        return;
    }
    postsFeedContainer.innerHTML = '';
    posts.forEach(post => {
        const postElement = document.createElement('div');
        postElement.className = 'post';
        postElement.innerHTML = `
            <div class="displayPostContainer">
                <div id="displayPostContainerPart1">
                    <img src="${post.profile_file_path}" class="profile-picture" alt="">
                </div>
                <div id="displayPostContainerPart2">
                    <h2>${post.username}</h2>
                    <p>${post.content}</p>
                    <div id="displayPostMediaPreview">
                        ${post.post_file_path.endsWith('.mp4') || post.post_file_path.endsWith('.webm') ?
                `<video controls class="video-preview">
                                <source src="${post.post_file_path}" type="video/mp4">
                                <source src="${post.post_file_path}" type="video/webm">
                                Your browser does not support the video tag.
                            </video>` :
                `<img src="${post.post_file_path}" alt="" class="image-preview">`}
                    </div>
                    <div id="displayPostButtons">
                        <p class="voteCount"> Votes: ${post.vote_count}</p>
                        <button class="vote-btn btn ${post.vote_type === 'upvote' ? '' : 'btn-alt'}" data-id="${post.post_id}" data-type="upvote">Upvote</button>
                        <button class="vote-btn btn ${post.vote_type === 'downvote' ? '' : 'btn-alt'}" data-id="${post.post_id}" data-type="downvote">Downvote</button>
                    </div>
                </div>
            </div>
        `;
        postsFeedContainer.appendChild(postElement);
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

        const formData = new FormData();
        formData.append('action', type);
        formData.append('post_id', postId);

        fetch('../posts/posts.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.status === 200) {
                return response.json(); // Parse JSON response
            } else if (response.status === 401) {
                alert('You need to log in to vote.');
                throw new Error('Unauthorized');
            } else {
                throw new Error(`Error: Status ${response.status}`);
            }
        })
        .then(posts => {
            // Assuming posts is an array of posts
            displayPosts(posts); // Call displayPosts with the posts array
        })
        .catch(error => {
            console.error('Error:', error.message);
        });
    }
}


// Event listener for voting
document.addEventListener('click', vote);

function fetchPosts() {
    fetch('../posts/posts.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(posts => {
            displayPosts(posts);
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });
}

// Fetch posts initially
fetchPosts();

// Fetch posts every 5 seconds
// setInterval(fetchPosts, 5000);


// Function to handle the 'updateNeeded' event
function handleUpdateEvent() {
    fetchPosts();
}

// Add event listener for 'update' event on displayPosts div
document.addEventListener("DOMContentLoaded", function () {
    postsFeedContainer.addEventListener("updateNeeded", handleUpdateEvent);
});
