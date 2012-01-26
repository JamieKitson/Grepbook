#!/bin/bash
exec 2>&1
cat <<END
Cache-Control: no-cache
Content-Type: text/plain

END

userId=$(curl -sb "$HTTP_COOKIE" "http://fb.kitten-x.com/getUserId.php")

file=$(find files/ -name ${userId}-*)

if [ -z "$file" ]
then
  echo "Creating file..."
else
  lastLine=$(tail -n 1 "$file")
  newLines=$(wc -l < "$file")
  engDate=$(echo "$lastLine" | cut -d '|' -f 2 )
  echo "File has $newLines lines, last date is $engDate "
fi
