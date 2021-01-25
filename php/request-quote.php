<?php
  session_start();
  include 'includes/functions.php';
  require_once 'vendor/autoload.php';

  // Email and database credentials
  // Change this to the path of your file
  require_once '../../../../CREDS/index.php';

  use Respect\Validation\Validator as v;

  // Setup PHPMailer
  $mail = new PHPMailer;

  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username = EMAIL;
  $mail->Password = EMAIL_PASSWORD;
  $mail->SMTPSecure = 'ssl';
  $mail->Port = 465;

  // Configure HTMLPurifier
  $purifier_config = HTMLPurifier_Config::createDefault();
  $purifier_config->set('HTML.Allowed', 'p,a[href],abbr[title],b,blockquote[cite],cite,code,del[datetime],ins,em,i,q[cite],strike,strong');
  $purifier = new HTMLPurifier($purifier_config);

  // Initialize the errors array
  $errors = array();

  if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
    $name         = $_POST['name'];
    $company      = $_POST['company'];
    $email        = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $deadline     = $_POST['deadline'];
    $file         = $_FILES['file'];
    $message      = $_POST['message'];
    $captcha      = $_POST['captcha'];

    // VALIDATE NAME

    // Name is required
    if(v::notEmpty()->validate($name)) {
      // The name must contain only letters and have between 2 and 32 characters
      $name_validator = v::alpha()->length(2, 32, true);

      try {
        $name_validator->assert($name);
      } catch(\InvalidArgumentException $e) {
         $name_errors = $e->findMessages(array(
          'alpha'        => 'The name must contain only letters and spaces.',
          'length'       => 'The name must have between {{minValue}} and {{maxValue}} characters.'
        ));

        foreach ($name_errors as $key => $value) {
          if(v::notEmpty()->validate($value)) {
            $errors['name'][] = $value;
          }
        }
      }
    } else {
      $errors['name'][] = 'The name is required.';
    }

    // VALIDATE COMPANY
    if(v::notEmpty()->validate($company)) {
      // The company name must contain only letters, numbers, dots, spaces and have between 5 and 100 characters
      $company_validator = v::alnum('.')->length(5, 100, true);

      try {
        $company_validator->assert($company);
      } catch(\InvalidArgumentException $e) {
         $company_errors = $e->findMessages(array(
          'alnum'        => 'The company name must contain only letters, numbers, dots and spaces.',
          'length'       => 'The company name must have between {{minValue}} and {{maxValue}} characters.'
        ));

        foreach ($company_errors as $key => $value) {
          if(v::notEmpty()->validate($value)) {
            $errors['company'][] = $value;
          }
        }
      }
    } else {
      $company = '-';
    }

    // VALIDATE EMAIL ADDRESS

    // Email address is required
    if(v::notEmpty()->validate($email)) {
      // The email address must be valid
      $email_validator = v::email();

      try {
        $email_validator->assert($email);
      } catch(\InvalidArgumentException $e) {
        $email_errors = $e->findMessages(array(
          'email' => 'The email address must be valid.'
        ));

        foreach ($email_errors as $key => $value) {
          if(v::notEmpty()->validate($value)) {
            $errors['email'][] = $value;
          }
        }
      }
    } else {
      $errors['email'][] = 'The email address is required.';
    }

    // VALIDATE PHONE NUMBER

    if(v::notEmpty()->validate($phone_number)) {
      // Must be a valid phone number
      $phone_number_validator = v::phone();

      try {
        $phone_number_validator->assert($phone_number);
      } catch(\InvalidArgumentException $e) {
        $phone_number_errors = $e->findMessages(array(
          'phone' => 'The phone number must be valid.'
        ));

        foreach ($phone_number_errors as $key => $value) {
          if(v::notEmpty()->validate($value)) {
            $errors['phone_number'][] = $value;
          }
        }
      }
    } else {
      $phone_number = '-';
    }

    // VALIDATE SERVICE

    // Service is required
    if(v::key('service')->validate($_POST)) {
      $service = $_POST['service'];

      if(v::notEmpty()->validate($service)) {
        $services = array('logo-design', 'graphic-design', 'web-design', 'web-development');

        // The submitted value must be in the $services array
        $service_validator = v::in($services);

        try {
          $service_validator->assert($service);
        } catch(\InvalidArgumentException $e) {
          $service_errors = $e->findMessages(array(
            'in' => 'The service value is not valid.'
          ));

          foreach ($service_errors as $key => $value) {
            if(v::notEmpty()->validate($value)) {
              $errors['service'][] = $value;
            }
          }
        }
      } else {
        $errors['service'][] = 'Please choose a service.';
      }
    } else {
      $errors['service'][] = 'Please choose a service.';
    }

    // VALIDATE BUDGET

    // Budget is required
    if(v::key('budget')->validate($_POST)) {
      $budget = $_POST['budget'];

      if(v::notEmpty()->validate($budget)) {
        $budgets = array('less-1000', '1000-2000', '2001-5000', '5000-plus');

        // The submitted value must be in the $budgets array
        $budget_validator = v::in($budgets);

        try {
          $budget_validator->assert($budget);
        } catch(\InvalidArgumentException $e) {
          $budget_errors = $e->findMessages(array(
            'in' => 'The budget value is not valid.'
          ));

          foreach ($budget_errors as $key => $value) {
            if(v::notEmpty()->validate($value)) {
              $errors['budget'][] = $value;
            }
          }
        }
      } else {
        $errors['budget'][] = 'Please choose a budget.';
      }
    } else {
      $errors['budget'][] = 'Please choose a budget.';
    }

    // VALIDATE DEADLINE

    // Deadline is required
    if(v::notEmpty()->validate($deadline)) {
      // Must be a valid date
      $deadline_validator = v::date('m/d/Y');

      try {
        $deadline_validator->assert($deadline);
      } catch(\InvalidArgumentException $e) {
        $deadline_errors = $e->findMessages(array(
          'date' => 'Enter a valid date with the following format: {{format}}'
        ));

        foreach ($deadline_errors as $key => $value) {
          if(v::notEmpty()->validate($value)) {
            $errors['deadline'][] = $value;
          }
        }
      }
    } else {
      $errors['deadline'][] = 'The deadline is required.';
    }

    // VALIDATE UPLOADED FILE

    if(v::notEmpty()->validate($file['name'])) {
      $file_mime_types = array(
        'pdf' => 'application/pdf',
        'jpg' => 'image/jpeg',
        'png' => 'image/png'
      );
      $file_max_size = 2097152; // 2M
      $file_message = file_upload_validate($file, $file_mime_types, $file_max_size, './uploads');
      if($file_message !== 'success') {
        $errors['file'][] = $file_message;
      }
    }

    // VALIDATE MESSAGE

    // Message is required
    if(v::notEmpty()->validate($message)) {
      // The name must contain have between 10 and 1050 characters
      $message_validator = v::length(10, 1050, true);
      try {
        $message_validator->assert($message);
      } catch(\InvalidArgumentException $e) {
         $message_errors = $e->findMessages(array(
          'length'       => 'The message must have between {{minValue}} and {{maxValue}} characters.'
        ));
        foreach ($message_errors as $key => $value) {
          if(v::notEmpty()->validate($value)) {
            $errors['message'][] = $value;
          }
        }
      }
      // Use the HTML Purifier on the message.
      $message = $purifier->purify($message);
    } else {
      $errors['message'][] = 'The message is required.';
    }

    // VALIDATE CAPTCHA

    // Captcha is required
    if(v::notEmpty()->validate($captcha)) {
      if($_SESSION['phrase'] !== $captcha) {
        $errors['captcha'][] = 'The captcha was wrong. Please try again.';
      }
    } else {
      $errors['captcha'][] = 'The captcha is required.';
    }

    // VALIDATE SEND COPY MAIL CHECKBOX
    if(v::key('send_copy')->validate($_POST)) {
      $send_copy = $_POST['send_copy'];

      if(!v::equals('yes')->validate($send_copy)) {
        $errors['send_copy'][] = 'Invalid value for the "send a copy of the email" checkbox.';
      }
    } else {
      $send_copy = 'no';
    }

    // IF IT'S AN AJAX REQUEST AND THERE ARE ERRORS
    if(v::key('ajax')->validate($_POST) && ($_POST['ajax'] === 'yes') && count($errors) > 0) {
      echo json_encode($errors);
      exit();
    }

    // IF THERE ARE NO VALIDATION ERRORS
    if(count($errors) === 0) {
      $email_body = '<h3>Contact\'s person information</h3>';
      $email_body .= '<p><strong>Name</strong>: ' . $name . '</p>';
      $email_body .= '<p><strong>Company</strong>: ' . $company . '</p>';
      $email_body .= '<p><strong>Email address</strong>: ' . $email . '</p>';
      $email_body .= '<p><strong>Phone</strong>: ' . $phone_number . '</p>';
      $email_body .= '<h3>Project information</h3>';
      $email_body .= '<p><strong>Service</strong>: ' . $service . '</p>';
      $email_body .= '<p><strong>Budget</strong>: ' . $budget . '</p>';
      $email_body .= '<p><strong>Deadline</strong>: ' . $deadline . '</p>';
      $email_body .= '<h3>Message</h3>';
      $email_body .= $message;

      // Mail settings
      $mail->setFrom('rcwthemes@gmail.com', 'rcwthemes');
      $mail->addAddress('rcwthemes@gmail.com', 'rcwthemes');
      $mail->addReplyTo($email, $name);
      if($send_copy === 'yes') {
        $mail->addAddress($email, $name);
      }
      // Attach file if file was uploaded
      if(v::notEmpty()->validate($file['name'])) {
        $ext;
        switch($file['type']) {
          case 'application/pdf':
            $ext = '.pdf';
            break;
          case 'image/png':
            $ext = '.png';
            break;
          case 'image/jpeg':
            $ext = '.jpg';
            break;
          default:
            $ext = '';
        }

        if($ext !== '') {
          $mail->addAttachment($file['tmp_name'], 'project-file' . $ext);
        }
      }
      $mail->isHTML(true);
      $mail->Subject = 'Quote request: ' . $name;
      $mail->Body = $email_body;

      // Send mail
      if(!$mail->send()) {
        if(v::key('ajax')->validate($_POST)) {
          $data = array('errorSend' => 'Unfortunately, the message was not sent. Please try again.');
          echo json_encode($data);
          exit();
        } else {
          $errors['send'] = 'Unfortunately, the message was not sent. Please try again.';
        }
      } else {
        if(v::key('ajax')->validate($_POST)) {
          $data = array('success' => 'Thank you for contacting us. I will get back in touch ass soon as possible.');
          echo json_encode($data);
          exit();
        } else {
          $success = 'Thank you for contacting us. I will get back in touch ass soon as possible.';
          unset($_POST);
        }
      }
    }
  }
?>
<!DOCTYPE html><!--[if IE 8]><html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>--> <html class="no-js"> <!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Request quote form</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon and Apple Icons-->
    <link rel="shortcut icon" href="../favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="../apple-touch-icon-precomposed.png">
    <!-- Google Fonts-->
    <link href="http://fonts.googleapis.com/css?family=Lato:400,700,900,400italic,700italic,900italic" rel="stylesheet">
    <!-- Vendor stylesheets-->
    <link rel="stylesheet" href="../styles/vendor/normalize.css">
    <link rel="stylesheet" href="../styles/vendor/grid.css">
    <link rel="stylesheet" href="../styles/icomoon.css">
    <link rel="stylesheet" href="../styles/vendor/spectrum.css">
    <link rel="stylesheet" href="../styles/vendor/spectrum-custom.css" id="spectrum-custom">
    <link rel="stylesheet" href="../styles/vendor/raty-custom.css">
    <link rel="stylesheet" href="../styles/vendor/animate.css">
    <link rel="stylesheet" href="../styles/style-switcher.css">
    <!-- Main stylesheet-->
    <link rel="stylesheet" href="../styles/form-framework.min.css" id="form-framework">
    <link rel="stylesheet" href="../styles/jqueryui-theme.min.css" id="jqueryui-theme">
    <!-- build:js scripts/vendor/modernizr.js-->
    <script src="../../bower_components/modernizr/modernizr.js"></script>
    <!-- endbuild-->
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries-->
    <!-- CSS3 pseudo-classes and attribute selectors support in Internet Explorer 6-8-->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/mootools/1.5.0/mootools-core-full-compat.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/selectivizr/1.0.2/selectivizr-min.js"></script>
    <![endif]-->
  </head>
  <body class="example">
    <div id="preloader" class="preloader">
      <div id="status" class="status"></div>
    </div>
    <div class="style-switcher ff-rounded-etr">
      <button type="button" id="switcher-toggle" class="switcher-toggle ff-rounded-r"><span class="icon-gear"></span></button>
      <h3>Change styles</h3>
      <h5>Skin</h5>
      <ul>
        <li><a id="carrot" href="#" title="Carrot" data-tooltip="Carrot" class="carrot skin"><span class="sr-only">Carrot</span></a></li>
        <li><a id="greensea" href="#" title="Greensea" class="greensea skin"><span class="sr-only">Greensea</span></a></li>
        <li><a id="pumpkin" href="#" title="Pumpkin" class="pumpkin skin"><span class="sr-only">Pumpkin</span></a></li>
        <li><a id="sunflower" href="#" title="Sunflower" class="sunflower skin"><span class="sr-only">Sunflower</span></a></li>
        <li><a id="turquoise" href="#" title="Turquoise" class="turquoise skin"><span class="sr-only">Turquoise</span></a></li>
      </ul>
      <h5>Body background</h5>
      <ul>
        <li><a id="food" href="#" title="Food" class="food body-bg"><span class="sr-only">Food</span></a></li>
        <li><a id="black-linen-v2" href="#" title="Black linen v2" class="black-linen-v2 body-bg"><span class="sr-only">Black linen v2</span></a></li>
        <li><a id="low-contrast-linen" href="#" title="Low contrast linen" class="low-contrast-linen body-bg"><span class="sr-only">Low contrast linen</span></a></li>
        <li><a id="purty-wood" href="#" title="Purty wood" class="purty-wood body-bg"><span class="sr-only">Purty wood</span></a></li>
        <li><a id="shattered" href="#" title="Shattered" class="shattered body-bg"><span class="sr-only">Shattered</span></a></li>
      </ul>
      <div class="text-right">
        <button type="button" id="reset-style-switcher" class="reset-style-switcher ff-rounded">Reset</button>
      </div>
    </div>
    <div class="form-container request-quote-form animated fadeInDown">
      <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data" id="request-quote-form" class="rcw-form container-fluid" novalidate>
        <header>
          <h3 class="form-main-heading">Request quote</h3>
        </header>
        <fieldset class="row">
          <div class="col-md-12 form-group">
            <p class="fields-required">Fields marked with * are required.</p>
            <?php
              if(isset($success)):
                echo '<div class="alert alert-success alert-dismissible has-alert-icon ff-rounded lh-margin-t"><span class="alert-icon icon-checkmark4"></span>' . $success . '</div>';
              elseif(isset($errors['send'])):
                echo '<div class="alert alert-danger has-alert-icon ff-rounded lh-margin-t"><span class="alert-icon icon-exclamation-circle"></span> <strong>Error!</strong> ' . $errors['send'] . '</div>';
              endif;
            ?>
          </div>
          <div class="col-md-12">
            <legend>
              <span>Your details</span>
            </legend>
          </div>
          <div class="col-md-12">
            <div class="row">
              <div class="form-group col-md-6 has-icon-right <?php if(isset($errors['name'])) echo 'has-error';?>">
                <label for="name", class="sr-only">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" placeholder="Name *" class="form-control ff-rounded" value="<?php if(isset($_POST['name'])) echo $_POST['name'];?>">
                <span class="icon-user14 icon-right"></span>
                <?php
                  if(isset($errors['name'])):
                    foreach ($errors['name'] as $error):
                      echo '<span class="help-block">' . $error . '</span>';
                    endforeach;
                  endif;
                ?>
              </div>
              <div class="form-group col-md-6 has-icon-right <?php if(isset($errors['company'])) echo 'has-error';?>">
                <label for="company", class="sr-only">Company</label>
                <input type="text" name="company" id="company" placeholder="Company" class="form-control ff-rounded" value="<?php if(isset($_POST['company'])) echo $_POST['company'];?>">
                <span class="icon-suitcase3 icon-right"></span>
                <?php
                  if(isset($errors['company'])):
                    foreach ($errors['company'] as $error):
                      echo '<span class="help-block">' . $error . '</span>';
                    endforeach;
                  endif;
                ?>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="row">
              <div class="form-group col-md-6 has-icon-right <?php if(isset($errors['email'])) echo 'has-error';?>">
                <label for="email" class="sr-only">Email address <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" placeholder="Email address *" class="form-control ff-rounded" value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>">
                <span class="icon-envelope7 icon-right"></span>
                <?php
                  if(isset($errors['email'])):
                    foreach ($errors['email'] as $error):
                      echo '<span class="help-block">' . $error . '</span>';
                    endforeach;
                  endif;
                ?>
              </div>
              <div class="form-group col-md-6 has-icon-right <?php if(isset($errors['phone_number'])) echo 'has-error';?>">
                <label for="phone-number" class="sr-only">Phone number</label>
                <input type="text" name="phone_number" id="phone-number" placeholder="Phone number" class="form-control ff-rounded" value="<?php if(isset($_POST['phone_number'])) echo $_POST['phone_number'];?>">
                <span class="icon-phone32 icon-right"></span>
                <?php
                  if(isset($errors['phone_number'])):
                    foreach ($errors['phone_number'] as $error):
                      echo '<span class="help-block">' . $error . '</span>';
                    endforeach;
                  else:
                    echo '<span class="help-block">Phone number example: (123)456-7890</span>';
                  endif;
                ?>
              </div>
            </div>
          </div>
        </fieldset>
        <fieldset class="row">
          <div class="col-md-12">
            <legend><span>Project information</span></legend>
          </div>
          <div class="form-group col-md-6 <?php if(isset($errors['service'])) echo 'has-error';?>">
            <label for="service" class="sr-only">Service <span class="text-danger">*</span></label>
            <select name="service" id="service" class="form-control ff-rounded">
              <option value="" selected disabled>Choose service *</option>
              <option value="logo-design" <?php if(isset($_POST['service']) && $_POST['service'] === 'logo-design') echo 'selected';?>>Logo design</option>
              <option value="graphic-design" <?php if(isset($_POST['service']) && $_POST['service'] === 'graphic-design') echo 'selected';?>>Graphic design</option>
              <option value="web-design" <?php if(isset($_POST['service']) && $_POST['service'] === 'web-design') echo 'selected';?>>Web design</option>
              <option value="web-development" <?php if(isset($_POST['service']) && $_POST['service'] === 'web-development') echo 'selected';?>>Web development</option>
            </select>
            <span class="select-icon icon-arrow-down8 ff-rounded-r"></span>
            <?php
              if(isset($errors['service'])):
                foreach ($errors['service'] as $error):
                  echo '<span class="help-block">' . $error . '</span>';
                endforeach;
              endif;
            ?>
          </div>
          <div class="form-group col-md-6 <?php if(isset($errors['budget'])) echo 'has-error';?>">
            <label for="budget" class="sr-only">Budget <span class="text-danger">*</span></label>
            <select name="budget" id="budget" class="form-control ff-rounded">
              <option value="" selected disabled>Your budget *</option>
              <option value="less-1000" <?php if(isset($_POST['budget']) && $_POST['budget'] === 'less-1000') echo 'selected';?>>Less than $1000</option>
              <option value="1000-2000" <?php if(isset($_POST['budget']) && $_POST['budget'] === '1000-2000') echo 'selected';?>>Between $1000 and $2000</option>
              <option value="2001-5000" <?php if(isset($_POST['budget']) && $_POST['budget'] === '2001-5000') echo 'selected';?>>Between $2001 and $5000</option>
              <option value="5000-plus" <?php if(isset($_POST['budget']) && $_POST['budget'] === '5000-plus') echo 'selected';?>>More than $5000</option>
            </select>
            <span class="select-icon icon-arrow-down8 ff-rounded-r"></span>
            <?php
              if(isset($errors['budget'])):
                foreach ($errors['budget'] as $error):
                  echo '<span class="help-block">' . $error . '</span>';
                endforeach;
              endif;
            ?>
          </div>
          <div class="form-group col-md-12 has-icon-right <?php if(isset($errors['deadline'])) echo 'has-error';?>">
            <label for="deadline">Deadline <span class="text-danger sr-only">*</span></label>
            <input type="text" name="deadline" id="deadline" placeholder="Pick a date *" class="form-control ff-rounded" value="<?php if(isset($_POST['deadline'])) echo $_POST['deadline'];?>">
            <span class="icon-calendar4 icon-right"></span>
            <?php
              if(isset($errors['deadline'])):
                foreach ($errors['deadline'] as $error):
                  echo '<span class="help-block">' . $error . '</span>';
                endforeach;
              else:
                echo '<span class="help-block">Date format: 12/31/2015.</span>';
              endif;
            ?>
          </div>
          <div class="form-group col-md-12 <?php if(isset($errors['file'])) echo 'has-error';?>">
            <label for="file" class="sr-only">Project related file</label>
            <div>
              <input type="file" name="file" id="file">
            </div>
            <?php
              if(isset($errors['file'])):
                foreach ($errors['file'] as $error):
                  echo '<span class="help-block">' . $error . '</span>';
                endforeach;
              else:
                echo '<span class="help-block">Upload a project related file (2M max). Accepted formats: PDF, JPG and PNG.</span>';
              endif;
            ?>
          </div>
          <div class="form-group col-md-12 has-icon-right <?php if(isset($errors['message'])) echo 'has-error';?>">
            <label for="message" class="sr-only">Your message <span class="text-danger">*</span></label>
            <textarea name="message" cols="30" rows="6" id="message" placeholder="Message *" class="form-control ff-rounded"><?php if(isset($_POST['message'])) echo $_POST['message'];?></textarea>
            <span class="icon-pen3 icon-right"></span>
            <?php
              if(isset($errors['message'])):
                foreach ($errors['message'] as $error):
                  echo '<span class="help-block">' . $error . '</span>';
                endforeach;
              else:
                echo '<span class="help-block">You may use these HTML tags and attributes: &lt;a href=&quot;&quot; title=&quot;&quot;&gt;, &lt;abbr title=&quot;&quot;&gt;, &lt;b&gt;, &lt;blockquote cite=&quot;&quot;&gt;, &lt;cite&gt;, &lt;code&gt;, &lt;del datetime=&quot;&quot;&gt;,&lt;ins&gt;, &lt;em&gt;, &lt;i&gt;, &lt;q cite=&quot;&quot;&gt;, &lt;strike&gt;, &lt;strong&gt;.</span>';
              endif;
            ?>
          </div>
          <div class="form-group col-md-12 <?php if(isset($errors['captcha'])) echo 'has-error';?>">
            <label for="captcha" class="sr-only">Captcha <span class="text-danger">*</span></label>
            <div class="captcha-right">
              <input type="text" name="captcha" id="captcha" placeholder="Captcha *" class="form-control ff-rounded-l">
              <img src="captcha.php" alt="Captcha" class="captcha-image ff-rounded-r" data-tooltip="Click to refresh">
            </div>
            <?php
              if(isset($errors['captcha'])):
                foreach ($errors['captcha'] as $error):
                  echo '<span class="help-block">' . $error . '</span>';
                endforeach;
              endif;
            ?>
          </div>
          <div class="form-group col-md-12 <?php if(isset($errors['send_copy'])) echo 'has-error';?>">
            <div class="checkbox-inline">
              <input type="checkbox" name="send_copy" id="send-copy" value="yes" <?php if(isset($_POST['send_copy']) && $_POST['send_copy'] === 'yes') echo 'checked';?>>
              <label for="send-copy">Send a copy of the email to me</label>
            </div>
            <?php
              if(isset($errors['send_copy'])):
                foreach ($errors['send_copy'] as $error):
                  echo '<span class="help-block">' . $error . '</span>';
                endforeach;
              endif;
            ?>
          </div>
        </fieldset>
        <footer class="ff-rounded-b">
          <button type="submit" name="submit" id="submit" style="margin-right: 10px;" class="btn btn-primary ff-rounded">Request quote</button>
          <button type="reset" class="btn btn-default ff-rounded">Reset</button>
        </footer>
      </form>
    </div>
    <!-- Javascript -->
    <script src="../scripts/plugins/jquery.js"></script>
    <script src="../scripts/plugins/jquery.easing.1.3.js"></script>
    <script src="../scripts/plugins/jquery-ui.js"></script>
    <script src="../scripts/plugins/jquery-ui-timepicker-addon.js"></script>
    <script src="../scripts/plugins/jquery.mtz.monthpicker.js"></script>
    <script src="../scripts/plugins/jquery-ui-sliderAccess.js"></script>
    <script src="../scripts/plugins/jquery.maskedinput.js"></script>
    <script src="../scripts/plugins/spectrum.js"></script>
    <script src="../scripts/plugins/jquery.raty.js"></script>
    <script src="../scripts/plugins/jquery.nicescroll.js"></script>
    <script src="../scripts/plugins/jquery.nicefileinput.min.js"></script>
    <script src="../scripts/plugins/placeholders.min.js"></script>
    <script src="../scripts/plugins/jquery.validate.js"></script>
    <script src="../scripts/plugins/additional-methods.js"></script>
    <script src="../scripts/plugins/jquery.form.js"></script>
    <script src="../scripts/style-switcher.js"></script>
    <script src="../scripts/preloader.js"></script>
    <script src="../scripts/request-quote.js"></script>
  </body>
</html>
