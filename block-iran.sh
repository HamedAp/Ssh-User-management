#!/bin/bash
po=$(cat /etc/ssh/sshd_config | grep "^Port")
pport=$(echo "$po" | sed "s/Port //g")
if [ -z "$port" ]
then
sed -i 's/#Port 22/Port 22/' /etc/ssh/sshd_config
po=$(cat /etc/ssh/sshd_config | grep "^Port")
pport=$(echo "$po" | sed "s/Port //g")
fi




apt update -y
apt install ufw libapache2-mod-geoip geoip-database -y
a2enmod geoip
apt install geoip-bin -y
curl -sSL https://www.ipdeny.com/ipblocks/data/countries/ir.zone | awk '{print "sudo ufw deny out from any to " $1}' | bash
ufw allow from any to any port $pport proto tcp
sudo ufw allow $pport
sudo ufw allow 80
sudo ufw allow 22
sudo ufw allow 443
sudo ufw allow 7300
curl -sSL https://www.arvancloud.ir/en/ips.txt | awk '{print "sudo ufw deny out from any to " $1}' | bash
ufw enable
