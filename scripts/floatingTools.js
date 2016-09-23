$( document ).ready(function() {
    toolsWidth = $(".article__tools").width();
    $(".article__tools").width(toolsWidth);

    toolsContainer = $("#tools_container");

    articleMain = $(".article__sheet");

    if(articleMain.height() > toolsContainer.height()){
        $(document).scroll(function() {
            toolsContainer.css("position", "fixed");
            toolsContainer.css("bottom", "");

            if(toolsContainer.offset().top + toolsContainer.height() > articleMain.offset().top + articleMain.height()){
                toolsContainer.css("position", "absolute");
                toolsContainer.css("bottom", "0");
            }
        });
    }
});