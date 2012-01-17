<?php

// Should be called with optional me/feed Graph API params until and/or since.
// To page through older posts use until.
// It seems that to page through newer statuses you have to use both until and since parameters.

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

function ifExists($key, $arr)
{
  if (array_key_exists($key, $arr))
    return $arr[$key];
}

function writeLine($post)
{ 
  $ids = explode("_", $post['id']);
  $l = $ids[1];
  $l = $l."|";
  if (array_key_exists('created_time', $post))
  {
    $dt = strtotime($post['created_time']);
    $l = $l.date('D M d G:i:s O Y', $dt);
  }
  $l = $l."|";
  $l = $l.ifExists('message', $post);
  $l = $l.ifExists('story', $post);
  $l = $l."|";
  $l = $l.ifExists('link', $post);
  $l = $l."\n\r";
  echo $l;
  return $dt;
}

echo print_r($argv);

if (!$userId) { 

  throw new Exception('Not logged in to Facebook.');

} else {

  header("Content-Type: text/plain");

  error_reporting(E_ALL);
  ini_set('display_errors', '1');

  $params = '?limit=500';
  
  foreach ($_GET as $key => $value)
  {
    $params .= "&$key=$value";
  }

  // echo "\n$param\n";

  $b = $facebook->api('/me/feed?'.$params);

  foreach($b['data'] as $post)
  {
    $dt = writeLine($post);
  }

 // echo "\n$param\n";

}

?>
