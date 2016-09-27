$(document).ready(function() {
    console.log("here");
    $('#searchForm').submit(function(e) {
        e.preventDefault();
        var len = $('#searchInput').val().length;
        return len > 0;
    });

});