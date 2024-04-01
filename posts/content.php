<div class="container">
    <!-- Form for creating a new post -->
    <form id="postForm">
        <h1>Create Post</h1>
        <label for="postContent">Content:</label>
        <textarea id="postContent" name="postContent" required></textarea>
        <button type="button" onclick="createPost()">Post</button>
    </form>

    <!-- Display existing posts -->
    <div id="postsContainer"></div>
</div>