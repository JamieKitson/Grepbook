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

?>

<html>
  <head>
    <script src="http://code.jquery.com/jquery-1.5.2.min.js"></script>
    <script type="text/javascript">

var stop, userID;

function go()
{

    stop = false;
    stat();
    get();
    dot();
}

function dot()
{
  if (stop)
    return;
  $('#dots').append('.');
  setTimeout(dot, 1000);
}

function stat()
{
  if (stop)
    return;
       $.ajax({
                url: '/stat.cgi?' + userID,
                    success: gotStat,
                        error: 
                                function (xhr, textStatus, thrownError) 
                                        { 
                                                  alert("An error occured contacting " + url + " status " + xhr.status + " error message: \n" + xhr.responseText); 
                                        }
              });
    setTimeout(stat, 10000);
}


function get()
{

       $.ajax({
                url: '/writeFeed.cgi',
                    success: got,
                        error: 
                                function (xhr, textStatus, thrownError) 
                                        { 
                                        stop = true;
                                                  alert("An error occured contacting " + url + " status " + xhr.status + " error message: \n" + xhr.responseText); 
                                        }
              });
          return false; 
}

function got(s)
{
  stop = true;
  appendLine(s);
}

function gotStat(s)
{
  $('#info').html(s);
}

function appendLine(s)
{
  $('#output').html(s);
}

function showFeed(s)
{
  //alert(s);
  $('#output').append(s);
}

$(document).ready(function() 
    {
      $('#flickrmorelink').click(get);
            });

  </script>

  </head>
  <body>
    <div id="intro">
      Once you have logged in click go to start backing up your Facebook feed. 
      Backup is done in batches of 200 which will take 10-20 seconds each. 
      Backup file line count and last status date will be updated at 10 second intervals.
      Once the backup is complete a link to your backup file will be shown.
    </div>
    <div id="fb-root"></div>
    <fb:login-button autologoutlink="true" size="large" data-scope="read_stream"></fb:login-button>
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

      FB.getLoginStatus(function(response) {

        if (response.status === 'connected') 
        {
          userID = response.authResponse.userID;
          $('#go').click(go);
          $('#go').attr("disabled", false);
        }
      } );


      };

      (function(d){
         var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
         js = d.createElement('script'); js.id = id; js.async = true;
         js.src = "//connect.facebook.net/en_US/all.js";
         d.getElementsByTagName('head')[0].appendChild(js);
       }(document));
    </script>
    <button id="go" disabled="disabled">go</button>
    <div id="dots" ></div>
    <div id="info" ></div>
    <div id="output" ></div>
  </body>
</html>

