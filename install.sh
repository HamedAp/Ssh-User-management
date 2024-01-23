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
function isRoot() {
	if [ "$EUID" -ne 0 ]; then
		return 1
	fi
}
if ! isRoot; then
	echo "Sorry, you need to run this as root"
	exit 1
fi
export PATH=$PATH:/usr/local/bin
panelporttmp=$(sudo lsof -i -P -n | grep -i LISTEN | grep apache2 | awk '{if(!seen[$9]++)print $9;exit}')
panelportt=$(echo $panelporttmp | sed 's/[^0-9]*//g' )
sed -i 's/#Port 22/Port 22/' /etc/ssh/sshd_config
po=$(cat /etc/ssh/sshd_config | grep "^Port")
port=$(echo "$po" | sed "s/Port //g")
adminuser=$(mysql -N -e "use ShaHaN; select adminuser from setting where id='1';")
adminpass=$(mysql -N -e "use ShaHaN; select adminpassword from setting where id='1';")

sudo wget -4 -O /usr/local/bin/shahan https://raw.githubusercontent.com/HamedAp/Ssh-User-management/main/screenshot/shahan &
wait
sudo chmod a+rx /usr/local/bin/shahan

sudo wget -4 -O /usr/local/bin/shahancheck https://raw.githubusercontent.com/HamedAp/Ssh-User-management/main/screenshot/shahancheck &
wait
sudo chmod a+rx /usr/local/bin/shahancheck

clear
echo ""
printshahan "ShaHaN Panel Installation :) By HamedAp" 0.1
echo ""
echo ""
printshahan "Please Wait . . ." 0.1
echo ""
echo ""

if [[ -e /etc/debian_version ]]; then
		OS="debian"
		source /etc/os-release
		if [[ $ID == "ubuntu" ]]; then
			OS="ubuntu"
			MAJOR_UBUNTU_VERSION=$(echo "$VERSION_ID" | cut -d '.' -f1)
			if [[ $MAJOR_UBUNTU_VERSION -lt 18 ]]; then
				echo "⚠️ Your version of Ubuntu is not supported. Please Install On Ubuntu 20"
				echo ""
				exit
			fi
		else
			echo "⚠️ Your OS not supported. Please Install On Ubuntu 20"
			echo ""
			read -rp "Please enter 'Y' to exit, or press the any key to continue installation ：" back2menuInput
   			 case "$back2menuInput" in
       			 y) exit 1 ;;
   			 esac
		fi
fi


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
apt update -y
apt upgrade -y
rm -fr /etc/php/7.4/apache2/conf.d/00-ioncube.ini
sudo apt -y install software-properties-common

sudo add-apt-repository ppa:ondrej/php -y
apt install apache2 zip unzip net-tools curl mariadb-server iptables-persistent vnstat -y


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
sudo apt install  php8.1-mbstring -y

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



echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/sshd' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/adduser' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/useradd' | sudo EDITOR='tee -a' visudo &
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
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/bin/systemctl restart syslog' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/local/bin/ocpasswd' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/local/bin/occtl' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/iptables' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/sbin/iptables-save' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/bin/systemctl restart tuic' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/uuidgen' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/who' | sudo EDITOR='tee -a' visudo &
wait
echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/vnstat' | sudo EDITOR='tee -a' visudo &
wait

sudo sed -i '/%sudo/s/^/#/' /etc/sudoers &
wait


sudo service apache2 restart
touch /var/www/html/p/banner.txt
chown -R www-data:www-data /var/www/html/p/* &
wait
chown www-data:www-data /var/www/config &
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

sed -i '/panelport/d' /var/www/html/p/config.php
cat >>  /var/www/html/p/config.php << ENDOFFILE
\$panelport = "$panelportt";
ENDOFFILE

mysql -e "use ShaHaN;update users set userport='' where userport like '39%';"

php /var/www/html/p/restoretarikh.php
rm -fr /var/www/html/update.zip

nowdate=$(date +"%Y-%m-%d-%H-%M-%S")
mysqldump -u root ShaHaN > /var/www/html/p/backup/${nowdate}-update.sql

rnd=$(shuf -i 1-59 -n 1)

crontab -l | grep -v '/p/expire.php'  | crontab  -
crontab -l | grep -v '/p/posttraffic.php'  | crontab  -
crontab -l | grep -v '/p/synctraffic.php'  | crontab  -
crontab -l | grep -v '/p/tgexpire.php'  | crontab  -
crontab -l | grep -v 'p/killusers.sh'  | crontab  -
crontab -l | grep -v '/p/log/log.sh'  | crontab  -
crontab -l | grep -v 'p/versioncheck.php'  | crontab  -
crontab -l | grep -v 'p/plugins/check.php'  | crontab  -
crontab -l | grep -v 'p/autoupdate.php'  | crontab  -
crontab -l | grep -v 'p/checkipauto.php'  | crontab  -
crontab -l | grep -v 'ocserv'  | crontab  -
crontab -l | grep -v 'tuic'  | crontab  -
crontab -l | grep -v 'HamedAp/Ssh-User-management/master/install.sh'  | crontab  -
crontab -l | grep -v '/p/checkipauto.php'  | crontab  -
crontab -l | grep -v '/p/log/clear.sh'  | crontab  -
(crontab -l ; echo "5 * * * * php /var/www/html/p/versioncheck.php >/dev/null 2>&1
* * * * * php /var/www/html/p/expire.php >/dev/null 2>&1
0 0 * * * php /var/www/html/p/tgexpire.php >/dev/null 2>&1
* * * * * php /var/www/html/p/posttraffic.php >/dev/null 2>&1
* * * * * php /var/www/html/p/plugins/check.php >/dev/null 2>&1
* * * * * bash /var/www/html/p/killusers.sh >/dev/null 2>&1
* * * * * bash /var/www/html/p/log/log.sh >/dev/null 2>&1
*/5 * * * * bash /var/www/html/p/log/clear.sh >/dev/null 2>&1" ) | crontab - &
wait
sudo timedatectl set-timezone Asia/Tehran
chmod 0646 /var/log/auth.log

sudo wget -4 -O /root/updateshahan.sh https://github.com/HamedAp/Ssh-User-management/raw/main/install.sh

if  grep -q "Apache2 Ubuntu Default Page" "/var/www/html/index.html" ; then
cat >  /var/www/html/index.html << ENDOFFILE
<meta http-equiv="refresh" content="0;url=http://google.com" />
ENDOFFILE
fi


if [ -e "/var/www/html/n.apk" ]; then
    echo "napster file"
else
echo "1"
#sudo wget -4 -O /var/www/html/n.apk https://shahanpanel.online/n.apk &
#wait
fi
inje='/var/www/html/h.apk'
if [ -e "$inje" ]; then
    echo "inje file"
else
sudo wget -4 -O /var/www/html/h.apk https://github.com/HamedAp/Ssh-User-management/raw/main/h.apk &
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
touch /var/www/userlog.txt
sudo chmod 646 /var/www/shahanak.txt
sudo chmod 646 /var/www/dropport.txt
sudo chmod 646 /var/www/cisco.txt
sudo chmod 646 /var/www/userlog.txt
sudo chmod 646 /etc/default/dropbear


touch /etc/ocserv/ocpasswd

echo "
Include "/var/www/banner.conf"
" >> /etc/ssh/sshd_config
sed -i '/Match User/d' /etc/sshd/sshd_config
sed -i '/Banner /d' /etc/sshd/sshd_config

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
ForceCommand /bin/false
" >> /etc/ssh/sshd_config
fi



sudo sed -i '/AllowTCPForwarding no/d' /etc/ssh/sshd_config &
wait
sudo sed -i 's@ChrootDirectory /jailed@ForceCommand /bin/false@' /etc/ssh/sshd_config &
wait
sudo sed -i '/X11Forwarding no/d' /etc/ssh/sshd_config &
wait

systemctl restart sshd

mkdir /var/www/config/

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
printf "\nNOW You Can Use ${Green_font_prefix}shahan${Font_color_suffix} and ${Green_font_prefix}shahancheck${Font_color_suffix} Command To See Menu Of Shahan Panel \n"
