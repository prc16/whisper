<div id="sidebarsearchContainer">
    <div class="sidebarsearch">
        <i class="far fa-search"></i>
        <input id="searchInput" type="text" placeholder="Search Whisper" onkeydown="handleSearch(event)">
    </div>
</div>

<script>
function handleSearch(event) {
    if (event.key === "Enter") {
        performSearch();
    }
}

function performSearch() {
    var query = document.getElementById("searchInput").value;
    if (query.trim() !== "") {
        window.location.href = "/search/" + encodeURIComponent(query);
    }
}
</script>
