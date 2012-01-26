#!/bin/bash -e
# vim: set ts=4 sw=4

exec 2>&1
cat <<END
Cache-Control: no-cache
Content-Type: text/plain

END

dir="files/"
userId=$(curl -sb "$HTTP_COOKIE" "http://fb.kitten-x.com/getUserId.php")
file=$(find $dir -name "${userId}-*")

if [ -z "$file" ]
then
  file=${dir}${userId}-$(echo $(od -An -N3 -i /dev/urandom))
fi

# getStatuses, optional parameter $sinceDate
function getStatuses {

  newLines=0
  if [ -f "$file" ]
  then
    newLines=$(wc -l < "$file")
  fi

  lines=$(( $newLines - 1))

  while [ $newLines -gt $lines ]
  do

    lines=$newLines

    params=""

    if [ -f "$file" ]
    then
      lastLine=$(tail -n 1 "$file")
      engDate=$(echo "$lastLine" | cut -d '|' -f 2 )
      unixDate=$(date +%s -d "$(echo $engDate)")
      lastDate=$(($unixDate - 1))
      params="until=$lastDate&"
    fi

    if [ -n "$1" ]
    then
      params="${params}since=$1"
    fi

#  echo $params

    curl -sb "$HTTP_COOKIE" "http://fb.kitten-x.com/getFeed.php?$params" >> $file

    newLines=$(wc -l < $file)

  done

}

# if file exists get newer posts
if [ -f "$file" ]
then

  firstLine=$(head -n 1 "$file")
  engDate=$(echo "$firstLine" | cut -d '|' -f 2 )
  unixDate=$(date +%s -d "$(echo $engDate)")
  nextDate=$(($unixDate + 1))

# echo $firstdate

  tmp=$(tempfile)

  mv "$file" "$tmp"

  getStatuses $nextDate 

  $(cat "$tmp" >> "$file")

fi

# get older posts
getStatuses

echo "Finished, backup file: <a href=\"$file\">userId:$userId</a>"

exit


