function NotificationsWidget () {

  var modelManager, unreadCounter, wrapperWidget,
  maxNotificationCount = mw.config.get( 'wgEchoMaxNotificationCount' ),
  echoApi = new mw.echo.api.EchoApi();
  this.$overlay = $('<div>');
  this.count = 0;
  this.doneLoading = false;
  this.$badge = $('.badge-count');
  this.markAllReadButton = $('#mark-all-read-button');

  $('#notifications-view-all').attr('href', mw.util.getUrl( 'Special:Notifications' ));
  unreadCounter = new mw.echo.dm.UnreadNotificationCounter( echoApi, 'all', maxNotificationCount );
  modelManager = new mw.echo.dm.ModelManager( unreadCounter, { type: [ 'message', 'alert' ] } );

  this.controller = new mw.echo.Controller(
		echoApi,
		modelManager,
		{
			type: [ 'message', 'alert' ]
		}
  );

  wrapperWidget = new mw.echo.ui.NotificationsWrapper( this.controller, modelManager, {
		$overlay: this.$overlay
  } );

  // Events
  unreadCounter.connect( this, {
    countChange: 'onUnreadCountChange'
  } );

  modelManager.connect( this, {
    update: 'checkShowMarkAllRead'
  } );

  var widget = this;

  this.markAllReadButton.click(function(){
    widget.onMarkAllReadButtonClick();
  });

  //Initialize
  $('#notifications-widget').append(
    wrapperWidget.$element,
    this.$overlay
  );

  // Populate notifications
  wrapperWidget.populate()
    .then( this.setDoneLoading.bind( this ) )
    .then( this.controller.updateSeenTime.bind( this.controller ) )
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
  this.count = this.controller.manager.getUnreadCounter().getCappedNotificationCount( count );
	if ( this.count > 0 ) {
		$badgeCounter.text(
			mw.msg( 'echo-badge-count', mw.language.convertNumber( this.count ) )
		).show();
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
		this.controller.manager.hasLocalUnread()
  );
};

/**
* Respond to mark all read button click
*/
NotificationsWidget.prototype.onMarkAllReadButtonClick = function () {
  var overlay = this,
	numNotifications = this.controller.manager.getLocalUnread().length;
	this.controller.markLocalNotificationsRead()
		.then( function () {
			overlay.confirmationWidget.setLabel(
			  mw.msg( 'echo-mark-all-as-read-confirmation', numNotifications )
		  );
			overlay.confirmationWidget.showAnimated();
    } );
};

/**
* Mark that all the notifications in the badge are seen.
*
* @method
*/
NotificationsWidget.prototype.setBadgeSeen = function () {
  this.$badge.show();
};
