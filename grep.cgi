#!/bin/bash
exec 2>&1
cat <<END
Cache-Control: no-cache
Content-Type: text/html

END

function postlink 
{
    if [ $(expr length "$postId") -ge 12 ]
    then
      echo '<a href="http://facebook.com/'$userId'/posts/'$postId'" target="_blank" class="datelink">'$1'</a>'
    else
      echo "<span class=\"date\">$date</span>"
    fi
}

userId=$(curl -sb "$HTTP_COOKIE" "http://$HTTP_HOST/getUserId.php")
q="$1"
c=0

for file in $(find files/ -name ${userId}-*)
do
  IFS="$(echo -e "\n\r")"
  for line in $(grep -i "$q" "$file")
  do
    IFS="|"
    set -- $line
    date=$(date +"%d %b %Y %T" -d $2)
    postId=$1; poster=$3; text=$4; link=$5; linkText=$6; comments=$7
    
    postlink $date

    echo "<span class=\"poster\">$poster:</span>"
    
    if [ -z "$link" ] || [ -n "$linkText" ]
    then
      echo "<span class=\"post\">$text</span>"
    fi

    if [ -n "$link" ]
    then
      if [ -z "$linkText" ]
      then
        linkText="$text"
        class="post"
      fi
      echo "<a href=\"$link\" class=\"assoclink $class\">$linkText</a>"
    fi

    if [ -n "$comments" ] && [ $comments -gt 0 ]
    then
      commentstext="$comments Comment"
      if [ $comments -gt 1 ]
      then
        commentstext="${commentstext}s"
      fi
      postlink $commentstext
    fi

    echo "<br>"
    c=$(( $c + 1 ))
  done
  echo "<p>"
  echo "$c results found."
  unset IFS
done
