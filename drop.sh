#!/bin/bash

clear
dropport=222
echo -e "\nPlease input DropBear Port."
printf "Default Port is \e[33m${dropport}\e[0m, let it blank to use this Port: "
read dropporttmp
if [[ -n "${dropporttmp}" ]]; then
    dropport=${dropporttmp}
fi

sudo apt update -y
sudo apt install dropbear -y
cat >  /etc/default/dropbear << ENDOFFILE
NO_START=0
DROPBEAR_PORT=$dropport
DROPBEAR_EXTRA_ARGS=""
DROPBEAR_RSAKEY="/etc/dropbear/dropbear_rsa_host_key"
DROPBEAR_DSSKEY="/etc/dropbear/dropbear_dss_host_key"
DROPBEAR_ECDSAKEY="/etc/dropbear/dropbear_ecdsa_host_key"
DROPBEAR_RECEIVE_WINDOW=65536
ENDOFFILE
sudo chmod 646 /etc/default/dropbear
sudo ufw allow $dropport
sudo systemctl restart dropbear
sudo systemctl enable dropbear
clear
echo "DROPBEAR Installed As Port : $dropport"
