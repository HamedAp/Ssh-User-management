#!/bin/bash
#By Hamed Ap

echo -e "\nPlease Input Panel IP."
read panelip

echo -e "\nPlease Input Token Added In Main Panel."
read token


if command -v apt-get >/dev/null; then
apt update -y &
wait
apt upgrade -y &
wait
apt install apache2 php curl php-mysql php-xml php-curl -y &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/adduser' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/userdel' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/passwd' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/sed' | sudo EDITOR='tee -a' visudo &
wait
systemctl restart apache2 &
wait
systemctl enable apache2 &
wait
elif command -v yum >/dev/null; then
yum update -y &
wait
yum install httpd php php-mysql php-xml mod_ssl php-curl -y &
wait
echo 'apache ALL=(ALL:ALL) NOPASSWD:/usr/sbin/adduser' | sudo EDITOR='tee -a' visudo &
wait
echo 'apache ALL=(ALL:ALL) NOPASSWD:/usr/sbin/userdel' | sudo EDITOR='tee -a' visudo &
wait
echo 'apache ALL=(ALL:ALL) NOPASSWD:/usr/bin/sed' | sudo EDITOR='tee -a' visudo &
wait
echo 'apache ALL=(ALL:ALL) NOPASSWD:/usr/bin/passwd' | sudo EDITOR='tee -a' visudo &
wait
systemctl restart httpd &
wait
systemctl enable httpd


fi


sudo wget -4 -O /var/www/html/syncdb.php https://raw.githubusercontent.com/HamedAp/Ssh-User-management/main/New-Server/syncdb.php
sudo wget -4 -O /var/www/html/adduser https://raw.githubusercontent.com/HamedAp/Ssh-User-management/main/New-Server/adduser
sudo wget -4 -O /var/www/html/delete https://raw.githubusercontent.com/HamedAp/Ssh-User-management/main/New-Server/delete
sudo wget -4 -O /var/www/html/list https://raw.githubusercontent.com/HamedAp/Ssh-User-management/main/New-Server/list

sudo sed -i "s/serverip/$panelip/g" /var/www/html/syncdb.php &
wait 
sudo sed -i "s/servertoken/$token/g" /var/www/html/syncdb.php &
wait 


echo "* * * * * php /var/www/html/syncdb.php >/dev/null 2>&1" | crontab - &
wait


