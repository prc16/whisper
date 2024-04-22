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
<div class="displayPostContainer" id="post_${post.post_id}">
    <div class="displayPostContainerPart1">
        <a href="/u/${post.username}" class="profile_link"><img src="${post.profile_file_path}" class="profile-picture" alt=""></a>
    </div>
    <div class="displayPostContainerPart2">
        <a href="/u/${post.username}" class="username_link"><h2 class="profile-username">${post.username}</h2></a>
        <p id="text_${post.post_id}" class="${post.report_count > 0 ? 'blur-low' : ''}">${post.post_text}</p>
        <div class="displayPostMediaPreview">
            <img src="${post.post_file_path}" alt="" class="image-preview ${post.report_count > 0 ? 'blur-high' : ''}" id="media_${post.post_id}">
        </div>
        <div class="displayPostButtonsJustify">
            <div class="displayPostButtons">


                <label for="upvote_${post.post_id}" class="btn btn2 ${post.vote_type === 'upvote' ? 'btn-selected' : ''}" title="Upvote"><i class="fas fa-arrow-alt-up"></i></label>
                <button id="upvote_${post.post_id}" class="vote-btn hidden" data-id="${post.post_id}" data-type="upvote"></button>


                <label for="downvote_${post.post_id}" class="btn btn2 ${post.vote_type === 'downvote' ? 'btn-selected' : ''}" title="Downvote"><i class="fas fa-arrow-alt-down"></i></label>
                <button id="downvote_${post.post_id}" class="vote-btn hidden" data-id="${post.post_id}" data-type="downvote"></button>
                <div class="voteCount">Votes: ${post.vote_count}</div>
            </div>
            <div class="displayPostButtons2">
                    <label id="review_label_${post.post_id}" for="review_${post.post_id}" class="btn btn2" title="Review"><i class="fas fa-eye-slash"></i></label>
                    <button id="review_${post.post_id}" class="review-btn hidden" data-id="${post.post_id}" data-type="review"></button>
            </div>
        </div>
        <div id="inappropriate_${post.post_id}">
            <p class="errorMessage">This post has been flagged inappropriate!</p>
            <div class="displayPostButtons0">
                <div>
                    <div class="displayPostButtons3">
                        <label for="approve_${post.post_id}" class="btn btn2 ${post.report_type === 'approve' ? 'btn-selected' : ''}" title="Approve Report"><i class="fas fa-check"></i></label>
                        <button id="approve_${post.post_id}" class="vote-btn hidden" data-id="${post.post_id}" data-type="approve"></button>
                        <label for="disapprove_${post.post_id}" class="btn btn2 ${post.report_type === 'disapprove' ? 'btn-selected' : ''}" title="Dispprove Report"><i class="fas fa-times"></i></label>
                        <button id="disapprove_${post.post_id}" class="vote-btn hidden" data-id="${post.post_id}" data-type="disapprove"></button>
                    </div>
                </div>
                <div class="voteCount approveCount">Reports: ${post.report_count}</div>
            </div>
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

        fetch('/server/vote', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                // Successful vote
                postsFeedContainer.dispatchEvent(new Event('updateNeeded'));
            } else {
                // Parse JSON response
                return response.json().then(data => {
                    // Server returned an error, display the error message
                    alert(data.message);
                    console.log(data.message);
                });
            }
        })
        .catch(error => {
            console.error('There was a problem with your fetch operation:', error);
        });
    } else if (target.classList.contains('review-btn')) {
        const postId = target.dataset.id;
        const textContainer = document.getElementById(`text_${postId}`);
        const mediaContainer = document.getElementById(`media_${postId}`);
        const reviewLabel = document.getElementById(`review_label_${postId}`);

        if (reviewLabel.classList.contains('btn-selected')){
            textContainer.classList.add('blur-low');
            mediaContainer.classList.add('blur-high');
            reviewLabel.classList.remove('btn-selected');
        } else {
            textContainer.classList.remove('blur-low');
            mediaContainer.classList.remove('blur-high');
            reviewLabel.classList.add('btn-selected');
        }
    }
}

function fetchPosts(username = '') {
    fetch('/server/posts/' + username)
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
