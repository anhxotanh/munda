(function($, window, document, undefined) {
  'use strict';
  // Input masking examples
  $.mask.definitions['~']='[+-]';
  $.mask.definitions.h='[A-Fa-f0-9]';
  $('#date').mask('99/99/9999', {
    placeholder: '_'
  });

  $('#phone').mask('(999) 999-999', {
    placeholder: 'X'
  });

  $('#phone-2').mask('(999) 999-9999? x99999', {
    placeholder: 'X'
  });

  $('#tax').mask('99-9999999', {
    placeholder: '_'
  });

  $('#ssn').mask('999-99-9999', {
    placeholder: '#'
  });

  $('#product-key').mask('a*-999-a999');
  $('#eye-script').mask('~9.99 ~9.99 999');
  $('#hex-value').mask('#hhh?hhh');
})(jQuery, this, this.document);
