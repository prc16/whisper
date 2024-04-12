<!-- Display existing posts -->
<div id="postsFeedContainer"></div>
<script src="/scripts/posts.js"></script>
<script>
    // Function to handle the 'updateNeeded' event
    function handleUpdateEvent() {
        fetchPosts();
    }

    document.addEventListener('DOMContentLoaded', () => {

        // Add event listener for 'update' event on displayPosts div
        postsFeedContainer.addEventListener("updateNeeded", handleUpdateEvent);
        
        // Fetch posts initially
        handleUpdateEvent();

        // Event listener for voting
        document.addEventListener('click', vote);

        // Fetch posts every 5 seconds
        // setInterval(handleUpdateEvent, 5000);
    });
</script>