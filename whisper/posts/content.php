 <!-- Display existing posts -->
 <div id="postsFeedContainer"></div>
 <script src="/whisper/posts/posts.js"></script>
 <script>
     document.addEventListener('DOMContentLoaded', () => {
         // Fetch posts initially
         fetchPosts();
         // Add event listener for 'update' event on displayPosts div
         document.getElementById('postsFeedContainer').addEventListener("updateNeeded", handleUpdateEvent);
         // Event listener for voting
         document.addEventListener('click', vote);
     });
 </script>