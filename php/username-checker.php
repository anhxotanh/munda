<?php
  session_start();
  require_once 'vendor/autoload.php';
  require_once '../../../../CREDS/index.php';
  use Illuminate\Database\Capsule\Manager as DB;

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

  if(isset($_POST['username'])) {
    $username = $_POST['username'];
    $match = count(DB::select('SELECT id FROM users WHERE username = ?', array($username)));
    if($match === 0) {
      echo 'true';
    } else {
      echo 'false';
    }
  } else {
    echo '<p>Page not found. 404 :(</p>';
  }
?>
