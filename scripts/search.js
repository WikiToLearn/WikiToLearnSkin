$(document).ready(function() {
    console.log("here");
    $('#searchForm').submit(function(e) {
        e.preventDefault();
        var len = $('#searchInput').val().length;
        if(len > 0)
            this.submit();
    });

});