// Function to display posts
function displayPosts(posts) {
    const postsFeedContainer = document.getElementById('postsFeedContainer');
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
                <a href="/u/${post.username}" class="profile_link"><img src="${post.profile_file_path}" class="profile-picture" alt=""></a>
            </div>
            <div id="displayPostContainerPart2">
                <a href="/u/${post.username}" class="username_link"><h2 class="profile-username">${post.username}</h2></a>
                <p>${post.post_text}</p>
                <div id="displayPostMediaPreview">
                <img src="${post.post_file_path}" alt="" class="image-preview">
                </div>
                <div id="displayPostButtons">
                    
                    <div class="voteCount">Votes: ${post.vote_count}</div>
                
                    <label for="upvote${post.post_id}" class="btn btn2 ${post.vote_type === 'upvote' ? 'btn-selected' : ''}" title="Upvote"><i class="fas fa-arrow-alt-up"></i></label>
                    <button id="upvote${post.post_id}" class="vote-btn hidden" data-id="${post.post_id}" data-type="upvote"></button>
    
                    
                    <label for="downvote${post.post_id}" class="btn btn2 ${post.vote_type === 'downvote' ? 'btn-selected' : ''}" title="Downvote"><i class="fas fa-arrow-alt-down"></i></label>
                    <button id="downvote${post.post_id}" class="vote-btn hidden" data-id="${post.post_id}" data-type="downvote"></button>
                </div>
            </div>
        </div>
    `;
        postsFeedContainer.appendChild(postElement);
    });
}

// Function to handle voting
function vote(event) {
    const {
        target
    } = event;
    if (target.classList.contains('vote-btn')) {
        const type = target.dataset.type;
        const postId = target.dataset.id;

        const formData = new FormData();
        formData.append('action', type);
        formData.append('post_id', postId);

        fetch('/whisper/posts/server.php', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (response.status === 200) {
                    return response.json();
                } else if (response.status === 401) {
                    alert('You need to log in to vote.');
                    throw new Error('Unauthorized');
                } else {
                    throw new Error(`Error: Status ${response.status}`);
                }
            })
            .then(posts => {
                // FINSHME: only update vote count
                displayPosts(posts);
            })
            .catch(error => {
                console.error('Error:', error.message);
            });
    }
}

function fetchPosts() {
    fetch('/whisper/posts/server.php')
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

// Function to handle the 'updateNeeded' event
function handleUpdateEvent() {
    fetchPosts();
}