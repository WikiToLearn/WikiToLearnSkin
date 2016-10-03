

$( document ).ready(function() {

    function isToolFloating(){
        return $(window).width() > 544;
    }

    var shouldFloat = isToolFloating();
    $(window).resize(function() {
        shouldFloat = isToolFloating();
    });

    if(shouldFloat){
        toolsWidth = $(".article__tools").width();
        if(toolsWidth)
            $(".article__tools").width(toolsWidth);
    } else {
        $(".multitool").removeClass("horizontal");
    }

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