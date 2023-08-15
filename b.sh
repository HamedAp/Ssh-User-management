#!/bin/bash
clear 
# Edited Opiran Version . Special Thanks To OPIran 

block_country_ips() {
  country_code="$1"
  echo -e "\e[33mBlocking IPs from $country_code\e[0m"
  curl -sSL "https://www.ipdeny.com/ipblocks/data/countries/$country_code.zone" | awk '{print "sudo ufw deny out from any to " $1}' | bash
}

# Install required packages
apt update
apt install ufw libapache2-mod-geoip geoip-database -y
a2enmod geoip
apt install geoip-bin -y

# Open desired ports
ufw allow ssh
ufw allow http
ufw allow https

 block_country_ips "ir"

ufw enable

