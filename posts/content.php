<div class="container">
    <h1>Create Post</h1>

    <!-- Form for creating a new post -->
    <form id="postForm">
        <label for="postContent">Content:</label>
        <textarea id="postContent" name="postContent" required></textarea>
        <button type="button" onclick="createPost()">Create Post</button>
    </form>

    <!-- Display existing posts -->
    <div id="postsContainer"></div>
</div>