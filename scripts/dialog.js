(function($, window, document, undefined) {
  'use strict';
  // Basic dialog
  var dialog = $('#dialog');
  dialog.dialog({
    autoOpen: false,
    modal: true,
    buttons: {
      'Confirm': function() {
        $( this ).dialog('close');
      },
      Cancel: function() {
        $( this ).dialog('close');
      }
    },
    resizable: false,
    width: 600
  });

  $('#open-dialog').on('click', function() {
    dialog.dialog('open');
  });
})(jQuery, this, this.document);
