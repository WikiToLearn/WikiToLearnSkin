//I'm sorry, I have not found a better way to do this.
//Thanks: http://stackoverflow.com/questions/3276133/jquery-wrapping-text-and-elements-between-hr-tags
$(document).ready(function(){
    $(".beginenvironment").each(function() {
        classes = $(this).attr('class').trim();
        classes = "." + classes;
        classes = classes.replace(" ", ".");
        replaced = classes.replace('.beginenvironment','').replace('begin','end').trim();
        if(replaced.length > 0){
            var $set = $();
            var nxt = this.nextSibling;
            console.log(nxt);
            while(nxt) {
                if(!$(nxt).is(replaced)) {
                    $set.push(nxt);
                    nxt = nxt.nextSibling;
                } else break;
            }
            $set.wrapAll('<div class="environmentcontent" />');
        }
    });
});
