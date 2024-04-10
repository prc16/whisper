 <!-- Display existing posts -->
 <div id="postsFeedContainer"></div>
 <script>
     document.addEventListener('DOMContentLoaded', () => {
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
                    <img src="${post.post_file_path}" alt="" class="image-preview">
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


         // Event listener for voting
         document.addEventListener('click', vote);

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

         // Fetch posts initially
         fetchPosts();

         // Fetch posts every 5 seconds
         // setInterval(fetchPosts, 5000);


         // Function to handle the 'updateNeeded' event
         function handleUpdateEvent() {
             fetchPosts();
         }

         // Add event listener for 'update' event on displayPosts div
         postsFeedContainer.addEventListener("updateNeeded", handleUpdateEvent);
     });
 </script>