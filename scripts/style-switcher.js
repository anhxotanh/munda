(function($, window, document, undefined) {
  'use strict';
  $('#switcher-toggle').on('click', function() {
    $(this).parent().toggleClass('open');
  });

  // Skin
  $('.skin').on('click', function(e) {
    var $this = $(this),
        $id = $this.attr('id'),
        spectrum = $('#spectrum-custom'),
        formFramework = $('#form-framework'),
        jqueryuiTheme = $('#jqueryui-theme');

    // Store value to local storage
    if (typeof window.localStorage !== undefined) {
      localStorage.setItem('skin', $id);
    }

    // Show preloader
    $('#preloader').show();

    // Load the stylesheets
    var $sStyle = document.createElement('link'),
        $ffStyle = document.createElement('link'),
        $jStyle = document.createElement('link');

    // Set rel attribute
    $($sStyle).attr('rel', 'stylesheet');
    $($ffStyle).attr('rel', 'stylesheet');
    $($jStyle).attr('rel', 'stylesheet');

    // Set href attribute
    $($sStyle).attr('href', '../styles/skins/' + $id + '/spectrum-custom.css');
    $($ffStyle).attr('href', '../styles/skins/' + $id + '/form-framework.min.css');
    $($jStyle).attr('href', '../styles/skins/' + $id + '/jqueryui-theme.min.css');

    spectrum.attr('href', $($sStyle).attr('href'));
    formFramework.attr('href', $($ffStyle).attr('href'));
    jqueryuiTheme.attr('href', $($jStyle).attr('href'));

    // Hide preloader
    $('#preloader').hide();

    e.preventDefault();
  });

  // Body background
  $('.body-bg').on('click', function(e) {
    var $this = $(this),
        $id = $this.attr('id');

    // Store value to local storage
    if (typeof window.localStorage !== undefined) {
      localStorage.setItem('bobg', $id);
    }
    $('body').css({
      'background': 'url(../images/textures/' + $id + '.png) 0 0 repeat'
    });
    e.preventDefault();
  });

  // Reset style switcher
  $('#reset-style-switcher').on('click', function() {
    if (typeof window.localStorage !== undefined) {
      if(localStorage.getItem('skin')) {
        localStorage.removeItem('skin');
      }
      if(localStorage.getItem('bobg')) {
        localStorage.removeItem('bobg');
      }
      $('#spectrum-custom').attr('href', '../styles/vendor/spectrum-custom.css');
      $('#form-framework').attr('href', '../styles/form-framework.min.css');
      $('#jqueryui-theme').attr('href', '../styles/jqueryui-theme.min.css');
      $('body').removeAttr('style');
    }
  });
})(jQuery, this, this.document);
