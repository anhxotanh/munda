(function($, window, document, undefined) {
  'use strict';
  // Colorpicker examples
  $('#basic-colorpicker-1').spectrum({
    color: '#f00'
  });

  $('#basic-colorpicker-2').spectrum({
    color: '#0f0',
    showInput: true,
    showAlpha: true,
    allowEmpty: true
  });

  $('#basic-colorpicker-3').spectrum({
    color: '#00f',
    showAlpha: true
  });

  $('#basic-colorpicker-4').spectrum({
    allowEmpty: true,
    disabled: true
  });

  // Colopicker with palette
  $('#colorpicker-with-palette').spectrum({
    showPalette: true,
    palette: [
      ['black', 'white', 'blanchedalmond'],
      ['rgb(255, 128, 0);', 'hsv 100 70 50', 'lightyellow']
    ]
  });

  $('#colorpicker-only-palette').spectrum({
    showPaletteOnly: true,
    showPalette: true,
    hideAfterPaletteSelect: true,
    color: 'blanchedalmond',
    palette: [
      ['black', 'white', 'blanchedalmond', 'rgb(255, 128, 0);', 'hsv 100 70 50'],
      ['red', 'yellow', 'green', 'blue', 'violet']
    ]
  });

  $('#colorpicker-toggle-palette').spectrum({
    showPaletteOnly: true,
    togglePaletteOnly: true,
    togglePaletteMoreText: 'more',
    togglePaletteLessText: 'less',
    color: '#999',
    palette: [
      ['#000','#444','#666','#999','#ccc','#eee','#f3f3f3','#fff'],
      ['#f00','#f90','#ff0','#0f0','#0ff','#00f','#90f','#f0f'],
      ['#f4cccc','#fce5cd','#fff2cc','#d9ead3','#d0e0e3','#cfe2f3','#d9d2e9','#ead1dc'],
      ['#ea9999','#f9cb9c','#ffe599','#b6d7a8','#a2c4c9','#9fc5e8','#b4a7d6','#d5a6bd'],
      ['#e06666','#f6b26b','#ffd966','#93c47d','#76a5af','#6fa8dc','#8e7cc3','#c27ba0'],
      ['#c00','#e69138','#f1c232','#6aa84f','#45818e','#3d85c6','#674ea7','#a64d79'],
      ['#900','#b45f06','#bf9000','#38761d','#134f5c','#0b5394','#351c75','#741b47'],
      ['#600','#783f04','#7f6000','#274e13','#0c343d','#073763','#20124d','#4c1130']
    ]
  });

  $('#colorpicker-show-selection-palette').spectrum({
    showPalette: true,
    showSelectionPalette: true,
    palette: [ ],
    localStorageKey: 'colorpicker.demo'
  });

  // Click out fires change
  $('#colorpicker-clickout-1').spectrum({
    clickoutFiresChange: true
  });

  $('#colorpicker-clickout-2').spectrum({
    color: '#7f6000',
    clickoutFiresChange: true,
    showInitial: true
  });

  $('#colorpicker-clickout-3').spectrum({
    color: '#900',
    clickoutFiresChange: true,
    showInitial: true,
    showInput: true
  });

  $('#colorpicker-clickout-4').spectrum({
    allowEmpty: true,
    clickoutFiresChange: true,
    showInitial: true,
    showInput: true
  });

  // Colorpicker buttons options
  $('#colorpicker-buttons-1').spectrum({
    color: '#073763',
    allowEmpty: true,
    chooseText: 'Alright',
    cancelText: 'No way'
  });

  $('#colorpicker-buttons-2').spectrum({
    color: '#20124d',
    allowEmpty: true,
    showButtons: false
  });

  // Colorpicker preferred format
  $('#colorpicker-format-1').spectrum({
    preferredFormat: 'hex',
    showInput: true,
    showPalette: true,
    palette: [
      ['red', 'rgba(0, 255, 0, .5)', 'rgb(0, 0, 255)']
    ]
  });

  $('#colorpicker-format-2').spectrum({
    preferredFormat: 'hex3',
    showInput: true,
    showPalette: true,
    palette: [
      ['red', 'rgba(0, 255, 0, .5)', 'rgb(0, 0, 255)']
    ]
  });
  $('#colorpicker-format-3').spectrum({
    preferredFormat: 'hsl',
    showInput: true,
    showPalette: true,
    palette: [
      ['red', 'rgba(0, 255, 0, .5)', 'rgb(0, 0, 255)']
    ]
  });
  $('#colorpicker-format-4').spectrum({
    preferredFormat: 'rgb',
    showInput: true,
    showPalette: true,
    palette: [
      ['red', 'rgba(0, 255, 0, .5)', 'rgb(0, 0, 255)']
    ]
  });
  $('#colorpicker-format-5').spectrum({
    preferredFormat: 'name',
    showInput: true,
    showPalette: true,
    palette: [
      ['red', 'rgba(0, 255, 0, .5)', 'rgb(0, 0, 255)']
    ]
  });
  $('#colorpicker-format-6').spectrum({
    showInput: true,
    showPalette: true,
    palette: [
      ['red', 'rgba(0, 255, 0, .5)', 'rgb(0, 0, 255)']
    ]
  });

  // Input + colorpicker on the right
  $('#colorpicker-input-1').spectrum({
    color: '#ff0',
    preferredFormat: 'hex',
    change: function(color) {
      $('#colorpicker-input-color').val(color);
    }
  });

  // On blur change the value of the colorpicker
  $('#colorpicker-input-color').on('change', function() {
    $('#colorpicker-input-1').spectrum('set', $(this).val());
  });

  // Input + colorpicker on the left
  $('#colorpicker-input-3').spectrum({
    color: '#f00',
    preferredFormat: 'hex',
    change: function(color) {
      $('#colorpicker-input-color-2').val(color);
    }
  });

  // On blur change the value of the colorpicker
  $('#colorpicker-input-color-2').on('change', function() {
    $('#colorpicker-input-3').spectrum('set', $(this).val());
  });

  // Input + colorpicker error state
  $('#colorpicker-error').spectrum({
    color: '#0f0',
    preferredFormat: 'hex',
    change: function(color) {
      $('#colorpicker-state-error').val(color);
    }
  });

  // On blur change the value of the colorpicker
  $('#colorpicker-state-error').on('change', function() {
    $('#colorpicker-error').spectrum('set', $(this).val());
  });

  // Input + colorpicker error state
  $('#colorpicker-error-right').spectrum({
    color: '#0ff',
    preferredFormat: 'hex',
    change: function(color) {
      $('#colorpicker-state-error-right').val(color);
    }
  });

  // On blur change the value of the colorpicker
  $('#colorpicker-state-error-right').on('change', function() {
    $('#colorpicker-error-right').spectrum('set', $(this).val());
  });
})(jQuery, this, this.document);
