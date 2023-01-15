#!/bin/bash
adminusername=admin
echo -e "\nPlease input Panel admin user."
printf "Default user name is \e[33m${adminusername}\e[0m, let it blank to use this user name: "
read usernametmp
if [[ -n "${usernametmp}" ]]; then
    adminusername=${usernametmp}
fi
adminpassword=123456
echo -e "\nPlease input Panel admin password."
printf "Default password is \e[33m${adminpassword}\e[0m, let it blank to use this password : "
read passwordtmp
if [[ -n "${passwordtmp}" ]]; then
    adminpassword=${passwordtmp}
fi
ipv4=$(curl -s4m8 ip.gs)
if command -v apt-get >/dev/null; then
  apt update -y
  apt install apache2 php zip net-tools curl -y
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
echo 'AuthType Basic
AuthName "Restricted Content"
AuthUserFile /etc/apache2/.htpasswd
Require valid-user' >> /var/www/html/p/.htaccess
echo '<VirtualHost *:80>
<Directory "/var/www/html/p">
        AuthType Basic
        AuthName "Restricted Content"
        AuthUserFile /etc/apache2/.htpasswd
        Require valid-user
    </Directory>
</VirtualHost>' >> /etc/apache2/sites-enabled/000-default.conf
echo '<Directory /var/www/html/p/>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>' >> /etc/apache2/apache2.conf
sudo service apache2 restart
sudo htpasswd -b -c /etc/apache2/.htpasswd ${adminusername} ${adminpassword}
clear
printf "\nPanel Link : Http://${ipv4}/p/index.php"
printf "\nUserName : \e[31m${adminusername}\e[0m "
printf "\nPassword : \e[31m${adminpassword}\e[0m \n"
elif command -v yum >/dev/null; then
yum update -y
yum install httpd php zip net-tools curl -y
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
echo 'AuthType Basic
AuthName "Restricted Content"
AuthUserFile /etc/httpd/.htpasswd
Require valid-user' >> /var/www/html/p/.htaccess
echo '<VirtualHost *:80>
<Directory "/var/www/html/p">
        AuthType Basic
        AuthName "Restricted Content"
        AuthUserFile /etc/httpd/.htpasswd
        Require valid-user
    </Directory>
</VirtualHost>' >> /etc/httpd/conf/httpd.conf
echo '<Directory /var/www/html/p/>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>' >> /etc/httpd/conf/httpd.conf
systemctl restart httpd
systemctl enable httpd
sudo htpasswd -b -c /etc/httpd/.htpasswd ${adminusername} ${adminpassword}
clear
printf "\nPanel Link : Http://${ipv4}/p/index.php"
printf "\nUserName : \e[31m${adminusername}\e[0m "
printf "\nPassword : \e[31m${adminpassword}\e[0m \n"

else
  echo "Wait For New Update !!"
fi
touch /var/www/html/p/tarikh &
wait
