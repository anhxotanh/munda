<?php
  session_start();
  require_once 'vendor/autoload.php';
  use Respect\Validation\Validator as v;

  if(isset($_POST['captcha'])) {
    if(v::notEmpty()->validate($_POST['captcha'])) {
      if($_SESSION['phrase'] === $_POST['captcha']) {
        echo 'true';
      } else {
        echo 'false';
      }
    } else {
      echo 'false';
    }
  } else {
    echo '<p>Page not found. 404 :(</p>';
  }
?>
