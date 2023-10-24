#!/bin/bash
printshahan() {
    text="$1"
    delay="$2"
    for ((i=0; i<${#text}; i++)); do
        echo -n "${text:$i:1}"
        sleep $delay
    done
    echo
}
clear
echo ""
printshahan "ShaHaN Panel Installation :) By HamedAp" 0.1
echo ""
echo ""
printshahan "Please Wait . . ." 0.1
echo ""
echo ""


sed -i 's/#Port 22/Port 22/' /etc/ssh/sshd_config
po=$(cat /etc/ssh/sshd_config | grep "^Port")
port=$(echo "$po" | sed "s/Port //g")
adminuser=$(mysql -N -e "use ShaHaN; select adminuser from setting where id='1';")
adminpass=$(mysql -N -e "use ShaHaN; select adminpassword from setting where id='1';")

if [ "$adminuser" != "" ]; then
adminusername=$adminuser
adminpassword=$adminpass
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


file=/etc/systemd/system/videocall.service
if [ -e "$file" ]; then
    echo ""
else
udpport=7300
echo -e "\nPlease input UDPGW Port ."
printf "Default Port is \e[33m${udpport}\e[0m, let it blank to use this Port: "
read udpport
fi


ipv4=$(curl -s ipv4.icanhazip.com)
sudo sed -i '/www-data/d' /etc/sudoers &
wait
sudo sed -i '/apache/d' /etc/sudoers & 
wait

sed -i 's@#Banner none@Banner /var/www/html/p/banner.txt@' /etc/ssh/sshd_config
sed -i 's@#PrintMotd yes@PrintMotd yes@' /etc/ssh/sshd_config
sed -i 's@#PrintMotd no@PrintMotd yes@' /etc/ssh/sshd_config


if command -v apt-get >/dev/null; then
apt update -y > /dev/null 2>&1
apt upgrade -y > /dev/null 2>&1
rm -fr /etc/php/7.4/apache2/conf.d/00-ioncube.ini
sudo apt -y install software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
apt install apache2 zip unzip net-tools curl mariadb-server iptables-persistent -y


string=$(php -v)
if [[ $string == *"8.1"* ]]; then

apt autoremove -y
  echo "PHP Is Installed :)"
else
apt remove php7* -y &
wait
apt remove php* -y
apt remove php -y
apt autoremove -y
apt install php8.1 php8.1-mysql php8.1-xml php8.1-curl cron -y
fi


if [ $# == 0 ]; then
link=$(sudo curl -Ls "https://api.github.com/repos/HamedAp/Ssh-User-management/releases/latest" | grep '"browser_download_url":' | sed -E 's/.*"([^"]+)".*/\1/')
sudo wget -O /var/www/html/update.zip $link
sudo unzip -o /var/www/html/update.zip -d /var/www/html/ &
wait
    else
last_version=$1

lastzip=$(echo $last_version | sed -e 's/\.//g')
link="https://github.com/HamedAp/Ssh-User-management/releases/download/$last_version/$lastzip.zip"

sudo wget -O /var/www/html/update.zip $link
sudo unzip -o /var/www/html/update.zip -d /var/www/html/ &
wait
    fi




echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/adduser' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/userdel' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/sed' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/cat' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/passwd' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/curl' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/kill' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/killall' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/pkill' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/lsof' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/lsof' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/sed' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/rm' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/crontab' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/mysqldump' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/pgrep' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/nethogs' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/nethogs' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/local/sbin/nethogs' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/netstat' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/bin/systemctl restart sshd' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/bin/systemctl restart videocall' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/bin/systemctl restart dropbear' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/bin/systemctl daemon-reload' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/local/bin/ocpasswd' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/iptables' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/iptables-save' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/bin/systemctl restart tuic' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/uuidgen' | sudo EDITOR='tee -a' visudo &
wait

sudo sed -i '/%sudo/s/^/#/' /etc/sudoers &
wait


sudo service apache2 restart
touch /var/www/html/p/banner.txt
chown -R www-data:www-data /var/www/html/p/* &
wait

systemctl restart mariadb &
wait
systemctl enable mariadb &
wait
sudo phpenmod curl
PHP_INI=$(php -i | grep /.+/php.ini -oE)
sed -i 's/extension=intl/;extension=intl/' ${PHP_INI}


IonCube=$(php -v)
if [[ $IonCube == *"PHP Loader v13"* ]]; then
  echo "IonCube Is Installed :)"
else
sed -i 's@zend_extension = /usr/local/ioncube/ioncube_loader_lin_8.1.so@@' /etc/php/8.1/cli/php.ini
bash <(curl -Ls https://raw.githubusercontent.com/HamedAp/ioncube-loader/main/install.sh --ipv4)
fi

Nethogs=$(nethogs -V)
if [[ $Nethogs == *"version 0.8.7"* ]]; then
  echo "Nethogs Is Installed :)"
else
bash <(curl -Ls https://raw.githubusercontent.com/HamedAp/Nethogs-Json/main/install.sh --ipv4)
fi

file=/etc/systemd/system/videocall.service
if [ -e "$file" ]; then
    echo "SSH-CALLS exists"
else

apt install git cmake -y
git clone https://github.com/ambrop72/badvpn.git /root/badvpn
mkdir /root/badvpn/badvpn-build
cd  /root/badvpn/badvpn-build
cmake .. -DBUILD_NOTHING_BY_DEFAULT=1 -DBUILD_UDPGW=1 &
wait
make &
wait
cp udpgw/badvpn-udpgw /usr/local/bin
cat >  /etc/systemd/system/videocall.service << ENDOFFILE
[Unit]
Description=UDP forwarding for badvpn-tun2socks
After=nss-lookup.target

[Service]
ExecStart=/usr/local/bin/badvpn-udpgw --loglevel none --listen-addr 127.0.0.1:$udpport --max-clients 999
User=videocall

[Install]
WantedBy=multi-user.target
ENDOFFILE
useradd -m videocall
systemctl enable videocall
systemctl start videocall
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

sudo sed -i "s/SERVERUSER/$adminusername/g" /var/www/html/p/killusers.sh &
wait 
sudo sed -i "s/SERVERPASSWORD/$adminpassword/g" /var/www/html/p/killusers.sh &
wait 
sudo sed -i "s/SERVERIP/$ipv4/g" /var/www/html/p/killusers.sh &
wait 
php /var/www/html/p/restoretarikh.php
cp /var/www/html/p/tarikh /var/www/html/p/backup/tarikh
rm -fr /var/www/html/p/tarikh
rm -fr /var/www/html/update.zip

nowdate=$(date +"%Y-%m-%d-%H-%M-%S")
mysqldump -u root ShaHaN > /var/www/html/p/backup/${nowdate}-full-installbackup.sql

crontab -l | grep -v '/p/expire.php'  | crontab  -
crontab -l | grep -v '/p/posttraffic.php'  | crontab  -
crontab -l | grep -v '/p/synctraffic.php'  | crontab  -
crontab -l | grep -v '/p/tgexpire.php'  | crontab  -
crontab -l | grep -v 'p/killusers.sh'  | crontab  -
crontab -l | grep -v 'p/versioncheck.php'  | crontab  -
crontab -l | grep -v 'p/autoupdate.php'  | crontab  -
crontab -l | grep -v 'HamedAp/Ssh-User-management/master/install.sh'  | crontab  -
(crontab -l ; echo "1 1-23/2 * * * php /var/www/html/p/versioncheck.php >/dev/null 2>&1
4 4 * * * bash <(curl -Ls https://raw.githubusercontent.com/HamedAp/Ssh-User-management/master/install.sh --ipv4)
* * * * * php /var/www/html/p/expire.php >/dev/null 2>&1
0 0 * * * php /var/www/html/p/tgexpire.php >/dev/null 2>&1
* * * * * php /var/www/html/p/posttraffic.php >/dev/null 2>&1
* * * * * bash /var/www/html/p/killusers.sh >/dev/null 2>&1" ) | crontab - &
wait
sudo timedatectl set-timezone Asia/Tehran
chmod 0644 /var/log/auth.log
sudo wget -4 -O /usr/local/bin/shahan https://raw.githubusercontent.com/HamedAp/Ssh-User-management/main/screenshot/shahan &
wait


naps=/var/www/html/n.apk
if [ -e "$naps" ]; then
    echo "napster file"
else
sudo wget -4 -O /var/www/html/n.apk https://github.com/HamedAp/Ssh-User-management/raw/main/n.apk &
wait
fi

inje=/var/www/html/h.apk
if [ -e "$inje" ]; then
    echo "inje file"
else
sudo wget -4 -O /var/www/html/n.apk https://github.com/HamedAp/Ssh-User-management/raw/main/h.apk &
wait
fi


elif command -v yum >/dev/null; then
echo "Only Ubuntu Supported"
fi


cat >  /usr/local/bin/listen << ENDOFFILE
sudo lsof -i -P -n | grep LISTEN
ENDOFFILE
sudo chmod a+rx /usr/local/bin/listen

touch /var/www/shahanak.txt
touch /var/www/dropport.txt
touch /var/www/cisco.txt
sudo chmod 646 /var/www/shahanak.txt
sudo chmod 646 /var/www/dropport.txt
sudo chmod 646 /var/www/cisco.txt
sudo chmod 646 /etc/default/dropbear


touch /etc/ocserv/ocpasswd
sudo chmod a+rx /usr/local/bin/shahan

JAILPATH='/jailed'
mkdir -p $JAILPATH
if ! getent group jailed > /dev/null 2>&1
then
  echo "creating jailed group"
  groupadd -r jailed
fi
if ! grep -q "Match group jailed" /etc/ssh/sshd_config
then
  echo "Users Limited From SSH Login"
  echo "
Match group jailed
  ChrootDirectory $JAILPATH
" >> /etc/ssh/sshd_config
fi
sudo chmod 400 /jailed/
systemctl restart sshd

sudo sed -i '/AllowTCPForwarding no/d' /etc/ssh/sshd_config &
wait
sudo sed -i '/X11Forwarding no/d' /etc/ssh/sshd_config &
wait


naps=/var/www/config.json
if [ -e "$naps" ]; then
echo "Hi"
else
bash <(curl -Ls https://raw.githubusercontent.com/HamedAp/Ssh-User-management/main/t.sh --ipv4)
fi
chown www-data:www-data /var/www/config.json

clear
printf "%s" "$(</var/www/html/shahan.txt)"

Green_font_prefix="\033[32m" && Red_font_prefix="\033[31m" && Font_color_suffix="\033[0m"
IonCube=$(php -v)
if [[ $IonCube == *"PHP Loader v13"* ]]; then
  echo -e "\n${Green_font_prefix}IonCube Is Installed${Font_color_suffix}"
else
echo -e "\n${Red_font_prefix}IonCube Is NOT Installed${Font_color_suffix}"
fi
Nethogs=$(nethogs -V)
if [[ $Nethogs == *"version 0.8.7"* ]]; then
  echo -e "\n${Green_font_prefix}Nethogs Is Installed${Font_color_suffix}"
else
echo -e "\n${Red_font_prefix}Nethogs Is NOT Installed${Font_color_suffix}"
fi
string=$(php -v)
if [[ $string == *"8.1"* ]]; then
  echo -e "\n${Green_font_prefix}PHP8.1 Is Installed${Font_color_suffix}"
else
echo -e "\n${Red_font_prefix}PHP8.1 Is NOT Installed${Font_color_suffix}"
fi
if [ -e "$file" ]; then
echo -e "\n${Green_font_prefix}SSH-Calls Is Installed${Font_color_suffix}"
else
echo -e "\n${Red_font_prefix}SSH-Calls Is NOT Installed${Font_color_suffix}"
fi



printf "\n\n\nPanel Link : http://${ipv4}/p/index.php"
printf "\nUserName : \e[31m${adminusername}\e[0m "
printf "\nPassword : \e[31m${adminpassword}\e[0m "
printf "\nPort : \e[31m${port}\e[0m \n"
printf "\n NOW You Can Use shahan Command To See Menu Of Shahan Panel \n"
