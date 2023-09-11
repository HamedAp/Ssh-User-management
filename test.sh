#!/bin/bash

apt update -y

apt install dropbear -y

cat >  /etc/default/dropbear << ENDOFFILE
NO_START=0
DROPBEAR_PORT=222
DROPBEAR_EXTRA_ARGS="-p 110"
DROPBEAR_RSAKEY="/etc/dropbear/dropbear_rsa_host_key"
DROPBEAR_DSSKEY="/etc/dropbear/dropbear_dss_host_key"
DROPBEAR_ECDSAKEY="/etc/dropbear/dropbear_ecdsa_host_key"
DROPBEAR_RECEIVE_WINDOW=65536
ENDOFFILE

sudo ufw allow 222
sudo ufw allow 110


sudo systemctl start dropbear
sudo systemctl enable dropbear

reboot
