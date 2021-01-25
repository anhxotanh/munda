(function($, window, document, undefined) {
  'use strict';
  // jQueryUI buttons
  $('#jqueryui-link, #jqueryui-btn, #jqueryui-input').button();

  // jQueryUI checkboxes
  $('#jqueryui-checkbox').button();
  $('#checkbox-group').buttonset();

  // jQueryUI icons
  $('#jqueryui-icon-only-1').button({
    icons: {
      primary: 'icon-trophy'
    },
    text: false
  });

  $('#jqueryui-icon-on-left').button({
    icons: {
      primary: 'icon-camera3'
    }
  });

  $('#jqueryui-icon-on-both').button({
    icons: {
      primary: 'icon-chart',
      secondary: 'icon-arrow-right'
    }
  });

  $('#jqueryui-icon-only-2').button({
    icons: {
      primary: 'icon-cog',
      secondary: 'icon-arrow-down72'
    },
    text: false
  });

  // jQueryUI radio
  $('#radio-group').buttonset();

  // jQueryUI split button
  $('#rerun').button().on('click', function() {
    console.log('Running the last action');
  }).next().button({
    text: false,
    icons: {
      primary: 'icon-arrow-down72'
    }
  }).on('click', function(){
    var menu = $(this).parent().next().show().position({
      my: 'left top',
      at: 'left bottom',
      of: this
    });
    $(document).one('click', function() {
      menu.hide();
    });
    return false;
  }).parent().buttonset().next().hide().menu();
})(jQuery, this, this.document);
