#!/bin/bash

sudo apt-get install curl unzip perl xtables-addons-common libtext-csv-xs-perl libmoosex-types-netaddr-ip-perl iptables-persistent -y 

url="https://raw.githubusercontent.com/HamedAp/Ssh-User-management/main/i.txt"
allcount=$(curl -s "$url" | wc -l)
curl -s "$url"  | while IFS= read -r line; do
((++line_number))
iptables -A OUTPUT -p tcp  --dport 80 -d $line -j DROP
iptables -A OUTPUT -p tcp  --dport 443 -d $line -j DROP
clear
echo "Iran IP Blocking ( List 1 ) : $line_number / $allcount "
done
sudo iptables-save | sudo tee /etc/iptables/rules.v4
