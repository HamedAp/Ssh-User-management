#!/bin/bash
#By Hamed Ap

i=0
while [ $i -lt 20 ]; do 
php /var/www/html/kill.php &
  sleep 3
  i=$(( i + 1 ))
done
