( function ( $, mw ) {
    'use strict';

    /*!
     * Echo Special:Notifications page initialization
     */
    $( document ).ready( function () {
        var $notificationsButton = $('#notifications'),
            $notificationsWidget = $('.dropdown-menu'),
            notificationsItems;


        var parameters = {
            action: 'query',
            format: 'json',
            meta: 'notifications'
        }

        $notificationsButton.click(function () {
            $.getJSON('/api.php', parameters, function (data) {
                notificationsItems = data.query.notifications.list;
                $.each(function ( index, element ) {
                    console.log(index);
                    console.log(element);
                });
            })
        });
    } );
} )( jQuery, mediaWiki );