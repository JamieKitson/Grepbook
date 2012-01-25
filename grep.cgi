#!/bin/bash
exec 2>&1
cat <<END
Cache-Control: no-cache
Content-Type: text/plain

END

userId=$(curl -sb "$HTTP_COOKIE" "http://fb.kitten-x.com/getUserId.php")

for file in $(find files/ -name ${userId}-*)
do
  IFS="$(echo -e "\n\r")"
  for line in $(grep -i "$1" $file)
  do
    IFS="|"
    set -- $line
    echo '<a href="http://facebook.com/'$userId'/posts/'$1'" target="_blank">'$2'</a>'
    echo $(echo $3 | sed -r 's/https?:\/\/[^ ]+/<a href="&" target="_blank">&<\/a>/g')
    if [ -n "$4" ]
    then
      echo '<a href="'$4'">Associated link</a>'
    fi
    echo "<br>"
  done
  unset IFS
done
