<div class="container">
    <h1>Posts</h1>

    <!-- Form for creating a new post -->
    <form id="postForm">
        <label for="postTitle">Title:</label>
        <input type="text" id="postTitle" name="postTitle" required>
        <label for="postContent">Content:</label>
        <textarea id="postContent" name="postContent" required></textarea>
        <button type="button" onclick="createPost()">Create Post</button>
    </form>

    <!-- Display existing posts -->
    <div id="postsContainer"></div>
</div>