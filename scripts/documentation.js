(function($, window, document, undefined) {
  'use strict';
  var $window = $(window);
  var $body   = $(document.body);
  var $sideNavContainer = $('.doc-nav-container');
  var SyntaxHighlighter = window.SyntaxHighlighter;
  var smoothScroll = window.smoothScroll;
  var ZeroClipboard = window.ZeroClipboard;

  // Scrollspy
  $body.scrollspy({
    target: '.doc-nav-container'
  });

  $window.on('load', function () {
    $body.scrollspy('refresh');
    $('#status').fadeOut();
    $('#preloader').delay(500).fadeOut('slow');
  });

  // Affix
  $sideNavContainer.affix({
    offset: {
      top: 166
    }
  });

  // Back to top button functionality
  $window.on('scroll', function() {
    var btn = $('.go-top');
    if($(this).scrollTop() > 200) {
      btn.show();
    } else {
      btn.hide();
    }
  });

  $('.go-top').on('click', function(e) {
    e.preventDefault();
    $('html, body').animate({scrollTop: 0}, 700);
  });

  // Initialize smooth scroll
  smoothScroll.init({
    easing: 'easeInOutCubic'
  });

  // Syntax highlighter
  SyntaxHighlighter.defaults['auto-links'] = false;
  SyntaxHighlighter.defaults.toolbar = false;
  SyntaxHighlighter.defaults.gutter = false;
  SyntaxHighlighter.all();

  // jQuery niceScroll
  $('html').niceScroll({
    zindex: '100000',
    cursorwidth: '7px'
  });

  // Toggle code visibility
  var toggleCode = $('.btn-code');
  toggleCode.on('click', function() {
    var $this = $(this),
        $btnText = $this.find('.btn-code-text');
    $this.parent().next().add($this.siblings('.btn-clipboard')).slideToggle({
      easing: 'easeInOutElastic'
    });
    if($btnText.text() === 'Hide code') {
      $btnText.text('Show code');
    } else {
      $btnText.text('Hide code');
    }
  });

  // ZeroClipboard configuration
  ZeroClipboard.config({
    hoverClass: 'btn-clipboard-hover',
    activeClass: 'btn-clipboard-active'
  });

  var zeroClipboard = new ZeroClipboard($('.btn-clipboard')),
      htmlBridge = $('#global-zeroclipboard-html-bridge');

  // Handlers for zeroClipboarb
  zeroClipboard.on({
    ready: function() {
      htmlBridge
        .data('placement', 'top')
        .attr('title', 'Copy to clipboard')
        .tooltip();
    },
    beforecopy: function(e) {
      var theID = $(e.target).parent().next().find('.syntaxhighlighter').attr('id');
      $(e.target).attr('data-clipboard-target', theID);
    },
    aftercopy: function() {
      htmlBridge
        .attr('title', 'Copied!')
        .tooltip('fixTitle')
        .tooltip('show')
        .attr('title', 'Copy to clipboard')
        .tooltip('fixTitle');
    }
  });
})(jQuery, this, this.document);
