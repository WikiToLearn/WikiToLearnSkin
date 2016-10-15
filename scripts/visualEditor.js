mw.hook( 've.activationComplete' ).add( function () {

    $("#ca-ve-edit").addClass("inVisualEditor");
    $("#ca-ve-edit").attr("href", location.href.split("?")[0]);
    $("#ca-ve-edit").find(".tool__icon").removeClass("fa-pencil").addClass("fa-eye");
    $("#ca-ve-edit").find(".tool__title").text(mw.message( 'view' ).text());
    $("#ca-ve-edit").attr("title", mw.message( 'view' ).text());

    $("#ca-ve-edit").click(function(e){ //because somehow the clicks on links are locked
        if($(this).hasClass("inVisualEditor"))
            location.href = location.href.split("?")[0];
    });
});

mw.hook( 've.deactivate' ).add( function () {
    $("#ca-ve-edit").removeClass("inVisualEditor");
    $("#ca-ve-edit").attr("href", location.href + "?veaction=edit");
    $("#ca-ve-edit").find(".tool__icon").removeClass("fa-eye").addClass("fa-pencil");
    $("#ca-ve-edit").find(".tool__title").text(mw.message( 'edit' ).text());
    $("#ca-ve-edit").attr("title", mw.message( 'edit' ).text());
});

