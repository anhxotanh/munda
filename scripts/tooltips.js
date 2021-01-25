(function($, window, document, undefined) {
  'use strict';
  // Tooltip left center
  $('#tooltip-left-center').tooltip({
    show: {
      duration: 300,
      effect: 'fadeIn'
    },
    hide: {
      duration: 300,
      effect: 'fadeOut'
    },
    position: {
      my: 'right-10 center',
      at: 'left center'
    },
    tooltipClass: 'arrow-rc',
    track: false,
    items: '[data-tooltip]',
    content: function() {
      return $(this).attr('data-tooltip');
    }
  });

  // Tooltip left top
  $('#tooltip-left-top').tooltip({
    show: {
      duration: 300,
      effect: 'fadeIn'
    },
    hide: {
      duration: 300,
      effect: 'fadeOut'
    },
    position: {
      my: 'left bottom-10',
      at: 'left top'
    },
    tooltipClass: 'arrow-lb',
    track: false,
    items: '[data-tooltip]',
    content: function() {
      return $(this).attr('data-tooltip');
    }
  });

  // Tooltip left bottom
  $('#tooltip-left-bottom').tooltip({
    show: {
      duration: 300,
      effect: 'fadeIn'
    },
    hide: {
      duration: 300,
      effect: 'fadeOut'
    },
    position: {
      my: 'left top+10',
      at: 'left bottom'
    },
    tooltipClass: 'arrow-lt',
    track: false,
    items: '[data-tooltip]',
    content: function() {
      return $(this).attr('data-tooltip');
    }
  });

  // Tooltip right center
  $('#tooltip-right-center').tooltip({
    show: {
      duration: 300,
      effect: 'fadeIn'
    },
    hide: {
      duration: 300,
      effect: 'fadeOut'
    },
    position: {
      my: 'left+10 center',
      at: 'right center'
    },
    tooltipClass: 'arrow-lc',
    track: false,
    items: '[data-tooltip]',
    content: function() {
      return $(this).attr('data-tooltip');
    }
  });

  // Tooltip right bottom
  $('#tooltip-right-bottom').tooltip({
    show: {
      duration: 300,
      effect: 'fadeIn'
    },
    hide: {
      duration: 300,
      effect: 'fadeOut'
    },
    position: {
      my: 'right top+10',
      at: 'right bottom'
    },
    tooltipClass: 'arrow-rt',
    track: false,
    items: '[data-tooltip]',
    content: function() {
      return $(this).attr('data-tooltip');
    }
  });

  // Tooltip right top
  $('#tooltip-right-top').tooltip({
    show: {
      duration: 300,
      effect: 'fadeIn'
    },
    hide: {
      duration: 300,
      effect: 'fadeOut'
    },
    position: {
      my: 'right bottom-10',
      at: 'right top'
    },
    tooltipClass: 'arrow-rb',
    track: false,
    items: '[data-tooltip]',
    content: function() {
      return $(this).attr('data-tooltip');
    }
  });

  // Tooltip on icon 1
  $('#tooltip-i1').tooltip({
    position: {
      my: 'right+17 bottom+5',
      at: 'center top'
    },
    tooltipClass: 'arrow-rb'
  });

  // Tooltip on icon 2
  $('#tooltip-i2').tooltip({
    position: {
      my: 'right+17 top-5',
      at: 'center bottom'
    },
    tooltipClass: 'arrow-rt'
  });

  // Tooltip on icon 3
  $('#tooltip-i3').tooltip({
    position: {
      my: 'center+1 bottom+5',
      at: 'center top'
    },
    tooltipClass: 'arrow-cb'
  });

  // Tooltip on icon 4
  $('#tooltip-i4').tooltip({
    position: {
      my: 'center+2 top-5',
      at: 'center bottom'
    },
    tooltipClass: 'arrow-ct'
  });
})(jQuery, this, this.document);
