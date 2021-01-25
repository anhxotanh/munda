(function($, window, document, undefined) {
  'use strict';
  // Basic datepickers
  $('#basic-datepicker-1').datepicker({
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>',
    showAnim: 'fadeIn'
  });

  // Custom datepicker positioning
  $('#basic-datepicker-1').on('focus', function() {
    var $this = $(this);
    var $widget = $this.datepicker('widget');
    $widget.position({
      my: 'right top+5',
      at: 'right bottom',
      of: $this
    });
  });


  $('#basic-datepicker-2').datepicker({
    showButtonPanel: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>'
  });

  // Datepicker with month & year menus
  $('#datepicker-month-and-year-1').datepicker({
    showButtonPanel: false,
    changeMonth: true,
    changeYear: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>'
  });

  $('#datepicker-month-and-year-2').datepicker({
    showButtonPanel: true,
    changeMonth: true,
    changeYear: true,
    showOtherMonths: true,
    selectOtherMonths: true,
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>'
  });

  // Display multiple months
  $('#multiple-months').datepicker({
    numberOfMonths: 3,
    showOtherMonths: true,
    showButtonPanel: true,
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>'
  });

  // Inline calendar
  $('#datepicker-inline-1').datepicker({
    showOtherMonths: true,
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>',
    showButtonPanel: true,
    showWeek: true
  });
  $('#datepicker-inline-2').datepicker({
    showOtherMonths: true,
    changeMonth: true,
    changeYear: true,
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>'
  });

  // Date formats
  $('#datepicker-date-formats').datepicker({
    showButtonPanel: false,
    showOtherMonths: false,
    selectOtherMonths: true,
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>'
  });

  $('#date-format').on('change', function() {
    $('#datepicker-date-formats').datepicker('option', 'dateFormat', $(this).val());
  });

  // Input group
  $('#datepicker-icon').datepicker({
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>'
  });

  // Icon trigger
  $('#datepicker-trigger').datepicker({
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>',
    showButtonPanel: true,
    showWeek: true
  });

  $('#icon-trigger').on('click', function() {
    $('#datepicker-trigger').datepicker('show');
  });

  // Populate alternate field
  $('#datepicker-alternate-1').datepicker({
    altField: '#alternate',
    altFormat: 'DD, d MM, yy',
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>'
  });

  // Restrict date range
  $('#datepicker-restrict').datepicker({
    minDate: -20,
    maxDate: '+1M +10D',
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>'
  });

  // Select a date range
  $('#datepicker-from').datepicker({
    defaultDate: '+1w',
    onClose: function(selectedDate) {
      $('#datepicker-to').datepicker('option', 'minDate', selectedDate);
    },
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>'
  });

  $('#datepicker-to').datepicker({
    defaultDate: '+1w',
    onClose: function(selectedDate) {
      $('#datepicker-from').datepicker('option', 'maxDate', selectedDate);
    },
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>'
  });
})(jQuery, this, this.document);
