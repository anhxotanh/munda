(function($, window, document, undefined) {
  'use strict';
  // Product quality rating system
  $('#product-quality').raty({
    cancel: true,
    cancelHint: 'Cancel rating',
    cancelPlace: 'left',
    scoreName: 'product_quality',
    starType: 'span',
    half: false,
    space: false,
    hints: ['bad', 'poor', 'regular', 'good', 'awesome']
  });

  // Product performance rating system
  $('#product-performance').raty({
    scoreName: 'product_performance',
    cancel: true,
    cancelHint: 'Cancel rating',
    cancelPlace: 'left',
    starType: 'span',
    half: false,
    score: 2,
    space: false,
    hints: ['bad', 'poor', 'regular', 'good', 'awesome']
  });

  // Quality price ratio rating system
  $('#quality-price-ratio').raty({
    scoreName: 'quality_price_ratio',
    cancel: true,
    cancelHint: 'Cancel rating',
    cancelPlace: 'left',
    starType: 'span',
    half: false,
    space: false,
    hints: ['bad', 'poor', 'regular', 'good', 'awesome']
  });

  // Tooltips for the stars
  $('.rating-icons').find('[class^="star-"]').tooltip({
    position: {
      my: 'center bottom-8',
      at: 'center top'
    },
    tooltipClass: 'arrow-cb'
  });

  $('.raty-cancel').tooltip({
    position: {
      my: 'center bottom-8',
      at: 'center top'
    },
    tooltipClass: 'arrow-cb'
  });
})(jQuery, this, this.document);
