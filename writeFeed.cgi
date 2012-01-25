#!/bin/bash -e
# vim: set ts=4 sw=4
exec 2>&1
cat <<END
Cache-Control: no-cache
Content-Type: text/plain

END

dir="files/"
newLines=0

userId=$(curl -sb "$HTTP_COOKIE" "http://fb.kitten-x.com/getUserId.php")
afile=$(find $dir -name "${userId}-*")
if [ -z $afile ]
then
  afile=${dir}${userId}-$(echo $(od -An -N3 -i /dev/urandom))
fi

# call with $file to get statuses, optional second parameter $sinceDate
function getStatuses {

file=$1

if [ -f "$file" ]
then
  lastLine=$(tail -n 1 "$file")
  newLines=$(wc -l < "$file")
fi

lines=$(( $newLines - 1))

while [ $newLines -gt $lines ]
#for i in {0..2}
do

  lines=$newLines

  params=""

  if [ -n "$lastLine" ]
  then
    engDate=$(echo "$lastLine" | cut -d '|' -f 2 )
#   echo "Getting from $engDate"
    lastdate=$(date +%s -d "$(echo $engDate)")
    lastdate=$(($lastdate - 1))
    params="until=$lastdate&"
  fi

  if [ -n "$2" ]
  then
    params="${params}since=$2"
  fi

#  echo $params

  curl -sb "$HTTP_COOKIE" "http://fb.kitten-x.com/getFeed.php?$params" >> $file

  lastLine=$(tail -n 1 "$file")
  
  newLines=$(wc -l < $file)
# echo "File has $newLines lines"

done

}

# get last date
# mv $file $tmp

getStatuses $afile

firstLine=$(head -n 1 "$file")


   engDate=$(echo "$firstLine" | cut -d '|' -f 2 )
    firstdate=$(date +%s -d "$(echo $engDate)")
    firstdate=$(($firstdate + 1))

# echo $firstdate

tmp=$(tempfile)
tmp2=$(tempfile)

getStatuses $tmp $firstdate 

cat $tmp $afile > $tmp2
cp $tmp2 $afile

echo "Finished, backup file: <a href=\"$file\">userId:$userId</a>"

exit


