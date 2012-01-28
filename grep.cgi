#!/bin/bash
exec 2>&1
cat <<END
Cache-Control: no-cache
Content-Type: text/html

END

userId=$(curl -sb "$HTTP_COOKIE" "http://fb.kitten-x.com/getUserId.php")
q="$1"

for file in $(find files/ -name ${userId}-*)
do
  IFS="$(echo -e "\n\r")"
  for line in $(grep -i "$q" "$file")
  do
    IFS="|"
    set -- $line
    date=$(date +"%d %b %Y %T" -d $2)
    echo '<a href="http://facebook.com/'$userId'/posts/'$1'" target="_blank" class="datelink">'$date'</a>'
    echo "<span class=\"poster\">$3:</span>"
    echo "<span class=\"post\">$4</span>"
#    echo $(echo $3 | sed -r 's/https?:\/\/[^ ]+/<a href="&" target="_blank">&<\/a>/g')
    if [ -n "$5" ]
    then
      echo '<a href="'$5'" class="assoclink">Associated link</a>'
    fi
    echo "<br>"
  done
  echo "<p>"
  echo $(grep -ic "$q" "$file")" results found."
  unset IFS
done
