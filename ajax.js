var stop;

function go()
{
  $('#output').html('');
  $('#btnBackup').attr("disabled", true);
  stop = false;
  statFile();
  getFeed();
  doDots();
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
  if (stop)
    return;
  ajaxCall('/stat.cgi', gotStat, false);
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
  ajaxCall('/grep.cgi?' + ('#grepText').val(), gotGrep, true);
}

function gotGrep(s)
{
  stop = true;
  $('#grep').html(s);
}
