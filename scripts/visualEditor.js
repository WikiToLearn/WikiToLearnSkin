mw.hook( 've.activationComplete' ).add( function () {

    $("#ca-ve-edit").addClass("inVisualEditor");
    $("#ca-ve-edit").attr("href", location.href.split("?")[0]);
    $("#ca-ve-edit").find(".tool__icon").removeClass("fa-pencil").addClass("fa-eye");
    $("#ca-ve-edit").find(".tool__title").text(mw.message( 'view' ).text());
    $("#ca-ve-edit").attr("title", mw.message( 'view' ).text());

    $("#ca-ve-edit").click(function(e){ //because somehow the clicks on links are locked
        if(window.ve && ve.init && ve.init.target && ve.init.target.active) //proper way to check if VE is open
            location.href = location.href.split("?")[0];
    });
});

//handle back button pressed
mw.hook( 've.deactivate' ).add( function () {
    $("#ca-ve-edit").attr("href", location.href + "?veaction=edit");
    $("#ca-ve-edit").find(".tool__icon").removeClass("fa-eye").addClass("fa-pencil");
    $("#ca-ve-edit").find(".tool__title").text(mw.message( 'edit' ).text());
    $("#ca-ve-edit").attr("title", mw.message( 'edit' ).text());
});

