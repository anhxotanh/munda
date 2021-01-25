(function($, window, document, undefined) {
  'use strict';
  // Basic example
  $('#progressbar-1').progressbar({
    value: 37
  });

  // Custom label
  var progressbar = $('#progressbar-2'),
    progressLabel = $('#progressbar-2').find('.progressbar-label');

  progressbar.progressbar({
    value: false,
    change: function() {
      progressLabel.text( progressbar.progressbar('value') + '%');
    },
    complete: function() {
      progressLabel.text('Complete!');
    }
  });
  function progress() {
    var val = progressbar.progressbar('value') || 0;

    progressbar.progressbar('value', val + 2);

    if (val < 99) {
      setTimeout(progress, 80);
    }
  }
  setTimeout(progress, 1000);

  // Dialog download

  function progress3() {
    var val = progressbar3.progressbar('value') || 0;
    progressbar3.progressbar('value', val + Math.floor(Math.random()*3));
    if(val <= 99) {
      progressTimer = setTimeout(progress3, 50);
    }
  }

  function closeDownload() {
    clearTimeout(progressTimer);
    dialog.dialog('option', 'buttons', dialogButtons).dialog('close');
    progressbar3.progressbar('value', false);
    progressLabel3.text('Starting download...');
    downloadButton.focus();
  }

  var progressTimer,
  progressbar3 = $('#progressbar-3'),
  progressLabel3 = $('#progressbar-3').find('.progressbar-label'),
  dialogButtons = [{
    text: 'Cancel download',
    click: closeDownload
  }],
  dialog = $('#dialog').dialog({
    autoOpen: false,
    closeOnEscape: false,
    resizable: false,
    buttons: dialogButtons,
    modal: true,
    open: function() {
      progressTimer = setTimeout(progress3, 1000);
    },
    beforeClose: function() {
      downloadButton.button('option', {
        disabled: false,
        label: 'Start download'
      });
    }
  }),
  downloadButton = $('#download-dialog').button().on('click', function() {
    $(this).button('option', {
      disabled: true,
      label: 'Downloading...'
    });
    dialog.dialog('open');
  });
  progressbar3.progressbar({
    value: false,
    change: function() {
      progressLabel3.text('Current progress: ' +  progressbar3.progressbar('value') + '%');
    },
    complete: function() {
      progressLabel3.text('Complete!');
      dialog.dialog('option', 'buttons', [{
        text: 'Close',
        click: closeDownload
      }]);
      $('.ui-dialog button').last().focus();
    }
  });
  progressbar3.css('margin-top', '10px');
})(jQuery, this, this.document);
