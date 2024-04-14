<div class="topbar-middle-container">
    <div class="topbar-middle-text" id="topbar-middle-title"></div>
</div>

<script>
    function updateTitle(title) {

        // Find the element with id "topbar-middle-title"
        var topbarMiddleTitle = document.getElementById("topbar-middle-title");

        // Set the text content of the element to the page title
        if (topbarMiddleTitle) {
            topbarMiddleTitle.textContent = title;
        }
        document.title = title;
    }
    document.addEventListener('DOMContentLoaded', () => {
        updateTitle(document.title);
    });
</script>