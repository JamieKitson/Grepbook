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

function lastDate($fn)
{

$line = '';

$f = fopen($fn, 'r');
$cursor = -1;

$i = 0;

while ($char !== false) {
    fseek($f, $cursor--, SEEK_END);
    $char = fgetc($f);
    if ($char == "|")
	$i++; 
    if ($i == 3)
  	break;
    if (($i == 2) && ($char !== "|"))
    	$line = $char . $line;
}

 echo $line;
$line = strtotime($line);
echo "|";
 echo $line;
echo "|";
return $line;


}

function firstDate($fn)
{

$line = '';

$f = fopen($fn, 'r');
$cursor = 0;

$char = fgetc($f);

$i = 0;

while ($char !== false && $char !== "\n" && $char !== "\r" && $i < 3) {
    if ($char == "|")
	$i++; 
    fseek($f, $cursor++);
    $char = fgetc($f);
    if ($i == 2)
  	break;
    if (($i == 1) && ($char !== "|"))
    	$line = $line . $char;
}

//echo "until1";
 echo $line;
$line = strtotime($line);
echo "|";
 echo $line;
echo "|";
return $line;


}

function writeLine($post, $h)
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
	fwrite($h, $l);
	echo $l;
	return $dt;
}

// echo $userId;   

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
//echo $until;
} else {
	$until = '';
}

// while (true)
for ($i = 0; $i < 10; $i++)
{

$b = $facebook->api('/me/feed?limit=1000'.$until);// + $userId);// + '/feed');

// echo print_r($b);
// echo "\n".count($b['data'])."\n";

if (count($b['data']) === 0)
	break;

foreach($b['data'] as $post)
{
/*
	$ids = explode("_", $post['id']);
	$l = (string)$ids[1];
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
*/
	$h = fopen($fn, 'a');
	$dt = writeLine($post, $h);
//	fwrite($h, $l);
	fclose($h);
//	echo $l;
	// ob_flush();
}

$until = '&until='.$dt;

// echo $until;

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

$b = $facebook->api('/me/feed?limit=1000'.$since);

if (count($b['data']) === 0)
	return;

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
