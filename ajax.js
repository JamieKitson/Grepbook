var stop;

function go()
{
  $('#output').html('');
  $('#btnBackup').attr("disabled", true);
  stop = false;
  getFeed();
  doDots();
  statFile();
}

function doDots()
{
  if (stop)
    return;
  $('#dots').append('.');
  setTimeout(doDots, 1000);
}

function ajaxCall(aUrl, sucFunc, stopOnErr)
{
  $.ajax({
    url: aUrl,
    success: sucFunc,
    error: 
      function (xhr, textStatus, thrownError) 
      { 
        if (stopOnErr)
          stop = true;
        alert("An error occured contacting " + aUrl + " status " + xhr.status + " error message: \n" + xhr.responseText); 
      }
    });
}

function statFile()
{
  ajaxCall('/stat.cgi', gotStat, false);
  if (stop)
    return;
  setTimeout(statFile, 5000);
}


function getFeed()
{
  ajaxCall('/writeFeed.cgi', gotFeed, true);
}

function gotFeed(s)
{
  stop = true;
  $('#btnBackup').attr("disabled", false);
  appendLine(s);
  statFile();
}

function gotStat(s)
{
  $('#stat').html(s);
}

function appendLine(s)
{
  $('#output').html(s);
}

function grep()
{
  stop = false;
  doDots();
  ajaxCall('/grep.cgi?' + $('#grepText').val(), gotGrep, true);
}

function gotGrep(s)
{
  stop = true;
  $('#grep').html(s);
}
