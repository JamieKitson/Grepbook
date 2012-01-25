#!/bin/bash
exec 2>&1
cat <<END
Cache-Control: no-cache
Content-Type: text/plain

END

for file in $(find files/ -name ${1}-*)
do

  lastLine=$(tail -n 1 "$file")
  newLines=$(wc -l < "$file")
  engDate=$(echo "$lastLine" | cut -d '|' -f 2 )
  echo "File has $newLines lines, last date is $engDate "

done
