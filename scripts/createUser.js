$( document ).ready(function() {
    if ($('body.mw-special-CreateAccount').length) {
        $("#wpEmail").prop('required',true);
    }
});