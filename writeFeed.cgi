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

# call with either "head" or "tail"
function setUnixDate 
{
      line=$($1 -n 1 "$file")
      engDate=$(echo "$line" | cut -d '|' -f 2 )
      unixDate=$(date +%s -d "$(echo $engDate)")
}

# getStatuses, optional parameter $sinceDate
function getStatuses 
{
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
      setUnixDate "tail"
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

# echo $firstdate

# have to do this ahead of the file move
  setUnixDate "head"
  nextDate=$(($unixDate + 1))

  tmp=$(tempfile)

  mv "$file" "$tmp"

  getStatuses $nextDate 

  $(cat "$tmp" >> "$file")

fi

# get older posts
getStatuses

echo "Finished, backup file: <a href=\"$file\">userId:$userId</a>"

exit


