<?php

//uses the PHP SDK.  Download from https://github.com/facebook/php-sdk
require 'facebook-php-sdk/facebook.php'; 

$facebook = new Facebook(array(
  'appId'  => '277863212273184',
  'secret' => trim(file_get_contents('secret.txt'))
));

$userId = $facebook->getUser();

if (!$userId) { 

  throw new Exception('Not logged in to Facebook.');

} else {

  header("Content-Type: text/plain");

  echo "$userId\n";
  
}

?>
