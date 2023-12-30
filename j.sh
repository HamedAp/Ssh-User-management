#!/bin/bash
# By Hamed Ap
# For Shahan Panel :) Dozdi Nakon BiShoor :))
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
printshahan "Juicity For ShaHaN Panel :) By HamedAp" 0.1
echo ""
echo ""
printshahan "Please Wait . . ." 0.1
echo ""
echo ""

sudo apt-get update
sudo apt-get install -y unzip jq

LATEST_RELEASE_URL=$(curl --silent "https://api.github.com/repos/juicity/juicity/releases" | jq -r '.[0].assets[] | select(.name == "juicity-linux-x86_64.zip") | .browser_download_url')


mkdir -p /root/juicity
curl -L $LATEST_RELEASE_URL -o /root/juicity/juicity.zip
unzip -q /root/juicity/juicity.zip -d /root/juicity


find /root/juicity ! -name 'juicity-server' -type f -exec rm -f {} +


chmod +x /root/juicity/juicity-server


read -p "Enter listen port (or press enter to random port): " PORT
[[ -z "$PORT" ]] && PORT=$((RANDOM % 65500 + 1))

openssl ecparam -genkey -name prime256v1 -out /root/juicity/private.key
openssl req -new -x509 -days 36500 -key /root/juicity/private.key -out /root/juicity/fullchain.cer -subj "/CN=speedtest.net"
UUID=$(uuidgen)



cat > /root/juicity/config_server.json <<EOL
{
  "listen": ":$PORT",
  "users": {
    "$UUID": "shahan"
  },
  "certificate": "/root/juicity/fullchain.cer",
  "private_key": "/root/juicity/private.key",
  "congestion_control": "bbr",
  "log_level": "info"
}
EOL


cat > /etc/systemd/system/juicity.service <<EOL
[Unit]
Description=juicity-server Service
Documentation=https://github.com/juicity/juicity
After=network.target nss-lookup.target

[Service]
Type=simple
User=root
Environment=QUIC_GO_ENABLE_GSO=true
ExecStart=/root/juicity/./juicity-server run -c /root/juicity/config_server.json
StandardOutput=file:/root/juicity/juicity-server.log
StandardError=file:/root/juicity/juicity-server.log
Restart=on-failure
LimitNPROC=512
LimitNOFILE=infinity

[Install]
WantedBy=multi-user.target
EOL


sudo systemctl daemon-reload
sudo systemctl enable juicity
sudo systemctl start juicity
sudo systemctl restart juicity




