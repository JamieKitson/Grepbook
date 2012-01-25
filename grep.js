var stop;
// var userID;

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
    url: '/stat.cgi', // ?' + userID,
    success: gotStat,
    error: 
      function (xhr, textStatus, thrownError) 
      { 
        alert("An error occured contacting " + url + " status " + xhr.status + " error message: \n" + xhr.responseText); 
      }
    });
  setTimeout(stat, 5000);
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

function grep()
{
  stop = false;
  dot();
  $.ajax({
    url: '/grep.cgi?' + $('#grepText').val(),
    success: gotGrep,
    error: 
      function (xhr, textStatus, thrownError) 
      { 
        stop = true;
        alert("An error occured contacting " + url + " status " + xhr.status + " error message: \n" + xhr.responseText); 
      }
    });
}

function gotGrep(s)
{
  stop = true;
  $('#grep').html(s);
}
