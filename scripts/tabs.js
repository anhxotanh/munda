(function($, window, document, undefined) {
  'use strict';
  // jQueryUI tabs
  var tabs = $('#tabs-example-1').tabs({
    collapsible: true,
    event: 'click',
    disabled: [0],
    active: 1
  });

  tabs.find('.ui-tabs-nav').sortable({
    axis: 'x',
    stop: function() {
      tabs.tabs('refresh');
    }
  });

  // jQueryUI vertical tabs
  $('#tabs-vertical-1').tabs().addClass('ui-tabs-vertical');
})(jQuery, this, this.document);
