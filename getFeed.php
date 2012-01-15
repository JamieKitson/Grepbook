<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

define('YOUR_APP_ID', '277863212273184');

//uses the PHP SDK.  Download from https://github.com/facebook/php-sdk
require 'facebook-php-sdk-5a88ed7/src/facebook.php'; // 'facebook-platform/php/facebook.php';

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

function getDateFromLine($fileName, $startAt, $seek)
{
  $strDate = '';
  $f = fopen($fileName, 'r');
  $pipes = 0;

  do
  {
    fseek($f, $startAt++, $seek);
    $char = fgetc($f);
    if ($char == "|")
      $pipes++; 
    if ($pipes == 2)
      break;
    if (($pipes == 1) && ($char !== "|"))
      $strDate = $strDate . $char;
  }
  while ($char !== false);

  fclose($f);
  $uDate = strtotime($strDate);
  echo "\nstrDate:$strDate\nuDate:$uDate\n";
  return $uDate;
}

function lastDate($fn)
{
  $f = fopen($fn, 'r');
  $cursor = -2;

  do
  {
    fseek($f, $cursor--, SEEK_END);
    $char = fgetc($f);
  }
  while ($char !== "\r" && $char !== "\n" && $char !== false);

  fclose($f);
//  echo "\n$cursor\n";
  return getDateFromLine($fn, $cursor + 1, SEEK_END);
}

function firstDate($fn)
{
  return getDateFromLine($fn, 0, SEEK_SET);
}

function writeLine($post, $fileHandle)
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
  fwrite($fileHandle, $l);
  echo $l;
  return $dt;
}

if (!$userId) { ?>

<html>
  <body>
    <div id="fb-root"></div>
    <fb:login-button></fb:login-button>
    <script>
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '<?= YOUR_APP_ID ?>',
          status     : true, 
          cookie     : true,
          xfbml      : true,
          oauth      : true,
        });

        FB.Event.subscribe('auth.login', function(response) {
          window.location.reload();
        });
      };

      (function(d){
         var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
         js = d.createElement('script'); js.id = id; js.async = true;
         js.src = "//connect.facebook.net/en_US/all.js";
         d.getElementsByTagName('head')[0].appendChild(js);
       }(document));
    </script>
  </body>
</html>

<?php

} else {

header("Content-Type: text/plain");

$fn = 'files/'.$userId;

if (file_exists($fn))
{
	$until = '&until='.lastDate($fn);
} else {
	$until = '';
}

//for ($i = 0; $i < 10; $i++)
while (true)
{

echo "\nuntil:$until\n";

  $b = $facebook->api('/me/feed?limit=1000'.$until);// + $userId);// + '/feed');

  if (count($b['data']) === 0)
    break;

  foreach($b['data'] as $post)
  {
    $f = fopen($fn, 'a');
    $dt = writeLine($post, $f);
    fclose($f);
  }

  $until = '&until='.$dt;

}

if (file_exists($fn))
{
	$since = '&since='.firstDate($fn);
} else {
	$since = '';
}

echo "\nUNTIL-SINCE-$since\n";

for ($i = 0; $i < 10; $i++)
{

  echo "\nsince:$since\n";

$b = $facebook->api('/me/feed?limit=1000'.$since);

if (count($b['data']) === 0)
	break;

$since = '';

foreach($b['data'] as $post)
{
	$h = fopen($fn, 'r+');
	$dt = writeLine($post, $h);
	fclose($h);
	if ($since == '')
		$since = '&since='.$dt;
}


// echo $until;

}

}

// echo print_r($b);

/*
//print_r($b);

?>

    <?php } else { ?>
*/ ?>
