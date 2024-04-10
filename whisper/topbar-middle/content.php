<div class="topbar-middle-container">
    <div class="topbar-middle-text" id="topbar-middle-title"></div>
</div>

<script>
    // Get the title of the page
    var pageTitle = document.title;

    // Find the element with id "topbar-middle-title"
    var topbarMiddleTitle = document.getElementById("topbar-middle-title");

    // Set the text content of the element to the page title
    if (topbarMiddleTitle) {
        topbarMiddleTitle.textContent = pageTitle;
    }
</script>