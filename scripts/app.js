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
                    $('#searchInput').removeClass('collapsed-search');
                    if(!$("#searchInput").hasClass('expanded-search'))
                        $('#searchInput').addClass('expanded-search');
                },
                click: function(){
                    $('#searchInput').removeClass('collapsed-search');
                    if(!$("#searchInput").hasClass('expanded-search'))
                        $('#searchInput').addClass('expanded-search');
                },
                mouseleave: function () {
                    if (! $('#searchInput').is(':focus')) {
                        $('#searchInput').removeClass('expanded-search');
                        $('#searchInput').addClass('collapsed-search');
                    }
                },
                focus: function () {
                    $('#searchInput').removeClass('collapsed-search');
                    if(!$("#searchInput").hasClass('expanded-search'))
                        $('#searchInput').addClass('expanded-search');
                },
                focusout: function() {
                    $('#searchInput').removeClass('expanded-search');
                    $('#searchInput').addClass('collapsed-search');
                }
            });
        } else {
            $('.nav__search').unbind();
        }
    }

    //Create notification widget
    var notificationsWidget = new NotificationsWidget();
} )( jQuery, mediaWiki );
