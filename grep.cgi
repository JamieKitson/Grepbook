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
    postId=$1
    if [ $(expr length "$postId") -ge 12 ]
    then
      echo '<a href="http://facebook.com/'$userId'/posts/'$postId'" target="_blank" class="datelink">'$date'</a>'
    else
      echo "<span class=\"date\">$date</span>"
    fi
    echo "<span class=\"poster\">$3:</span>"
    echo "<span class=\"post\">$4</span>"
#    echo $(echo $3 | sed -r 's/https?:\/\/[^ ]+/<a href="&" target="_blank">&<\/a>/g')
    if [ -n "$5" ]
    then
      text="Associated link"
      if [ -n "$6" ]
      then
        text="$6"
      fi
      echo "<a href=\"$5\" class=\"assoclink\">$text</a>"
    fi
    echo "<br>"
    c=$(( $c + 1 ))
  done
  echo "<p>"
  echo "$c results found."
  unset IFS
done
