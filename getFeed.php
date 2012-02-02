<?php

// Should be called with optional me/feed Graph API params until and/or since.
// To page through older posts use until.
// It seems that to page through newer statuses you have to use both until and since parameters.

define('YOUR_APP_ID', '277863212273184');

//uses the PHP SDK.  Download from https://github.com/facebook/php-sdk
require 'facebook-php-sdk/facebook.php'; 

$facebook = new Facebook(array(
  'appId'  => YOUR_APP_ID,
  'secret' => 'de7c62abf49a9e711c738421a9afd205',
));

$userId = $facebook->getUser();

function ifExists($key, $arr)
{
  if (array_key_exists($key, $arr))
    // I should be encoding pipes here...
    return str_replace("\n", " ", $arr[$key]);
}

function writeLine($post)
{ 
  $ids = explode("_", $post['id']);
  $l = $ids[1];
  $l .= "|";
  if (array_key_exists('created_time', $post))
  {
    $dt = strtotime($post['created_time']);
    // We could just replace T with a space here as the date's already in ISO8601
    // (Linux's date v8.5 doesn't seem to like the T)
    $l .= date("Y-m-d H:i:sO", $dt);
  }
  $l .= "|";
  if (array_key_exists('from', $post))
  {
    $l .= ifExists('name', $post['from']);
  }
  $l .= "|";
  // ...or maybe here
  $l .= ifExists('message', $post);
  $l .= ifExists('story', $post);
  $l .= "|";
  $l .= ifExists('link', $post);
  $l .= "\n";
  echo $l;
  return $dt;
}

if (!$userId) { 

  throw new Exception('Not logged in to Facebook.');

} else {

  header("Content-Type: text/plain");

  $params = '?limit=200';
  
  foreach ($_GET as $key => $value)
  {
    $params .= "&$key=$value";
  }

  $b = $facebook->api('/me/feed'.$params);

  // echo print_r($b);

  foreach($b['data'] as $post)
  {
    $dt = writeLine($post);
  }

}

?>
