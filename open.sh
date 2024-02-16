#!/bin/bash
printshahan() {
    text="$1"
    delay="$2"
    for ((i=0; i<${#text}; i++)); do
        echo -n "${text:$i:1}"
        sleep $delay
    done
    echo
}

CPU=$(uname -i)

if [ "$CPU" = "aarch64" ]; then
echo "Your Cpu Type Not Supported !! Please Wait For Update :) "
exit
fi



clear
echo ""
printshahan "Openvpn Installation :) By HamedAp" 0.1
echo ""
echo ""
printshahan "Please Wait . . ." 0.1
echo ""
echo ""

if [[ ! -e /dev/net/tun ]] || ! ( exec 7<>/dev/net/tun ) 2>/dev/null; then
	echo "The system does not have the TUN device available.
TUN needs to be enabled before running this installer."
	exit
fi

ipv4=$(curl -s ipv4.icanhazip.com)
echo -e "\nPlease input Domain Name To This Server"
printf "Default IP is \e[33m${ipv4}\e[0m, let it blank to use IP: "
read serveraddress
if [[ -n "${serveraddress}" ]]; then
    ipv4=${serveraddress}
fi

sudo sed -i '/ovpm/d' /etc/apt/sources.list &
wait

sudo sh -c 'echo "deb [trusted=yes] https://cad.github.io/ovpm/deb/ ovpm main" >> /etc/apt/sources.list'
sudo apt update -y
sudo apt install openvpn -y
sudo apt install ovpm -y
systemctl start ovpmd
systemctl enable ovpmd  
ovpm vpn init --hostname $ipv4


sudo sed -i '/ovpm/d' /etc/sudoers &
wait

echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/ovpm' | sudo EDITOR='tee -a' visudo &
wait


mkdir /var/www/html/p/open/
touch /var/www/html/p/open/index.php
chown www-data:www-data /var/www/html/p/open -R


echo "application/x-openvpn-profile      ovpn" >> /etc/mime.types
systemctl restart apache2





clear 
echo "OpenVpn Installed Succesfully :) "
echo "Have Fun Shahan Group "
