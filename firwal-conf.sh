#!/bin/bash
apt update
apt install ufw libapache2-mod-geoip geoip-database -y
a2enmod geoip
apt install geoip-bin -y
curl -sSL https://www.ipdeny.com/ipblocks/data/countries/ir.zone | awk '{print "sudo ufw deny out from any to " $1}' | bash
ufw allow from any to any port 22 proto tcp
ufw enable
ufw reload