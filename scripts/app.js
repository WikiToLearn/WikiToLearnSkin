( function ( $, mw ) {
    'use strict';

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