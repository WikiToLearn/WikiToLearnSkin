( function ( $, mw ) {
    'use strict';

    var mql = window.matchMedia("(min-width: 544px)");
    mql.addListener(handleViewportChange);
    handleViewportChange(mql);

    function handleViewportChange(mql) {
        if (mql.matches) {
            // Expandable search bar
            $( '.nav__search' ).on({
                mouseenter: function() {
                    $('#search').removeClass('collapsed-search');
                    $('#search').addClass('expanded-search');
                },
                mouseleave: function () {
                    if (! $('#search').is(':focus')) {
                        $('#search').removeClass('expanded-search');
                        $('#search').addClass('collapsed-search');
                    }
                },
                focus: function () {
                    $('#search').removeClass('collapsed-search');
                    $('#search').addClass('expanded-search');
                },
                focusout: function() {
                    $('#search').removeClass('expanded-search');
                    $('#search').addClass('collapsed-search');
                }
            });
        } else {
            $('.nav__search').unbind();
        }
    }
    var notificationsWidget = new NotificationsWidget();
    /*!
     * Echo Special:Notifications page initialization

    $( document ).ready( function () {
      var notificationsWidget = new NotificationsWidget();
    });
    */
} )( jQuery, mediaWiki );
