<?php
  session_start();
  include 'includes/functions.php';
  require_once 'vendor/autoload.php';
  // Email and database credentials
  // Change this to the path of your file
  require_once '../../../../CREDS/index.php';

  use Illuminate\Database\Capsule\Manager as DB;
  use Respect\Validation\Validator as v;

  // Connect to database
  $db = new DB;
  $db->addConnection([
      'driver'    => 'mysql',
      'host'      => 'localhost',
      'database'  => DATABASE,
      'username'  => USERNAME,
      'password'  => PASSWORD,
      'charset'   => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'prefix'    => '',
  ]);
  $db->setAsGlobal();

  // Setup PHPMailer
  $mail = new PHPMailer;

  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username = EMAIL;
  $mail->Password = EMAIL_PASSWORD;
  $mail->SMTPSecure = 'ssl';
  $mail->Port = 465;

  // Initialize the errrors array
  $errors = array();

  if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
    $username        = $_POST['username'];
    $email           = $_POST['email'];
    $password        = $_POST['password'];
    $retype_password = $_POST['retype_password'];
    $first_name      = $_POST['first_name'];
    $last_name       = $_POST['last_name'];
    $captcha         = $_POST['captcha'];

    // VALIDATE USERNAME

    // Username is required
    if(v::notEmpty()->validate($username)) {
      // The username must contain alphanumeric characters, musn't contain white spaces and have between 2 and 32 characters
      $username_validator = v::alnum('_')->length(2, 32, true)->noWhitespace();

      try {
        $username_validator->assert($username);
      } catch(\InvalidArgumentException $e) {
         $username_errors = $e->findMessages(array(
          'alnum'        => 'Username must contain only letters, digits and underscores.',
          'length'       => 'Username must have between {{minValue}} and {{maxValue}} characters.',
          'noWhitespace' => 'Username can\'t contain spaces.'
        ));

        foreach ($username_errors as $key => $value) {
          if(v::notEmpty()->validate($value)) {
            $errors['username'][] = $value;
          }
        }
      }

      // Check if username exists
      $username_search = DB::select('SELECT id FROM users WHERE username = ?', array($username));
      if(count($username_search) !== 0) {
        $errors['username'][] = 'The username already exists please choose another username.';
      }
    } else {
      $errors['username'][] = 'The username is required.';
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

      // Check if email exists
      $email_search = DB::select('SELECT id FROM users WHERE email_address = ?', array($email));
      if(count($email_search) !== 0) {
        $errors['email'][] = 'The email already exists please choose another email.';
      }
    } else {
      $errors['email'][] = 'The email address is required.';
    }

    // VALIDATE PASSWORD

    // Password is required
    if(v::notEmpty()->validate($password)) {
      // Password must have at least 8 characters
      $password_validator = v::string()->length(8, null);

      try {
        $password_validator->assert($password);
      } catch(\InvalidArgumentException $e) {
         $password_errors = $e->findMessages(array(
          'length' => 'The password must have at least {{minValue}} characters.'
        ));

        foreach ($password_errors as $key => $value) {
          if(v::notEmpty()->validate($value)) {
            $errors['password'][] = $value;
          }
        }
      }
    } else {
      $errors['password'][] = 'The password is required.';
    }

    // VALIDATE RETYPE PASSWORD

    // Retype password is required
    if(v::notEmpty()->validate($retype_password)) {
      // Retype password must be the same as password
      $retype_password_validator = v::equals($password);

      try {
        $retype_password_validator->assert($retype_password);
      } catch(\InvalidArgumentException $e) {
        $retype_password_errors = $e->findMessages(array(
          'equals' => 'Please enter the same password again.'
        ));

        foreach ($retype_password_errors as $key => $value) {
          if(v::notEmpty()->validate($value)) {
            $errors['retype_password'][] = $value;
          }
        }
      }
    } else {
      $errors['retype_password'][] = 'Retype password is required.';
    }

    // VALIDATE FIRST NAME

    // First name is required
    if(v::notEmpty()->validate($first_name)) {
      // The first name must contain only letters
      $first_name_validator = v::alpha()->length(2, null);

      try {
        $first_name_validator->assert($first_name);
      } catch (\InvalidArgumentException $e) {
        $first_name_errors = $e->findMessages(array(
          'alpha'  => 'The first name must containe only letters',
          'length' => 'The first name must have at least {{minValue}} characters.'
        ));

        foreach ($first_name_errors as $key => $value) {
          if(v::notEmpty()->validate($value)) {
            $errors['first_name'][] = $value;
          }
        }
      }
    } else {
      $errors['first_name'][] = 'The first name is required.';
    }

    // VALIDATE LAST NAME

    // Last name is required
    if(v::notEmpty()->validate($last_name)) {
      // The first name must contain only letters
      $last_name_validator = v::alpha()->length(2, null);

      try {
        $last_name_validator->assert($last_name);
      } catch (\InvalidArgumentException $e) {
        $last_name_errors = $e->findMessages(array(
          'alpha'  => 'The last name must containe only letters',
          'length' => 'The last name must have at least {{minValue}} characters.'
        ));

        foreach ($last_name_errors as $key => $value) {
          if(v::notEmpty()->validate($value)) {
            $errors['last_name'][] = $value;
          }
        }
      }
    } else {
      $errors['last_name'][] = 'The last name is required.';
    }

    // VALIDATE GENDER SELECT MENU

    // Gender is required
    if(v::key('gender')->validate($_POST)) {
      $gender = $_POST['gender'];

      if(v::notEmpty()->validate($gender)) {
        $genders = array('male', 'female');

        // The submitted value must be in the $genders array
        $gender_validator = v::in($genders);

        try {
          $gender_validator->assert($gender);
        } catch(\InvalidArgumentException $e) {
          $gender_errors = $e->findMessages(array(
            'in' => 'Gender must be either male of female.'
          ));

          foreach ($gender_errors as $key => $value) {
            if(v::notEmpty()->validate($value)) {
              $errors['gender'][] = $value;
            }
          }
        }
      } else {
        $errors['gender'][] = 'The gender can\'t be empty.';
      }
    } else {
      $errors['gender'][] = 'The gender is required.';
    }

    // VALIDATE SUSBCRIBE CHECKBOX - OPTIONAL

    // Check for the subscribe key in the $_POST array
    if(v::key('subscribe')->validate($_POST)) {
      $subscribe = $_POST['subscribe'];

      if(v::notEmpty()->validate($subscribe)) {
        // The submitted value must be equal to 'subscribe'
        $subscribe_validator = v::equals('yes');

        try {
          $subscribe_validator->assert($subscribe);
        } catch(\InvalidArgumentException $e) {
          $subscribe_errors = $e->findMessages(array(
            'equals' => 'Invalid subscribe value.'
          ));

          foreach ($subscribe_errors as $key => $value) {
            if(v::notEmpty()->validate($value)) {
              $errors['subscribe'][] = $value;
            }
          }
        }
      } else {
        $errors['subscribe'][] = 'Invalid subscribe value.';
      }
    }

    // VALIDATE TERMS CHECKBOX

    // Agreeing with the terms and conditions is mandatory
    if(v::key('terms')->validate($_POST)) {
      $terms = $_POST['terms'];

      if(v::notEmpty()->validate($terms)) {
        // The submitted value must be equal to 'terms'
        $terms_validator = v::equals('yes');

        try {
          $terms_validator->assert($terms);
        } catch(\InvalidArgumentException $e) {
          $terms_errors = $e->findMessages(array(
            'equals' => 'Invalid terms and conditions value.'
          ));

          foreach ($terms_errors as $key => $value) {
            if(v::notEmpty()->validate($value)) {
              $errors['terms'][] = $value;
            }
          }
        }
      } else {
        $errors['terms'][] = 'Invalid terms and conditions value.';
      }
    } else {
      $errors['terms'][] = 'You must agree with the terms and conditions.';
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

    // IF IT'S AN AJAX REQUEST AND THERE ARE ERRORS
    if(v::key('ajax')->validate($_POST) && count($errors) > 0) {
      echo json_encode($errors);
      exit();
    }

    // IF THERE ARE NO VALIDATION ERRORS
    if(count($errors) === 0) {
      // Hash password
      $hashed_password = password_hash($password, PASSWORD_BCRYPT, array("cost" => 4));

      // Subscribe value
      if(!v::key('subscribe')->validate($_POST)) {
        $subscribe = 'no';
      }

      // Insert user data into the database
      $insert = DB::insert('INSERT INTO users (username, first_name, last_name, gender, email_address, password, subscription, registration_date) values (?, ?, ?, ?, ?, ?, ?, ?)', array($username, $first_name, $last_name, $gender, $email, $hashed_password, $subscribe, new DateTime()));
      if((int)$insert === 1) {
        // Send an email to the user
        $mail->setFrom('rcwthemes@gmail.com', 'rcwthemes');
        $mail->addAddress($email, $last_name . ' ' . $first_name);
        $mail->addAddress('rcwthemes@gmail.com', 'rcwthemes');
        $mail->isHTML(true);
        $mail->Subject = 'Site registration';
        $mail->Body = '<h4>Hello ' . $last_name . ' ' . $first_name . '!</h4><p>Thank you for registering to our site.</p>';

        if(!$mail->send()) {
          if(v::key('ajax')->validate($_POST)) {
            $data = array('errorSend' => 'You have registered but the message couldn\'t be sent.');
            echo json_encode($data);
            exit();
          } else {
            $errors['send'][] = 'You have registered but the message couldn\'t be sent.';
          }
        } else {
          if(v::key('ajax')->validate($_POST)) {
            $data = array('success' => 'You have registered and we\'ve sent you a thank you message.');
            echo json_encode($data);
            exit();
          } else {
            $success = 'You have registered and we\'ve sent you a thank you message.';
            unset($_POST);
          }
        }
      } else {
        if(v::key('ajax')->validate($_POST)) {
          $data = array('errorRegistration' => 'We couldn\'t register your account. Please try again.');
          echo json_encode($data);
          exit();
        } else {
          $errors['registration'][] = 'We couldn\'t register your account. Please try again.';
        }
      }
    }
  }
?>
<!DOCTYPE html><!--[if IE 8]><html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Register form</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon and Apple Icons-->
    <link rel="shortcut icon" href="../favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="../apple-touch-icon-precomposed.png">
    <!-- Google Fonts-->
    <link href="http://fonts.googleapis.com/css?family=Lato:400,700,900,400italic,700italic,900italic" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Playfair+Display:400,700,900,400italic,700italic,900italic" rel="stylesheet">
    <!-- build:css styles/vendor.css-->
    <!-- bower:css-->
    <link rel="stylesheet" href="../styles/vendor/normalize.css">
    <link rel="stylesheet" href="../styles/vendor/grid.css">
    <link rel="stylesheet" href="../styles/icomoon.css">
    <link rel="stylesheet" href="../styles/vendor/spectrum.css">
    <link rel="stylesheet" href="../styles/vendor/spectrum-custom.css" id="spectrum-custom">
    <link rel="stylesheet" href="../styles/vendor/animate.css">
    <link rel="stylesheet" href="../styles/style-switcher.css">
    <!-- endbower-->
    <!-- endbuild-->
    <!-- build:css(.tmp) styles/main.min.css-->
    <link rel="stylesheet" href="../styles/form-framework.min.css" id="form-framework">
    <link rel="stylesheet" href="../styles/jqueryui-theme.min.css" id="jqueryui-theme">
    <!-- endbuild-->
    <!-- build:js scripts/vendor/modernizr.js-->
    <script src="../../bower_components/modernizr/modernizr.js"></script>
    <!-- endbuild-->
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries-->
    <!-- CSS3 pseudo-classes and attribute selectors support in Internet Explorer 6-8--><!--[if lt IE 9]>
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
    <div class="form-container register-form animated fadeInDown">
      <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" id="register-form" class="rcw-form container-fluid">
        <header>
          <h3 class="form-main-heading">Register</h3>
        </header>
        <fieldset class="row">
          <div class="form-group col-md-12">
            <p class="fields-required">Fields marked with * are required.</p>
            <?php
              if(isset($success)):
                echo '<div class="alert alert-success alert-dismissible has-alert-icon ff-rounded lh-margin-t"><span class="alert-icon icon-checkmark-circle"></span>' . $success . '</div>';
              elseif(isset($errors['registration'])):
                echo '<div class="alert alert-danger has-alert-icon ff-rounded lh-margin-t"><span class="alert-icon icon-close2"></span> <strong>Error!</strong> ' . $errors['registration'] . '</div>';
              elseif(isset($errors['send'])):
                echo '<div class="alert alert-danger has-alert-icon ff-rounded lh-margin-t"><span class="alert-icon icon-close2"></span> <strong>Error!</strong> ' . $errors['send'] . '</div>';
              endif;
            ?>
          </div>
          <div class="col-md-12">
            <legend><span>Account details</span></legend>
          </div>
          <div class="col-md-12">
            <div class="row">
              <div class="form-group col-md-6 has-icon-left <?php if(isset($errors['username'])) { echo 'has-error'; } ?>">
                <label for="username" class="sr-only">Username</label>
                <input type="text" name="username" id="username" placeholder="Username *" class="form-control ff-rounded" value="<?php if(isset($_POST['username'])) { echo $_POST['username']; } ?>">
                <span class="icon-user14 icon-left"></span>
                <?php
                  if(isset($errors['username'])):
                    foreach ($errors['username'] as $error):
                      echo '<span class="help-block">' . $error . '</span>';
                    endforeach;
                  endif;
                ?>
              </div>
              <div class="form-group col-md-6 has-icon-left <?php if(isset($errors['email'])) echo 'has-error';?>">
                <label for="email" class="sr-only">Email address</label>
                <input type="text" name="email" id="email" placeholder="Email address *" class="form-control ff-rounded" value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>">
                <span class="icon-envelope7 icon-left"></span>
                <?php
                  if(isset($errors['email'])):
                    foreach ($errors['email'] as $error):
                      echo '<span class="help-block">' . $error . '</span>';
                    endforeach;
                  endif;
                ?>
              </div>
            </div> <!-- end .row -->
          </div> <!-- end .col-md-12 -->
          <div class="col-md-12">
            <div class="row">
              <div class="form-group col-md-6 has-icon-left <?php if(isset($errors['password'])) echo 'has-error';?>">
                <label for="password" class="sr-only">Password</label>
                <input type="password" name="password" id="password" placeholder="Password *" class="form-control ff-rounded">
                <span class="icon-locked5 icon-left"></span>
                <?php
                  if(isset($errors['password'])):
                    foreach ($errors['password'] as $error):
                      echo '<span class="help-block">' . $error . '</span>';
                    endforeach;
                  endif;
                ?>
              </div>
              <div class="form-group col-md-6 has-icon-left <?php if(isset($errors['retype_password'])) echo 'has-error';?>">
                <label for="retype-password" class="sr-only">Retype password</label>
                <input type="password" name="retype_password" id="retype-password" placeholder="Retype password *" class="form-control ff-rounded">
                <span class="icon-locked5 icon-left"></span>
                <?php
                  if(isset($errors['retype_password'])):
                    foreach ($errors['retype_password'] as $error):
                      echo '<span class="help-block">' . $error . '</span>';
                    endforeach;
                  endif;
                ?>
              </div>
            </div> <!-- end .row -->
          </div> <!-- end .col-md-12 -->
        </fieldset>
        <fieldset class="row">
          <div class="col-md-12">
            <legend><span>Personal details</span></legend>
          </div>
          <div class="col-md-12">
            <div class="row">
              <div class="form-group col-md-6 has-icon-left <?php if(isset($errors['first_name'])) echo 'has-error';?>">
                <label for="firstname" class="sr-only">First name</label>
                <input type="text" name="first_name" id="first-name" placeholder="First name *" class="form-control ff-rounded" value="<?php if(isset($_POST['first_name'])) echo $_POST['first_name'];?>">
                <span class="icon-user14 icon-left"></span>
                <?php
                  if(isset($errors['first_name'])):
                    foreach ($errors['first_name'] as $error):
                      echo '<span class="help-block">' . $error . '</span>';
                    endforeach;
                  endif;
                ?>
              </div>
              <div class="form-group col-md-6 has-icon-left <?php if(isset($errors['first_name'])) echo 'has-error';?>">
                <label for="last-name" class="sr-only">Last name</label>
                <input type="text" name="last_name" id="last-name" placeholder="Last name *" class="form-control ff-rounded" value="<?php if(isset($_POST['last_name'])) echo $_POST['last_name']; ?>">
                <span class="icon-user14 icon-left"></span>
                <?php
                  if(isset($errors['last_name'])):
                    foreach ($errors['last_name'] as $error):
                      echo '<span class="help-block">' . $error . '</span>';
                    endforeach;
                  endif;
                ?>
              </div>
            </div> <!-- end .row -->
          </div> <!-- end .col-md-12 -->
          <div class="form-group col-md-12 has-icon-left <?php if(isset($errors['gender'])) echo 'has-error';?>">
            <label for="gender" class="sr-only">Gender</label>
            <select name="gender" id="gender" class="form-control ff-rounded">
              <option value="" selected disabled>Choose gender *</option>
              <option value="male" <?php if(isset($_POST['gender']) && $_POST['gender'] === 'male') echo 'selected';?>>Male</option>
              <option value="female" <?php if(isset($_POST['gender']) && $_POST['gender'] === 'female') echo 'selected';?>>Female</option>
            </select>
            <span class="select-icon icon-arrow-down8 ff-rounded-r"></span>
            <span class="icon-users5 icon-left"></span>
            <?php
              if(isset($errors['gender'])):
                foreach ($errors['gender'] as $error):
                  echo '<span class="help-block">' . $error . '</span>';
                endforeach;
              endif;
            ?>
          </div>
          <div class="form-group col-md-12 <?php if(isset($errors['subscribe'])) echo 'has-error';?>">
            <div class="checkbox-inline">
              <input type="checkbox" name="subscribe" id="subscribe" value="yes" <?php if(isset($_POST['subscribe'])) echo 'checked';?>>
              <label for="subscribe">Subscribe to our newsletter. We won't spam!</label>
            </div>
            <?php
              if(isset($errors['subscribe'])):
                foreach ($errors['subscribe'] as $error):
                  echo '<span class="help-block">' . $error . '</span>';
                endforeach;
              endif;
            ?>
          </div>
          <div class="form-group col-md-12 <?php if(isset($errors['terms'])) echo 'has-error';?>">
            <div class="checkbox-inline">
              <input type="checkbox" name="terms" id="terms" value="yes" <?php if(isset($_POST['terms'])) echo 'checked';?>>
              <label for="terms">I agree with the <a href="#" title="Terms and conditions", target='_blank'>terms and conditions</a>. *</label>
            </div>
            <?php
              if(isset($errors['terms'])):
                foreach ($errors['terms'] as $error):
                  echo '<span class="help-block">' . $error . '</span>';
                endforeach;
              endif;
            ?>
          </div>
          <div class="form-group col-md-12 <?php if(isset($errors['captcha'])) echo 'has-error';?>">
            <label for="captcha" class="sr-only">Captcha</label>
            <div class="captcha-right">
              <input type="text" name="captcha" id="captcha" placeholder="Captcha code *" class="form-control ff-rounded-l">
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
        </fieldset>
        <footer class="ff-rounded-b">
          <button type="submit" name="submit" id="submit" style="margin-right: 10px;" class="btn btn-primary ff-rounded">Contact</button>
          <button type="reset" class="btn btn-default ff-rounded btn-has-icon-left"><span class="btn-icon-left icon-cancel5 ff-rounded-l"></span>Reset</button>
        </footer>
      </form>
    </div>
    <!-- Javascript -->
    <script src="../scripts/plugins/jquery.js"></script>
    <script src="../scripts/plugins/placeholders.jquery.min.js"></script>
    <script src="../scripts/plugins/jquery-ui.js"></script>
    <script src="../scripts/plugins/jquery.ui.touch-punch.js"></script>
    <script src="../scripts/plugins/jquery-ui-timepicker-addon.js"></script>
    <script src="../scripts/plugins/jquery-ui-sliderAccess.js"></script>
    <script src="../scripts/plugins/jquery.mtz.monthpicker.js"></script>
    <script src="../scripts/plugins/jquery.maskedinput.js"></script>
    <script src="../scripts/plugins/spectrum.js"></script>
    <script src="../scripts/plugins/jquery.nicefileinput.min.js"></script>
    <script src="../scripts/plugins/jquery.validate.js"></script>
    <script src="../scripts/plugins/additional-methods.js"></script>
    <script src="../scripts/plugins/jquery.form.js"></script>
    <script src="../scripts/style-switcher.js"></script>
    <script src="../scripts/preloader.js"></script>
    <script src="../scripts/register.js"></script>
  </body>
</html>
