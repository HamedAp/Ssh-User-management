#!/bin/bash
#By Hamed Ap

i=0
while [ $i -lt 12 ]; do 
php /var/www/html/p/kill.php &
  sleep 5
  i=$(( i + 1 ))
done
