#!/bin/bash
exec 2>&1
cat <<END
Cache-Control: no-cache
Content-Type: text/plain

END

# Remember that this script is used to check the file exists, changing "Creating file..." will need a change in ajax.js

userId=$(curl -sb "$HTTP_COOKIE" "http://$HTTP_HOST/getUserId.php")

file=$(find files/ -name ${userId}-*)

if [ -n "$file" ]
then
  rm "$file"
  echo "File deleted."
fi


