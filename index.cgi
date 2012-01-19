#!/bin/bash -e
# vim: set ts=4 sw=4
exec 2>&1
cat <<END
Cache-Control: no-cache
Content-Type: text/plain

hi

END

params=""
newLines=0

userId=$(curl -sb "$HTTP_COOKIE" "http://fb.kitten-x.com/getUserId.php")
file="files/$userId"

echo "<a href=\"http://$file\">userId:$userId</a>"

if [ -f "$file" ]
then
  lastLine=$(tail -n 1 "$file")
  newLines=$(wc -l < "$file")
  echo "File has $newLines lines"
fi

lines=$(( $newLines - 1))

#while [ $newLines -gt $lines ]
for i in {0..2}
do

  lines=$newLines

  if [ -n "$lastLine" ]
  then
    engDate=$(echo "$lastLine" | cut -d '|' -f 2 )
    echo "Getting from $engDate"
    lastdate=$(date +%s -d "$(echo $engDate)")
    params="?until=$lastdate"
    echo $params
  fi

  curl -sb "$HTTP_COOKIE" "http://fb.kitten-x.com/getFeed.php$params" >> $file

  lastLine=$(tail -n 1 "$file")
  
  newLines=$(wc -l < $file)
  echo "File has $newLines lines"

done

    exit


