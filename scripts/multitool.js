// Code from https://github.com/Dogfalo/materialize
// https://github.com/Dogfalo/materialize/blob/master/LICENSE
// tweaked for our needs
$(document).ready(function() {
    // jQuery reverse
    $.fn.reverse = [].reverse;

    // Hover behaviour: make sure this doesn't work on .click-to-toggle FABs!
    $('.multitool:not(.click-to-toggle)').on('mouseenter', function(e) {
      var $this = $(this);
      openFABMenu($this);
    });
    $('.multitool:not(.click-to-toggle)').on('mouseleave', function(e) {
      var $this = $(this);
      closeFABMenu($this);
    });

    // Toggle-on-click behaviour.
    $('.multitool.click-to-toggle > .multitool__trigger').on('click', function(e) {
      console.log("here");
      var $this = $(this);
      var $menu = $this.parent();
      if ($menu.hasClass('active')) {
        closeFABMenu($menu);
      } else {
        openFABMenu($menu);
      }
    });

    $.fn.extend({
      openFAB: function() {
        openFABMenu($(this));
      },
      closeFAB: function() {
        closeFABMenu($(this));
      }
    });


  var openFABMenu = function (btn) {
    $this = btn;
    if ($this.hasClass('active') === false) {

      // Get direction option
      var horizontal = $this.hasClass('horizontal');
      var offsetY, offsetX;

      if (horizontal === true) {
        offsetX = 40;
      } else {
        offsetY = 40;
      }

      $this.addClass('active');
      $this.find('.multitool__trigger').addClass('active');

      $this.find('ul .tool').velocity(
        { scaleY: ".4", scaleX: ".4", translateY: offsetY + 'px', translateX: offsetX + 'px'},
        { duration: 0 });

      var time = 0;
      $this.find('ul .tool').reverse().each( function () {
        $(this).velocity(
          { opacity: "1", scaleX: "1", scaleY: "1", translateY: "0", translateX: '0'},
          { duration: 80, delay: time });
        time += 40;
      });
    }
  };

  var closeFABMenu = function (btn) {
    $this = btn;
    // Get direction option
    var horizontal = $this.hasClass('horizontal');
    var offsetY, offsetX;

    if (horizontal === true) {
      offsetX = 40;
    } else {
      offsetY = 40;
    }

    $this.removeClass('active');
    $this.find('.multitool__trigger').removeClass('active');

    var time = 0;
    $this.find('ul .tool').velocity("stop", true);
    $this.find('ul .tool').velocity(
      { opacity: "0", scaleX: ".4", scaleY: ".4", translateY: offsetY + 'px', translateX: offsetX + 'px'},
      { duration: 80 }
    );
  };

});
