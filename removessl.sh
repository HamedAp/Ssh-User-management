#!/bin/bash
sudo cp /etc/apache2/sites-available/default-ssl.conf.bak /etc/apache2/sites-available/default-ssl.conf
systemctl restart apache2
clear
printf "\n Apache SSL Removed \n"
