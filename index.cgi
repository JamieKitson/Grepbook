#!/bin/bash -e
# vim: set ts=4 sw=4
exec 2>&1
cat <<END
Cache-Control: no-cache
Content-Type: text/plain

hi

END



#echo $(dirname $0)
#SCRIPT_FILENAME='/var/www/fb.kitten-x.com/getFeed.php'
#  feed=$(php-cgi -f "until=1326234060")
#  echo $feed
#  lastline=$(echo "$feed" | tail -n 2)
#  lastdate=$(date +%s -d "$(echo $lastline | cut -d '|' -f 2 )")
#  params="?until=$lastdate"
#  echo $params
#unset SCRIPT_FILENAME
#
#exit

feed="a"
params=""

#while [ -n $feed ]
for i in {0..1}
do
#  IFS=''
#  for line in $(curl -sb "$HTTP_COOKIE" http://fb.kitten-x.com/getFeed.php$params)
#  do
#    feed="$feed\n$line"
#  done
  echo $(curl -sb "$HTTP_COOKIE" http://fb.kitten-x.com/getFeed.php$params)
  userId=$(echo $feed | head -n 1)
  echo "userid:$userId"
  feed=$(echo $feed | sed 1d)
  echo "feed:$feed"
  lastline=$(echo "$feed" | tail -n 2)
  lastdate=$(date +%s -d "$(echo $lastline | cut -d '|' -f 2 )")
  params="?until=$lastdate"
  echo $params
done

#unset IFS

    exit

IFS=\;

for cookie in  $HTTP_COOKIE 
do 
  IFS="="
  set -- $cookie
  if [ $1 = "PHPSESSID" ]
  then
    echo $(curl -b $HTTP_COOKIE http://fb.kitten-x.com/getFeed.php)
    break
  fi
done 

unset IFS

