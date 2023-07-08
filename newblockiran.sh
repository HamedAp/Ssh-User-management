#!/bin/bash
clear
SSHPORT=22
echo -e "\nPlease input SSH Port ."
printf "Default Port is \e[33m${SSHPORT}\e[0m, let it blank to use this Port: "
read udpport

sudo apt-get update -y
sudo apt-get -y upgrade
sudo apt-get install curl unzip perl xtables-addons-common libtext-csv-xs-perl libmoosex-types-netaddr-ip-perl iptables-persistent -y 
sudo mkdir /usr/share/xt_geoip

sudo wget -4 -O /usr/local/bin/geo-update.sh https://raw.githubusercontent.com/HamedAp/Ssh-User-management/main/geo-update.sh

chmod 755 /usr/lib/xtables-addons/xt_geoip_build
bash /usr/local/bin/geo-update.sh


iptables -A OUTPUT -m geoip -p tcp --dport $SSHPORT --src-cc IR -j DROP
iptables-save
clear
printf "\nAll IRAN IP Blocked :)\n\n\n\n"
