<?php
  session_start();
  require_once 'vendor/autoload.php';
  use Gregwar\Captcha\CaptchaBuilder;

  header('Content-type: image/jpeg');
  $builder = new CaptchaBuilder;
  // Here you can change the dimensions of the captcha image
  $builder->build($width = 116, $height = 44, $font = null)->output();
  $_SESSION['phrase'] = $builder->getPhrase();
?>
