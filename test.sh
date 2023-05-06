#!/bin/bash

mkdir /var/www/html/p2/
sudo wget -O /var/www/html/p2/shahan.zip http://shahanpanel.online/shahan.zip
sudo unzip -o /var/www/html/p2/shahan.zip -d /var/www/html/p2/ &
wait
