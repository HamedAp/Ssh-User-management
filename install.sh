#!/bin/bash

po=$(cat /etc/ssh/sshd_config | grep "^Port")
port=$(echo "$po" | sed "s/Port //g")


output=$(mysql -N -e "use ShaHaN; select adminuser from setting;")
if [ "$output" != "" ]; then
echo $output
else




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

fi

ipv4=$(curl -s4m8 ip.gs)
if command -v apt-get >/dev/null; then
apt update -y
apt upgrade -y
apt install apache2 php zip unzip net-tools curl mariadb-server php-mysql php-xml vnstat nethogs -y
link=$(sudo curl -Ls "https://api.github.com/repos/HamedAp/Ssh-User-management/releases/latest" | grep '"browser_download_url":' | sed -E 's/.*"([^"]+)".*/\1/')
sudo wget -O /var/www/html/update.zip $link
sudo unzip -o /var/www/html/update.zip -d /var/www/html/ &
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
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/kill' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/killall' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/lsof' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/lsof' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/htpasswd' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/sed' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/rm' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/crontab' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/mysqldump' | sudo EDITOR='tee -a' visudo &
wait

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
chown www-data:www-data /var/www/html/p/* &
wait
systemctl restart mariadb &
wait

elif command -v yum >/dev/null; then
yum update -y
yum install httpd php zip unzip net-tools curl mariadb-server php-mysql php-xml mod_ssl vnstat nethogs -y
systemctl restart httpd
systemctl restart mariadb &
wait
link=$(sudo curl -Ls "https://api.github.com/repos/HamedAp/Ssh-User-management/releases/latest" | grep '"browser_download_url":' | sed -E 's/.*"([^"]+)".*/\1/')
sudo wget -O /var/www/html/update.zip $link
sudo unzip -o /var/www/html/update.zip -d /var/www/html/ &
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
echo 'apache ALL=(ALL:ALL) NOPASSWD:/usr/bin/kill' | sudo EDITOR='tee -a' visudo &
wait
echo 'apache ALL=(ALL:ALL) NOPASSWD:/usr/bin/killall' | sudo EDITOR='tee -a' visudo &
wait
echo 'apache ALL=(ALL:ALL) NOPASSWD:/usr/bin/lsof' | sudo EDITOR='tee -a' visudo &
wait
echo 'apache ALL=(ALL:ALL) NOPASSWD:/usr/sbin/lsof' | sudo EDITOR='tee -a' visudo &
wait
echo 'apache ALL=(ALL:ALL) NOPASSWD:/usr/bin/htpasswd' | sudo EDITOR='tee -a' visudo &
wait
echo 'apache ALL=(ALL:ALL) NOPASSWD:/usr/bin/sed' | sudo EDITOR='tee -a' visudo &
wait
echo 'apache ALL=(ALL:ALL) NOPASSWD:/usr/bin/rm' | sudo EDITOR='tee -a' visudo &
wait
echo 'apache ALL=(ALL:ALL) NOPASSWD:/usr/bin/crontab' | sudo EDITOR='tee -a' visudo &
wait
echo 'apache ALL=(ALL:ALL) NOPASSWD:/usr/bin/mysqldump' | sudo EDITOR='tee -a' visudo &
wait
po=$(cat /etc/ssh/sshd_config | grep "^Port")
port=$(echo "$po" | sed "s/Port //g")

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
chown apache:apache /var/www/html/p/* &
wait


sudo sed -i "s/apache2/httpd/g" /var/www/html/p/setting.php &
wait
chmod 644 /etc/ssh/sshd_config &
wait

else
  echo "Wait For New Update !!"
fi

mysql -e "create database ShaHaN;" &
wait

mysql -e "CREATE USER '${adminusername}'@'localhost' IDENTIFIED BY '${adminpassword}';" &
wait

mysql -e "GRANT ALL ON *.* TO '${adminusername}'@'localhost';" &
wait




sudo sed -i "s/22/$port/g" /var/www/html/p/config.php &
wait 
sudo sed -i "s/adminuser/$adminusername/g" /var/www/html/p/config.php &
wait 
sudo sed -i "s/adminpass/$adminpassword/g" /var/www/html/p/config.php &
wait 
curl -u "$adminusername:$adminpassword" "http://${ipv4}/p/restoretarikh.php"

cp /var/www/html/p/tarikh /var/www/html/p/backup/tarikh

rm -fr /var/www/html/p/tarikh
echo "5 0 * * * php /var/www/html/p/expire.php >/dev/null 2>&1" | crontab - &
wait

clear
printf "\nPanel Link : http://${ipv4}/p/index.php"
printf "\nUserName : \e[31m${adminusername}\e[0m "
printf "\nPassword : \e[31m${adminpassword}\e[0m "
printf "\nPort : \e[31m${port}\e[0m \n"




