#!/bin/bash
exec 2>&1
cat <<END
Cache-Control: no-cache
Content-Type: text/plain

END

# Remember that this script is used to check the file exists, changing "Creating file..." will need a change in ajax.js

userId=$(curl -sb "$HTTP_COOKIE" "http://fb.kitten-x.com/getUserId.php")

file=$(find files/ -name ${userId}-*)

if [ -z "$file" ]
then
  echo "Creating file..."
else
  lastLine=$(tail -n 1 "$file")
  newLines=$(wc -l < "$file")
  engDate=$(date -d "$(echo "$lastLine" | cut -d '|' -f 2 )")
  echo "File has $newLines lines, last date is $engDate "
fi
