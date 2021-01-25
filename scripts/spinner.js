(function($, window, document, undefined) {
  'use strict';
  // Basic spinner
  var spinner = $('#basic-spinner').spinner({
    icons: {
      up: 'ui-icon-carat-1-n',
      down: 'ui-icon-carat-1-s'
    }
  });
  var $basicInstance = $('#basic-spinner').spinner('instance');
  $basicInstance.buttons.find('.ui-icon').text('');

  $('#toggle-de').on('click', function() {
    if(spinner.spinner('option', 'disabled')) {
      spinner.spinner('enable');
    } else {
      spinner.spinner('disable');
    }
  });

  $('#get-value').on('click', function() {
    console.log(spinner.spinner('value'));
  });

  $('#set-value').on('click', function() {
    spinner.spinner('value', 5);
  });

  // Currency
  $('#spinner-currency').spinner({
    min: 5,
    max: 5000,
    step: 25,
    start: 1000,
    icons: {
      up: 'ui-icon-carat-1-n',
      down: 'ui-icon-carat-1-s'
    }
  });
  var $currencyInstance = $('#spinner-currency').spinner('instance');
  $currencyInstance.buttons.find('.ui-icon').text('');

  // Decimal spinner
  $('#spinner-decimal').spinner({
    min: 5.00,
    max: 99.99,
    step: 0.01,
    numberFormat: 'n',
    icons: {
      up: 'ui-icon-carat-1-n',
      down: 'ui-icon-carat-1-s'
    }
  });
  var $decimalInstance = $('#spinner-decimal').spinner('instance');
  $decimalInstance.buttons.find('.ui-icon').text('');

  // Overflow spinner
  $('#spinner-overflow').spinner({
    spin: function(event, ui) {
      if (ui.value > 10) {
        $( this ).spinner('value', -10);
        return false;
      } else if (ui.value < -10) {
        $( this ).spinner('value', 10);
        return false;
      }
    },
    icons: {
      up: 'ui-icon-carat-1-n',
      down: 'ui-icon-carat-1-s'
    }
  });
  var $overflowInstance = $('#spinner-overflow').spinner('instance');
  $overflowInstance.buttons.find('.ui-icon').text('');
})(jQuery, this, this.document);
