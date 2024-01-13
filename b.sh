#!/bin/bash
clear 
po=$(cat /etc/ssh/sshd_config | grep "^Port")
port=$(echo "$po" | sed "s/Port //g")

sudo apt install iptables ipset -y

sudo wget -4 -O /root/iranip.txt https://raw.githubusercontent.com/HamedAp/Ssh-User-management/main/iranip.txt &
wait

iptables -F
iptables -A INPUT -p tcp --dport 22 -j ACCEPT
iptables -A INPUT -p tcp --dport $port -j ACCEPT
ipset create whitelist hash:net
while read line; do ipset add whitelist $line; done < /root/iranip.txt
iptables -A INPUT -m set --match-set whitelist src -j ACCEPT
iptables -A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT
iptables -I INPUT 1 -i lo -j ACCEPT
iptables -A INPUT -j DROP

echo "Blocked All Country Incomming Except Iran :) "
