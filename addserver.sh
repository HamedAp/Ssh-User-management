#!/bin/bash
#By Hamed Ap
apt install curl wget -y
yum install curl wget -y
ipv4=$(curl -s ipv4.icanhazip.com)
echo -e "\nPlease Input Panel IP/Domain That Has License."
read panelip

echo -e "\nPlease Input Token Added In Main Panel."
read token


if command -v apt-get >/dev/null; then
apt update -y &
wait
apt upgrade -y &
wait
apt install apache2 php8.1 php8.1-mysql php8.1-xml php8.1-curl cron -y &
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


IonCube=$(php -v)
if [[ $IonCube == *"PHP Loader v12.0.5"* ]]; then
  echo "IonCube Is Installed :)"
else
bash <(curl -Ls https://raw.githubusercontent.com/HamedAp/ioncube-loader/main/install.sh --ipv4)
fi



sudo wget -4 -O /var/www/html/syncdb.php https://raw.githubusercontent.com/HamedAp/Ssh-User-management/main/New-Server/syncdb.php
sudo wget -4 -O /var/www/html/adduser https://raw.githubusercontent.com/HamedAp/Ssh-User-management/main/New-Server/adduser
sudo wget -4 -O /var/www/html/delete https://raw.githubusercontent.com/HamedAp/Ssh-User-management/main/New-Server/delete
sudo wget -4 -O /var/www/html/list https://raw.githubusercontent.com/HamedAp/Ssh-User-management/main/New-Server/list

sudo sed -i "s/serverip/$panelip/g" /var/www/html/syncdb.php &
wait 
sudo sed -i "s/servertoken/$token/g" /var/www/html/syncdb.php &
wait 
chown www-data:www-data /var/www/html/* &
wait

crontab -l | grep -v '/syncdb.php'  | crontab  -

(crontab -l ; echo "* * * * * php /var/www/html/syncdb.php >/dev/null 2>&1" ) | crontab - &



