(function($, window, document, undefined) {
  'use strict';
  $(window).on('load', function() {
    var $mcHeight = $(window).height() - $('#top-bar').height() - 8;
    $('#main-content').css('height', $mcHeight);
    $('#status').fadeOut();
    $('#preloader').delay(500).fadeOut('slow');
  });

  $(window).on('resize', function() {
    var $mcHeight = $(window).height() - $('#top-bar').height() - 8;
    $('#main-content').css('height', $mcHeight);
  });

  // Toogle menu
  $('#toggle-side-menu').on('click', function() {
    $('body').toggleClass('open');
  });

  // Top level links
  $('#side-menu > ul > li > a').on('click', function(e) {
    $(this).next().slideToggle({
      duration: 300,
      easing: 'easeInOutCubic'
    });

    // Change icon value
    var $spanIcon = $(this).find('span'),
        $currentClass = $spanIcon.attr('class');

    if($currentClass === 'icon-plus42') {
      $spanIcon.attr('class', 'icon-minus42');
    } else {
      $spanIcon.attr('class', 'icon-plus42');
    }

    // Prevent default behavior
    e.preventDefault();
  });

  // Submenu
  $('.submenu').find('a').on('click', function(e) {
    var $linkData = $(this).data('form'),
        $parentID = $(this).parent().parent().attr('id'),
        $url;

    if($parentID === 'ajax-forms') {
      $url = 'php/' + $linkData + '.php';
    } else {
      $url = 'examples/' + $linkData + '.html';
    }

    // Send GET request
    $.ajax({
      type: 'GET',
      url: $url,
      success: function() {
        $('#main-content').attr('src', $url);
      }
    });

    // Prevent default behavior
    e.preventDefault();
  });
})(jQuery, this, this.document);
