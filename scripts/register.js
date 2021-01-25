(function($, window, document, undefined) {
  'use strict';
  // Helper functions for the validate plugin
  function appendLoader(name, position) {
    var $validatedInput = $('[name="' + name + '"]'),
        $inputFormGroup = $validatedInput.parents('.form-group'),
        $html = '<span class="image-loader icon-' + position + '"><img src="../images/arrows.gif" alt="Image loader"></span>';
    $inputFormGroup.addClass('has-icon-' + position).append($html);
    return false;
  }

  function removeLoader(name, position) {
    var $validatedInput = $('[name="' + name + '"]'),
        $inputFormGroup = $validatedInput.parents('.form-group');
    $inputFormGroup.removeClass('has-icon-' + position).find('.image-loader').remove();
    return false;
  }

  function addMessage(type, message) {
    // Create the message
    var $message = $('<div class="alert alert-' + type + ' alert-dismissible has-alert-icon ff-rounded lh-margin-t">' + message + '<button type="button" class="close"><span class="icon-cancel5"></span><span class="sr-only">Close</span></button></div>');
    var $icon;

    // Give the $icon variable a value based on the message type
    switch(type) {
      case 'success':
        $icon = $('<span class="alert-icon icon-checkmark-circle"></span>');
        break;
      case 'danger':
        $icon = $('<span class="alert-icon icon-close2"></span>');
        break;
      case 'warning':
        $icon = $('<span class="alert-icon icon-warning"></span>');
        break;
      default:
        $icon = $('<span class="alert-icon icon-info22"></span>');
    }
    // Prepend the icon to the message
    $message = $message.prepend($icon).css('display', 'none');

    // Append the message to the DOM - NOT SURE ABOUT THIS FIND BETTER SELECTOR
    $('.fields-required').after($message);
    $message.fadeIn(500, 'swing');

    // Remove the message on click
    $('.alert-dismissible').find('.close').on('click', function() {
      $(this).parent().fadeOut(500, 'swing', function() {
        $(this).remove();
      });
    });
  }

  // Get all .help-block elements with a style attribute, get their .form-group parents,
  // remove the has-error class, add the has-success class, go back to the .help-block elements and remove them.
  function cleanUp() {
    $('.help-block[style]').parents('.form-group').removeClass('has-error').addClass('has-success').end().remove();
  }

  // Before adding a message remove all other messages, if they exit
  function removeAlerts(form) {
    form.find('.alert').remove();
  }

  // Rules for the validate plugin
  var ruleList = {
    username: {
      required: true,
      rangelength: [2, 32],
      alphanumeric: true,
      remote: {
        url: 'username-checker.php',
        type: 'post',
        data: {
          username: function() {
            return $('[name="username"]').val();
          }
        },
        beforeSend: function() {
          appendLoader('username', 'right');
        },
        complete: function() {
          removeLoader('username', 'right');
        }
      }
    },
    email: {
      required: true,
      email: true,
      remote: {
        url: 'email-checker.php',
        type: 'post',
        data: {
          email: function() {
            return $('[name="email"]').val();
          }
        },
        beforeSend: function () {
          appendLoader('email', 'right');
        },
        complete: function() {
          removeLoader('email', 'right');
        }
      }
    },
    password: {
      required: true,
      minlength: 8
    },
    'retype_password': {
      required: true,
      equalTo: '#password'
    },
    'first_name': {
      required: true,
      minlength: 2
    },
    'last_name': {
      required: true,
      minlength: 2
    },
    gender: {
      required: true
    },
    terms: {
      required: true
    },
    captcha: {
      required: true,
      remote: {
        url: 'captcha-checker.php',
        type: 'post',
        data: {
          captcha: function() {
            return $('[name="captcha"]').val();
          }
        },
        beforeSend: function () {
          appendLoader('captcha', 'left');
        },
        complete: function() {
          removeLoader('captcha', 'left');
        }
      }
    }
  };

  // Messages for the validate plugin
  var messages = {
    username: {
      required: 'The username is required.',
      rangelength: $.validator.format('Username must have between {0} and {1} characters.'),
      alphanumeric: 'Username must contain only letters, digits and underscores.',
      remote: 'The username already exists please choose another username.'
    },
    email: {
      required: 'The email address is required.',
      email: 'The email address must be valid.',
      remote: 'The email already exists please choose another email.'
    },
    password: {
      required: 'The password is required.',
      minlength: $.validator.format('The password must have at least {0} characters.')
    },
    'retype_password': {
      required: 'Retype password is required.',
      equalTo: 'Please enter the same password again.'
    },
    'first_name': {
      required: 'The first name is required.',
      minlength: $.validator.format('The first name must have at least {0} characters.')
    },
    'last_name': {
      required: 'The last name is required.',
      minlength: $.validator.format('The first name must have at least {0} characters.')
    },
    gender: {
      required: 'The gender is required.'
    },
    terms: {
      required: 'You must agree with the terms and conditions.'
    },
    captcha: {
      required: 'The captcha is required.',
      remote: 'The captcha was wrong. Please try again.'
    }
  };

  var $registerForm = $('#register-form');

  // Initialize the validation plugin
  $registerForm.validate({
    success: function(label) {
      label.parents('.form-group').addClass('has-success');
      label.remove();
    },
    highlight: function(elem) {
      var formGroup = $(elem).parents('.form-group');
      if(formGroup.hasClass('has-success')) {
        formGroup.removeClass('has-success').addClass('has-error');
      } else {
        formGroup.addClass('has-error');
      }
    },
    unhighlight: function(elem) {
      $(elem).parents('.form-group').removeClass('has-error');
    },
    errorPlacement: function(error, elem) {
      $(elem).parents('.form-group').append(error).fadeIn();
    },
    errorElement: 'span',
    errorClass: 'help-block',
    rules: ruleList,
    messages: messages,
    submitHandler: function(form) {
      var $form = $(form),
          $submitButton = $form.find('#submit');
      $form.ajaxSubmit({
        data: {
          ajax: 'yes'
        },
        dataType: 'json',
        beforeSend: function() {
          $form.find('#submit').text('Loading...');
        },
        success: function(data) {
          $.each(data, function(prop, value) {
            switch(prop) {
              case 'success':
                removeAlerts($form);
                cleanUp();
                // Add the message
                addMessage('success', value);
                // Restore the submit button
                $submitButton.removeAttr('disabled').text('Register');
                // Reset the form and remove all .has-success class
                $form.resetForm().find('.has-success').removeClass('has-success');
                break;
              case 'errorSend':
                removeAlerts($form);
                cleanUp();
                // Add the message
                addMessage('danger', value);
                // Restore the submit button
                $submitButton.removeAttr('disabled').text('Register');
                break;
              case 'errorRegistration':
                removeAlerts($form);
                cleanUp();
                // Add the message
                addMessage('danger', value);
                // Restore the submit button
                $submitButton.removeAttr('disabled').text('Register');
                break;
              default:
                // This is for errors that appear and where not detected on the front-end

                // Find the .form-group parent of the element with the name attribute equal to prop value.
                var $formGroup = $('[name="' + prop + '"]').parents('.form-group');

                cleanUp();
                // Remove class has-success and has-error and add class has-error
                $formGroup.removeClass('has-success has-error').addClass('has-error');
                $formGroup.find('.help-block').remove();

                $.each(value, function(i, val) {
                  $formGroup.append('<span class="help-block">' + val + '</span>');
                });
                $submitButton.removeAttr('disabled').text('Register');
            }
          });
        }
      });
    }
  });

  // Captcha reload
  var $captchaImage = $('.captcha-image');
  $captchaImage.on('click', function() {
    $(this).attr('src', 'captcha.php?n=' + Math.random());
    return false;
  });

  // Add captcha tooltip
  $captchaImage.tooltip({
    show: {
      duration: 300,
      effect: 'fadeIn'
    },
    hide: {
      duration: 300,
      effect: 'fadeOut'
    },
    position: {
      my: 'center bottom-10',
      at: 'center top'
    },
    tooltipClass: 'arrow-cb',
    track: false,
    items: '[data-tooltip]',
    content: function() {
      return $(this).attr('data-tooltip');
    }
  });

  // Show captcha tooltip on focus, hide on blur
  $('#captcha').on({
    focus: function() {
      $captchaImage.tooltip('open');
    },
    blur: function() {
      $captchaImage.tooltip('close');
    }
  });
})(jQuery, this, this.document);
