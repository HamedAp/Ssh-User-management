#!/bin/bash
clear 
po=$(cat /etc/ssh/sshd_config | grep "^Port")
port=$(echo "$po" | sed "s/Port //g")

sudo apt install iptables ipset -y

sudo wget -4 -O /root/iranip.txt https://raw.githubusercontent.com/HamedAp/Ssh-User-management/main/iranip.txt &
wait

iptables -F

ipset create shahaniran hash:net
ipset flush shahaniran
while read line; do ipset add shahaniran $line; done < /root/iranip.txt
iptables -A INPUT -p tcp --dport 22 -m set --match-set shahaniran src -j ACCEPT
iptables -A INPUT -p tcp --dport $port -m set --match-set shahaniran src -j ACCEPT
iptables -A INPUT -p tcp -m set --match-set shahaniran src -j ACCEPT
iptables -A INPUT -m set --match-set shahaniran src -j ACCEPT
#iptables -A OUTPUT -m set --match-set shahaniran src -j DROP
iptables -A OUTPUT -p tcp --dport 443 -m set --match-set shahaniran dst -j DROP
iptables -A OUTPUT -p tcp --dport 80 -m set --match-set shahaniran dst -j DROP
iptables -A INPUT -m state --state ESTABLISHED,RELATED -j ACCEPT
iptables -I INPUT 1 -i lo -j ACCEPT
iptables -A INPUT -j DROP
sudo iptables-save | sudo tee /etc/iptables/rules.v4

echo "Blocked Iran Ip :)"
echo "Blocked Panel From Outside :)"
