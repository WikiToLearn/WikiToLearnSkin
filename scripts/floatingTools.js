function isToolFloating(){
    return $(window).width() > 544;
}

$( document ).ready(function() {

    var shouldFloat = isToolFloating();
    $(window).resize(function() {
        shouldFloat = isToolFloating();
    });

    toolsWidth = $(".article__tools").width();
    $(".article__tools").width(toolsWidth);

    toolsContainer = $("#tools_container");

    articleMain = $(".article__main");
    articleSheet = $(".article__sheet");

    if(toolsContainer.length > 0 && articleMain.length > 0){
        if(articleSheet.height() > toolsContainer.height()){
            $(document).scroll(function() {
                if(shouldFloat){
                    toolsContainer.css("position", "fixed");
                    toolsContainer.css("bottom", "");

                    if(toolsContainer.offset().top + toolsContainer.height() > articleMain.offset().top + articleMain.height()){
                        toolsContainer.css("position", "absolute");
                        toolsContainer.css("bottom", "0");
                    }
                } else {
                    toolsContainer.css("position", "");
                    toolsContainer.css("bottom", "");
                }
            });
        }
    }
});