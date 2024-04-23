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
printshahan "ShaHaN Panel Tunnel Command 2  :) By HamedAp" 0.05
echo ""
echo ""
printshahan "Please Wait . . ." 0.05
echo ""
echo ""

sed -i 's/#Port 22/Port 22/' /etc/ssh/sshd_config
po=$(cat /etc/ssh/sshd_config | grep "^Port")
port=$(echo "$po" | sed "s/Port //g")
ipkharej=$(curl -s ipv4.icanhazip.com)


echo -e "\nPlease input IP Kharej : "
printf "Default IP is \e[33m${ipkharej}\e[0m, let it blank to use this IP: "
read khtmp
if [[ -n "${khtmp}" ]]; then
    ipkharej=${khtmp}
fi


echo -e "\nPlease input IP Iran : "
read irtmp
if [[ -n "${irtmp}" ]]; then
    ipiran=${irtmp}
fi


ip tunnel add 6to4_To_IR mode sit remote $ipiran local $ipkharej
ip -6 addr add fc00::2/64 dev 6to4_To_IR
ip link set 6to4_To_IR mtu 1480
ip link set 6to4_To_IR up

ip -6 tunnel add GRE6Tun_To_IR mode ip6gre remote fc00::1 local fc00::2
ip addr add 192.168.13.2/30 dev GRE6Tun_To_IR
ip link set GRE6Tun_To_IR mtu 1436
ip link set GRE6Tun_To_IR up




cat >  /etc/rc.local << ENDOFFILE
#! /bin/bash
ip tunnel add 6to4_To_IR mode sit remote $ipiran local $ipkharej
ip -6 addr add fc00::2/64 dev 6to4_To_IR
ip link set 6to4_To_IR mtu 1480
ip link set 6to4_To_IR up

ip -6 tunnel add GRE6Tun_To_IR mode ip6gre remote fc00::1 local fc00::2
ip addr add 192.168.13.2/30 dev GRE6Tun_To_IR
ip link set GRE6Tun_To_IR mtu 1436
ip link set GRE6Tun_To_IR up
ENDOFFILE

clear
echo ""
printshahan "Installed Successfully :)  " 0.05
echo ""
echo ""
