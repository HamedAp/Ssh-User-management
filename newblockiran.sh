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

cat >  /usr/local/bin/geo-update.sh << ENDOFFILE
#!/bin/bash
MON=$(date +"%m")
YR=$(date +"%Y")
wget https://download.db-ip.com/free/dbip-country-lite-${YR}-${MON}.csv.gz -O /usr/share/xt_geoip/dbip-country-lite.csv.gz
gunzip /usr/share/xt_geoip/dbip-country-lite.csv.gz
/usr/lib/xtables-addons/xt_geoip_build -D /usr/share/xt_geoip/ -S /usr/share/xt_geoip/
rm /usr/share/xt_geoip/dbip-country-lite.csv
ENDOFFILE

chmod 755 /usr/lib/xtables-addons/xt_geoip_build
bash /usr/local/bin/geo-update.sh


iptables -A OUTPUT -m geoip -p tcp --dport $SSHPORT --src-cc IR -j DROP

clear
printf "\nAll IRAN IP Blocked :)"
