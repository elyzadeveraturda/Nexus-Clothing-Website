document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.getElementById("search-toggle");
    const searchBar = document.getElementById("search-bar");

    toggleBtn.addEventListener("click", function () {
        if (searchBar.style.maxHeight) {
            searchBar.style.maxHeight = null;
        } else {
            searchBar.style.maxHeight = searchBar.scrollHeight + "px";
        }
    });
});
