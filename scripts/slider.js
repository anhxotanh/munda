(function($, window, document, undefined) {
  'use strict';
  // Basic slider
  $('#basic-slider').slider({
    animate: '600'
  });
  $('#basic-slider').slider('value', 20);

  $('#basic-slider').on('slidechange', function(e, ui) {
    console.log(ui.value);
  });

  // Range slider
  $('#range-slider').slider({
    animate: '600',
    range: true,
    min: 0,
    max: 100,
    values: [ 45, 100 ],
    slide: function( event, ui ) {
      $('#amount').text('$' + ui.values[0] + ' - $' + ui.values[1]);
    }
  });
  $('#amount').text('$' + $('#range-slider').slider('values', 0) + ' - $' + $('#range-slider').slider('values', 1));

  // Max range slider
  $('#max-range-slider').slider({
    animate: '600',
    range: 'max',
    min: 1,
    max: 10,
    value: 2,
    slide: function(event, ui) {
      $('#max-amount').text(ui.value);
    }
  });
  $('#max-amount').text($('#max-range-slider').slider('value'));

  // Min range slider
  $('#min-range-slider').slider({
    animate: '600',
    range: 'min',
    min: 1,
    max: 300,
    value: 103,
    slide: function(event, ui) {
      $('#min-amount').text('$' + ui.value);
    }
  });
  $('#min-amount').text('$' + $('#min-range-slider').slider('value'));

  // Snap to increments
  $('#increments-slider').slider({
    animate: '600',
    min: 20,
    max: 200,
    value: 40,
    step: 20,
    slide: function(event, ui) {
      $('#increments-amount').text('$' + ui.value);
    }
  });
  $('#increments-amount').text('$' + $('#increments-slider').slider('value'));

  // Basic vertical slider
  $('#basic-vertical-slider').slider({
    animate: '600',
    orientation: 'vertical',
    range: 'min',
    min: 0,
    max: 100,
    value: 60,
    slide: function(event, ui) {
      $('#basic-vertical-amount').text(ui.value);
    }
  });
  $('#basic-vertical-amount').text($('#basic-vertical-slider').slider('value'));

  // Range vertical slider
  $('#range-vertical-slider').slider({
    animate: '600',
    orientation: 'vertical',
    range: true,
    min: 0,
    max: 100,
    values: [10, 80],
    slide: function(event, ui) {
      $('#range-vertical-amount').text('$' + ui.values[0] + ' - $' + ui.values[1]);
    }
  });
  $('#range-vertical-amount').text('$' + $('#range-vertical-slider').slider('values', 0) +
    ' - $' + $('#range-vertical-slider').slider('values', 1));
})(jQuery, this, this.document);
