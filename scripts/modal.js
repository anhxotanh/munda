(function($, window, document, undefined) {
  'use strict';
  // Register dialog
  $('#register-dialog').dialog({
    dialogClass: 'modal-form',
    draggable: true,
    autoOpen: false,
    modal: true,
    resizable: false,
    show: {
      effect: 'slideDown',
      duration: 300
    },
    hide: {
      effect: 'slideUp',
      duration: 300
    },
    closeText: 'close',
    width: 500
  });
  $('#register').on('click', function(e) {
    e.preventDefault();
    $('#register-dialog').dialog('open');
  });

  // Login Dialog
  $('#login-dialog').dialog({
    dialogClass: 'modal-form',
    autoOpen: false,
    modal: true,
    resizable: false,
    show: {
      effect: 'slideDown',
      duration: 300
    },
    hide: {
      effect: 'slideUp',
      duration: 300
    },
    closeText: 'close',
    width: 400
  });
  $('#login').on('click', function(e) {
    e.preventDefault();
    $('#login-dialog').dialog('open');
  });
})(jQuery, this, this.document);
