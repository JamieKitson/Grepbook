<?php

// error_reporting(E_ALL);
// ini_set('display_errors', '1');

define('YOUR_APP_ID', '277863212273184');

//uses the PHP SDK.  Download from https://github.com/facebook/php-sdk
require 'facebook-php-sdk-5a88ed7/src/facebook.php'; 

$facebook = new Facebook(array(
  'appId'  => YOUR_APP_ID,
  'secret' => 'de7c62abf49a9e711c738421a9afd205',
));

$userId = $facebook->getUser();

if (!$userId) { 

  throw new Exception('Not logged in to Facebook.');

} else {

  header("Content-Type: text/plain");

  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  echo "$userId\n";
  
}

?>
