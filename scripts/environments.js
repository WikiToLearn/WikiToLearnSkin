//I'm sorry, I have not found a better way to do this.
$(document).ready(function(){
    $(".beginenvironment").each(function() {
        classes = $(this).attr('class').trim();
        classes = "." + classes;
        classes = classes.replace(" ", ".");
        replaced = classes.replace('.beginenvironment','').replace('begin','end').trim();
        console.log(replaced);
        if(replaced.length > 0)
            $(this).nextUntil(replaced).wrapAll('<div class="environmentcontent"></div>');
    });
});
