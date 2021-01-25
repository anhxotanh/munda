(function($, window, document, undefined) {
  'use strict';
  // Basic
  $('#basic-timepicker-1').datetimepicker({
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>'
  });

  $('#basic-timepicker-2').timepicker();

  // Format the time
  $('#timepicker-format-time').datetimepicker({
    timeFormat: 'hh:mm tt',
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>'
  });

  // Using timezones
  $('#timepicker-timezone-1').datetimepicker({
    timeFormat: 'hh:mm tt z',
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>'
  });

  $('#timepicker-timezone-2').datetimepicker({
    timeFormat: 'HH:mm z',
    timezoneList: [
      {
        value: -300,
        label: 'Eastern'
      },
      {
        value: -360,
        label: 'Central'
      },
      {
        value: -420,
        label: 'Mountain'
      },
      {
        value: -480,
        label: 'Pacific'
      }
    ],
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>'
  });

  $('#timepicker-timezone-3').datetimepicker({
    timeFormat: 'HH:mm z',
    timezone: 'MT',
    timezoneList: [
      {
        value: 'ET',
        label: 'Eastern'
      },
      {
        value: 'CT',
        label: 'Central'
      },
      {
        value: 'MT',
        label: 'Mountain'
      },
      {
        value: 'PT',
        label: 'Pacific'
      }
    ],
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>'
  });

  // Set the interval step of sliders
  $('#timepicker-step').datetimepicker({
    timeFormat: 'HH:mm:ss',
    stepHour: 2,
    stepMinute: 10,
    stepSecond: 10,
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>'
  });

  // Add sliderAccess plugin for touch devices
  $('#timepicker-slideraccess').datetimepicker({
    addSliderAccess: true,
    sliderAccessArgs: {
      touchonly: false
    },
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>'
  });

  // Alternate fields
  $('#timepicker-alternate-1').datetimepicker({
    altField: '#timepicker-alternate-2',
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>'
  });

  $('#timepicker-alternate-3').datetimepicker({
    altField: '#timepicker-alternate-4',
    altFieldTimeOnly: false,
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>'
  });

  $('#timepicker-alternate-5').datetimepicker({
    altField: '#timepicker-alternate-6',
    altFieldTimeOnly: false,
    altFormat: 'yy-mm-dd',
    altTimeFormat: 'h:m tt',
    altSeparator: ' @ ',
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>'
  });

  // Time restraints
  $('#timepicker-restrain-1').timepicker({
    hourMin: 8,
    hourMax: 16
  });

  $('#timepicker-restrain-2').datetimepicker({
    minDate: new Date(2014, 9, 15, 8, 30),
    maxDate: new Date(2014, 9, 26, 18, 0),
    prevText: '<i class="icon-arrow-left8"></i>',
    nextText: '<i class="icon-arrow-right8"></i>'
  });
})(jQuery, this, this.document);
