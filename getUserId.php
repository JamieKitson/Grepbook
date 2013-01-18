<?php

//uses the PHP SDK.  Download from https://github.com/facebook/php-sdk
require 'facebook-php-sdk/src/facebook.php'; 

$f = file('secret.php');
$secret = trim($f[1]);

$facebook = new Facebook(array(
  'appId'  => '277863212273184',
  'secret' => $secret
));

$userId = $facebook->getUser();

if (!$userId) { 

  throw new Exception('Not logged in to Facebook.');

} else {

  header("Content-Type: text/plain");

  echo "$userId\n";
  
}

?>
