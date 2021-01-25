(function($, window, document, undefined) {
  'use strict';
  // Social buttons
  $('.btn').on('click', function(e) {
    e.preventDefault();
  });

  // Tooltips on icons
  $('.btn').tooltip({
    position: {
      my: 'center bottom-7',
      at: 'center top'
    },
    tooltipClass: 'arrow-cb'
  });
})(jQuery, this, this.document);
