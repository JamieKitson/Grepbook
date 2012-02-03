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
        {
          stop = true;
          alert("An error occured contacting " + aUrl + " status " + xhr.status + " error message: \n" + xhr.responseText); 
        }
      }
    });
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
  enableGrep();
}

function statFile()
{
  ajaxCall('/stat.cgi', gotStat, false);
  if (!stop)
    setTimeout(statFile, 5000);
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

function getFileExists()
{
  ajaxCall('/stat.cgi', gotFileExists, false);
}

function gotFileExists(s)
{
  if (s.indexOf('Creating') == -1)
  {
    enableGrep();
    // gotStat(s);
  }
}

function enableGrep()
{
  $("#grepText").keydown(
    function(event)
    {
      if(event.keyCode == 13){
        $("#btnGrep").click();
      }
    });
  $('#grepText').attr("disabled", false);
  $('#btnGrep').attr("disabled", false);
}

