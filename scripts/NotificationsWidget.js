function NotificationsWidget () {

  //Set static divs
  $('.notifications-count').prepend(mw.msg( 'notifications' ));
  $('#notifications-view-all').html(mw.msg('echo-overlay-link')).attr('href', mw.util.getUrl( 'Special:Notifications' ));
  $('#mark-all-read-button').html(mw.msg( 'echo-mark-all-as-read' ));

  var model, unreadCounter, wrapperWidget,
  maxNotificationCount = mw.config.get( 'wgEchoMaxNotificationCount' ),
  echoApi = new mw.echo.api.EchoApi(),
  widget = $('#notifications-widget');

  unreadCounter = new mw.echo.dm.UnreadNotificationCounter( echoApi, 'all', maxNotificationCount );

  model = new mw.echo.dm.NotificationsModel(
    echoApi,
    unreadCounter,
    { type: 'all' }
  );

  wrapperWidget = new mw.echo.ui.NotificationsWrapper( model, {
    $overlay: widget
  } );

  // Events
  unreadCounter.connect( this, {
    countChange: 'onUnreadCountChange'
  } );

  // Populate notifications
  wrapperWidget.populate()
    .then( model.updateSeenTime.bind( model, 'all' ) );
}

/**
* Update the unread number on the notifications badge
*
* @param {Number} count Number of unread notifications
* @method
*/
NotificationsWidget.prototype.onUnreadCountChange = function ( count ) {
  var $badgeCounter = $('#badge-count');
  console.log("baghaga");
  if ( count > 0 ) {
    $badgeCounter.text( count ).show();
  } else {
    $badgeCounter.hide();
  }
};
