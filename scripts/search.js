$(document).ready(function() {
    $('#searchForm').submit(function(e) {
        e.preventDefault();
        var len = $('#searchInput').val().length;
        if(len > 0){
            this.submit();
        } else {
            $('#searchInput').focus();
            $('#searchInput').attr('class', 'expanded-search');
        }
    });
});