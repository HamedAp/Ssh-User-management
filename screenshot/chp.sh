#!/bin/bash
clear
ipv4=$(curl -s ipv4.icanhazip.com)
panelPort=80
echo -e "\nPlease input Panel Port ."
printf "Default Port is \e[33m${panelPort}\e[0m, let it blank to use this Port: "
read panelPortt
   echo "<VirtualHost 0.0.0.0:$panelPortt>
        ServerAdmin ShaHaN@localhost
        DocumentRoot /var/www/html/
        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined
        <Directory '/var/www/html/'>
            AllowOverride All
        </Directory>
    </VirtualHost>" > /etc/apache2/sites-available/000-default.conf

   echo "Listen 0.0.0.0:$panelPortt
<IfModule ssl_module>
        Listen 443
</IfModule>

<IfModule mod_gnutls.c>
        Listen 443
</IfModule>" > /etc/apache2/ports.conf


ufw allow $panelPortt/tcp
systemctl restart apache2 

sudo sed -i "s/80/$panelPortt/g" /var/www/html/p/config.php &
wait 


clear 
echo -e "\nPanel Port Changed To : $panelPortt"
echo -e "\nPanel Address : http://$ipv4:$panelPortt/p/index.php"
echo -e "\n"
