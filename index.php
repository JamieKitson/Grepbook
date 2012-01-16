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

function get()
{

       $.ajax({
                url: '/getFeed.php?suntil=since&date=1325326717',
                    success: showFeed,
                        error: 
                                function (xhr, textStatus, thrownError) 
                                        { 
                                                  alert("An error occured contacting " + url + " status " + xhr.status + " error message: \n" + xhr.responseText); 
                                        }
              });
          return false; 
}

function showFeed(s)
{
  alert(s);
}

$(document).ready(function() 
    {
      $('#flickrmorelink').click(get);
            });

  </script>

  </head>
  <body>
    <div id="fb-root"></div>
    <fb:login-button autologoutlink="true" size="large"></fb:login-button>
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
    <button id="flickrmorelink">hello</button>
  </body>
</html>

