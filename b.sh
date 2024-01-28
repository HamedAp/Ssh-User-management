#!/bin/bash

sudo apt install libtext-csv-xs-perl libmoosex-types-netaddr-ip-perl iptables-persistent ipset -y 

sudo wget -4 -O /root/iranip.txt https://raw.githubusercontent.com/HamedAp/Ssh-User-management/main/iranip.txt &
wait

iptables -F

ipset create shahaniran hash:net
ipset flush shahaniran
while read line; do ipset add shahaniran $line; done < /root/iranip.txt
#iptables -A OUTPUT -m set --match-set shahaniran src -j DROP
iptables -A OUTPUT -p tcp --dport 443 -m set --match-set shahaniran dst -j DROP
iptables -A OUTPUT -p tcp --dport 80 -m set --match-set shahaniran dst -j DROP
sudo iptables-save | sudo tee /etc/iptables/rules.v4
clear
echo "Blocked Iran Ip :)"
