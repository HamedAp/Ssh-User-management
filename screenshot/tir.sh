#!/bin/bash
# By HamedAp

printshahan() {
    text="$1"
    delay="$2"
    for ((i=0; i<${#text}; i++)); do
        echo -n "${text:$i:1}"
        sleep $delay
    done
    echo
}
clear
echo ""
printshahan "ShaHaN Panel Tunnel :) By HamedAp" 0.05
echo ""
echo ""
printshahan "Please Wait . . ." 0.05
echo ""
echo ""

sed -i 's/#Port 22/Port 22/' /etc/ssh/sshd_config
po=$(cat /etc/ssh/sshd_config | grep "^Port")
port=$(echo "$po" | sed "s/Port //g")
ipiran=$(curl -s ipv4.icanhazip.com)


echo -e "\nPlease input IP Kharej : "
read khtmp
if [[ -n "${khtmp}" ]]; then
    ipkharej=${khtmp}
fi


echo -e "\nPlease input IP Iran : "
printf "Default IP is \e[33m${ipiran}\e[0m, let it blank to use this IP: "
read irtmp
if [[ -n "${irtmp}" ]]; then
    ipiran=${irtmp}
fi




ip tunnel add 6to4_To_KH mode sit remote $ipkharej local $ipiran
ip -6 addr add fc00::1/64 dev 6to4_To_KH
ip link set 6to4_To_KH mtu 1480
ip link set 6to4_To_KH up

ip -6 tunnel add GRE6Tun_To_KH mode ip6gre remote fc00::2 local fc00::1
ip addr add 192.168.13.1/30 dev GRE6Tun_To_KH
ip link set GRE6Tun_To_KH mtu 1436
ip link set GRE6Tun_To_KH up

sysctl net.ipv4.ip_forward=1
iptables -t nat -A PREROUTING -p tcp --dport $port -j DNAT --to-destination 192.168.13.1
iptables -t nat -A PREROUTING -j DNAT --to-destination 192.168.13.2
iptables -t nat -A POSTROUTING -j MASQUERADE 



cat >  /etc/rc.local << ENDOFFILE
#! /bin/bash
ip tunnel add 6to4_To_KH mode sit remote $ipkharej local $ipiran
ip -6 addr add fc00::1/64 dev 6to4_To_KH
ip link set 6to4_To_KH mtu 1480
ip link set 6to4_To_KH up

ip -6 tunnel add GRE6Tun_To_KH mode ip6gre remote fc00::2 local fc00::1
ip addr add 192.168.13.1/30 dev GRE6Tun_To_KH
ip link set GRE6Tun_To_KH mtu 1436
ip link set GRE6Tun_To_KH up

sysctl net.ipv4.ip_forward=1
iptables -t nat -A PREROUTING -p tcp --dport $port -j DNAT --to-destination 192.168.13.1
iptables -t nat -A PREROUTING -j DNAT --to-destination 192.168.13.2
iptables -t nat -A POSTROUTING -j MASQUERADE 
ENDOFFILE

clear
echo ""
printshahan "Installed Successfully :) Please Run Next Command In Server Kharej :) " 0.05
echo ""
echo ""
