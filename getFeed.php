<?php

# Called with no arguments: will bring back everything in a user's feed 
# Called with until/since date: will bring back everything until/since unix date stamp
# Facebook seems to limit data to one year.

/*
if (($argc != 3) && ($argc != 1))
{
  echo $argv;
  exit('Must be called with zero or two arguments: "since"/"until" and a unix date stamp.');
}

if ($argc == 3)
{
  if (($argv[1] !== "since") && ($argv[1] != "until"))
    exit('First parameter must be either "since" or "until".');
  if (intval($argv[2]) == 0)
    exit('Second parameter must be a unix date stamp, ie, an integer.');
}
*/

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
  $l = $l."\n";
  echo $l;
  return $dt;
}

if (!$userId) { 

  throw new Exception('Not logged in to Facebook.');

} else {

  header("Content-Type: text/plain");

error_reporting(E_ALL);
ini_set('display_errors', '1');

  if (array_key_exists('suntil', $_GET))
  {
    $suntil = $_GET['suntil'];
    $inDate = $_GET['date']; 
    $param = "$suntil=$inDate";
  } else {
    $suntil = "until";
    $param = "";
  }

  // echo "\n$param\n";

  while (true)
  {

    $b = $facebook->api('/me/feed?limit=500&'.$param);

    if (count($b['data']) === 0)
      break;

    $firstDate = '';

    foreach($b['data'] as $post)
    {
      $dt = writeLine($post);
      if ($firstDate == '')
        $firstDate = $dt;
    }

    $lastDate = $dt;

    if ($suntil == "until")
    {
      $param = "until=$dt";
    } else {
      $param = "until=$dt&since=$inDate";
    }

    // echo "\n$param\n";

  }

}

?>
