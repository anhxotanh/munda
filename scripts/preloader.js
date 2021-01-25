(function($, window, document, undefined) {
  'use strict';
  // Preloader
  $(window).on('load', function() {
    if (typeof window.localStorage !== undefined) {
      if(localStorage.getItem('skin')) {
        var $value = localStorage.getItem('skin');
        $('#spectrum-custom').attr('href', '../styles/skins/' + $value + '/spectrum-custom.css');
        $('#form-framework').attr('href', '../styles/skins/' + $value + '/form-framework.min.css');
        $('#jqueryui-theme').attr('href', '../styles/skins/' + $value + '/jqueryui-theme.min.css');
      }

      if(localStorage.getItem('bobg')) {
        var $bgValue = localStorage.getItem('bobg');
        $('body').css({
          'background': 'url(../images/textures/' + $bgValue + '.png) 0 0 repeat'
        });
      }
    }
    $('#status').fadeOut();
    $('#preloader').delay(500).fadeOut('slow');
  });
})(jQuery, this, this.document);
