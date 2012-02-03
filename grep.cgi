#!/bin/bash
exec 2>&1
cat <<END
Cache-Control: no-cache
Content-Type: text/html

END

userId=$(curl -sb "$HTTP_COOKIE" "http://fb.kitten-x.com/getUserId.php")
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
    postId=$1; poster=$3; text=$4; link=$5; linkText=$6
    
    if [ $(expr length "$postId") -ge 12 ]
    then
      echo '<a href="http://facebook.com/'$userId'/posts/'$postId'" target="_blank" class="datelink">'$date'</a>'
    else
      echo "<span class=\"date\">$date</span>"
    fi
    
    echo "<span class=\"poster\">$poster:</span>"
    
    if [ -z "$link" ] || [ -n "$linkText" ]
    then
      echo "<span class=\"post\">$text</span>"
    fi

    if [ -n "$link" ]
    then
      echo "<a href=\"$link\" class=\"assoclink"
      if [ -n "$linkText" ]
      then
        echo "\">$linkText"
      else
        echo " post\">$text"
      fi
      echo "</a>"
    fi

    echo "<br>"
    c=$(( $c + 1 ))
  done
  echo "<p>"
  echo "$c results found."
  unset IFS
done
