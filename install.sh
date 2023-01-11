#!/bin/bash

if command -v apt-get >/dev/null; then
  apt update -y
  apt install apache2 php zip net-tools -y
  systemctl restart httpd
sudo wget -O /var/www/html/update https://raw.githubusercontent.com/HamedAp/Ssh-User-management/main/p/update -4 &
wait
sudo bash /var/www/html/update &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/adduser' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/userdel' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/sed' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/passwd' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/curl' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/wget' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/unzip' | sudo EDITOR='tee -a' visudo &
wait
chown www-data:www-data /var/www/html/p/*

po=$(cat /etc/ssh/sshd_config | grep "^Port")
port=$(echo "$po" | sed "s/Port //g")
sudo sed -i "/^5829:$port/d" /var/www/html/p/online

elif command -v yum >/dev/null; then
yum update -y
yum install httpd php zip net-tools -y
systemctl restart httpd
sudo wget -O /var/www/html/update https://raw.githubusercontent.com/HamedAp/Ssh-User-management/main/p/update -4 &
wait
sudo bash /var/www/html/update &
wait
echo 'apache ALL=(ALL:ALL) NOPASSWD:/usr/sbin/adduser' | sudo EDITOR='tee -a' visudo &
wait
echo 'apache ALL=(ALL:ALL) NOPASSWD:/usr/sbin/userdel' | sudo EDITOR='tee -a' visudo &
wait
echo 'apache ALL=(ALL:ALL) NOPASSWD:/usr/bin/sed' | sudo EDITOR='tee -a' visudo &
wait
echo 'apache ALL=(ALL:ALL) NOPASSWD:/usr/bin/passwd' | sudo EDITOR='tee -a' visudo &
wait
echo 'apache ALL=(ALL:ALL) NOPASSWD:/usr/bin/curl' | sudo EDITOR='tee -a' visudo &
wait
echo 'apache ALL=(ALL:ALL) NOPASSWD:/usr/bin/wget' | sudo EDITOR='tee -a' visudo &
wait
echo 'apache ALL=(ALL:ALL) NOPASSWD:/usr/bin/unzip' | sudo EDITOR='tee -a' visudo &
wait
chown apache:apache /var/www/html/p/*

po=$(cat /etc/ssh/sshd_config | grep "^Port")
port=$(echo "$po" | sed "s/Port //g")
sudo sed -i "/^5829:$port/d" /var/www/html/p/online

else
  echo "Centos And Ubunto Supported For Now !!"
fi
