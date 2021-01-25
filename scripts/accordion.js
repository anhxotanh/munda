(function($, window, document, undefined) {
  'use strict';

  // Basic accordion
  $('#basic-accordion').accordion({
    collapsible: true,
    disabled: false,
    active: 2,
    header: '> div > h3',
    icons: {
      header: 'icon-arrow-right8',
      activeHeader: 'icon-arrow-down8'
    },
    heightStyle: 'content',
    event: 'click'
  }).sortable({
    axis: 'y',
    handle: 'h3',
    stop: function(event, ui) {
      ui.item.children('h3').triggerHandler('focusout');
      $(this).accordion('refresh');
    }
  });
})(jQuery, this, this.document);
