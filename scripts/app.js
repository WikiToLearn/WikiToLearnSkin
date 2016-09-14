( function ( $, mw ) {
    'use strict';

// Expandable search bar
$( '.nav__search' ).on({
    mouseenter: function() {
        console.log('ciao');
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

    /*!
     * Echo Special:Notifications page initialization
     */
    $( document ).ready( function () {
        var $notificationsButton = $('#notifications'),
            $notificationsWidget = $('.dropdown-menu'),
            notificationsItems;


        var queryParameters = {
            action: 'query',
            format: 'json',
            meta: 'notifications'
        }

        $notificationsButton.click(function () {
            $.getJSON('/api.php', queryParameters, function (data) {
                notificationsItems = data.query.notifications.list;
                console.log(notificationsItems);
                for (var prop in notificationsItems) {
                    console.log(prop);
                    console.log(notificationsItems[prop]);
                }
            });
        });
    } );
} )( jQuery, mediaWiki );