function NotificationsWidget () {

  var unreadCounter, wrapperWidget,
  maxNotificationCount = mw.config.get( 'wgEchoMaxNotificationCount' ),
  echoApi = new mw.echo.api.EchoApi();
  this.$overlay = $('<div>');
  this.count = 0;
  this.doneLoading = false;
  this.$badge = $('#badge-count');
  this.markAllReadButton = $('#mark-all-read-button');

  $('#notifications-view-all').attr('href', mw.util.getUrl( 'Special:Notifications' ));

  unreadCounter = new mw.echo.dm.UnreadNotificationCounter( echoApi, 'all', maxNotificationCount );

  this.model = new mw.echo.dm.NotificationsModel(
    echoApi,
    unreadCounter,
    { type: 'all' }
  );

  notificationsWrapper = new mw.echo.ui.NotificationsWrapper( this.model, {
    $overlay: this.$overlay
  } );

  // Events
  unreadCounter.connect( this, {
    countChange: 'onUnreadCountChange'
  } );

  this.model.connect( this, {
			update: 'checkShowMarkAllRead'
	} );

  var widget = this;
  this.markAllReadButton.click(function(){
    widget.onMarkAllReadButtonClick();
  });

  //Initialize
  $('#notifications-widget').append(
    notificationsWrapper.$element,
    this.$overlay
  );

  // Populate notifications
  notificationsWrapper.populate()
    .then( this.model.updateSeenTime.bind( this.model, 'all' ) )
    .then( this.setDoneLoading.bind( this ) )
		.then( this.setBadgeSeen.bind( this ) )
    .then( this.checkShowMarkAllRead.bind( this ) );
}

/**
* Set done loading flag for notifications list
*
* @method
*/
NotificationsWidget.prototype.setDoneLoading = function () {
  this.doneLoading = true;
},
/**
* Check if notifications have finished loading
*
* @method
* @return {Boolean} Notifications list has finished loading
*/
NotificationsWidget.prototype.isDoneLoading = function () {
  return this.doneLoading;
}

/**
* Update the unread number on the notifications badge
*
* @param {Number} count Number of unread notifications
* @method
*/
NotificationsWidget.prototype.onUnreadCountChange = function ( count ) {
  var $badgeCounter = this.$badge;
  if ( count > 0 ) {
    $badgeCounter.text( count ).show();
  } else {
    $badgeCounter.hide();
  }
  this.checkShowMarkAllRead();
};

/**
* Toggle mark all read button
*
* @method
*/
NotificationsWidget.prototype.checkShowMarkAllRead = function () {
  this.markAllReadButton.toggle(
    this.isDoneLoading() &&
    this.model.unreadCounter.getCount() > 0
  );
};

/**
* Respond to mark all read button click
*/
NotificationsWidget.prototype.onMarkAllReadButtonClick = function () {
  this.model.markAllRead();
};

/**
* Mark that all the notifications in the badge are seen.
*
* @method
*/
NotificationsWidget.prototype.setBadgeSeen = function () {
  this.$badge.show();
};
