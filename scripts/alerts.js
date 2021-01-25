(function($, window, document, undefined) {
  'use strict';
  // Close alerts
  $('.alert-dismissible').find('.close').on('click', function() {
    $(this).parent().fadeOut();
  });
})(jQuery, this, this.document);
