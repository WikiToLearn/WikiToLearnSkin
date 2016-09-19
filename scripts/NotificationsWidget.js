function NotificationsWidget () {

  //Set static divs
  $('.notifications-count').prepend(mw.msg( 'notifications' ));
  $('#notifications-view-all').html(mw.msg('echo-overlay-link')).attr('href', mw.util.getUrl( 'Special:Notifications' ));
  $('#mark-all-read-button').html(mw.msg( 'echo-mark-all-as-read' ));

  var model, unreadCounter, wrapperWidget,
  maxNotificationCount = mw.config.get( 'wgEchoMaxNotificationCount' ),
  echoApi = new mw.echo.api.EchoApi(),
  overlay = $('<div>').addClass("mw-echo-ui-overlay");
  
  unreadCounter = new mw.echo.dm.UnreadNotificationCounter( echoApi, 'all', maxNotificationCount );

  model = new mw.echo.dm.NotificationsModel(
    echoApi,
    unreadCounter,
    { type: 'all' }
  );

  wrapperWidget = new mw.echo.ui.NotificationsWrapper( model, {
    $overlay: overlay
  } );

  // Events
  unreadCounter.connect( this, {
    countChange: 'onUnreadCountChange'
  } );

  //Initialize
  $('#notifications-widget').append(
    wrapperWidget.$element,
    overlay
  );
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
  if ( count > 0 ) {
    $badgeCounter.text( count ).show();
  } else {
    $badgeCounter.hide();
  }
};
