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
printshahan "Tuic For ShaHaN Panel :) By HamedAp" 0.1
echo ""
echo ""
printshahan "Please Wait . . ." 0.1
echo ""
echo ""

sudo apt update -y > /dev/null 2>&1
sudo apt install curl jq openssl uuid-runtime -y > /dev/null 2>&1

detect_arch() {
    local arch=$(uname -m)
    case $arch in
        x86_64)
            echo "x86_64-unknown-linux-gnu"
            ;;
        i686)
            echo "i686-unknown-linux-gnu"
            ;;
        armv7l)
            echo "armv7-unknown-linux-gnueabi"
            ;;
        aarch64)
            echo "aarch64-unknown-linux-gnu"
            ;;
        *)
            echo "Unsupported architecture: $arch"
            exit 1
            ;;
    esac
}

server_arch=$(detect_arch)
download_url="https://github.com/HamedAp/tuic/releases/download/1.0.0/tuic-server-1.0.0-$server_arch"
mkdir -p /root/tuic
cd /root/tuic
rm -fr /root/tuic/tuic-server
sudo wget -4 -O /root/tuic/tuic-server -q "$download_url"
if [[ $? -ne 0 ]]; then
    echo "Failed to download the tuic binary."
    exit 1
fi
chmod 755 tuic-server
openssl ecparam -genkey -name prime256v1 -out ca.key
openssl req -new -x509 -days 36500 -key ca.key -out ca.crt -subj "/CN=bing.com"
echo ""
read -p "Enter a port (or press enter for a random port between 10000 and 65000): " port
echo ""
[ -z "$port" ] && port=$((RANDOM % 55001 + 10000))
UUID=$(uuidgen)
if [ -z "$UUID" ]; then
    echo "Error: Failed to generate UUID."
    exit 1
fi
cp /root/tuic/config.json /var/www/config.json

cat > /var/www/config.json <<EOL
{
  "server": "[::]:$port",
  "users": {
    "$UUID": "shahan"
  },
  "certificate": "/root/tuic/ca.crt",
  "private_key": "/root/tuic/ca.key",
  "congestion_control": "bbr",
  "alpn": ["h3", "spdy/3.1"],
  "udp_relay_ipv6": true,
  "zero_rtt_handshake": false,
  "dual_stack": true,
  "auth_timeout": "3s",
  "task_negotiation_timeout": "3s",
  "max_idle_time": "10s",
  "max_external_packet_size": 1500,
  "gc_interval": "3s",
  "gc_lifetime": "15s",
  "log_level": "warn"
}
EOL

cat > /etc/systemd/system/tuic.service <<EOL
[Unit]
Description=tuic service
Documentation=TUIC v5
After=network.target nss-lookup.target

[Service]
User=root
WorkingDirectory=/root/tuic
CapabilityBoundingSet=CAP_NET_ADMIN CAP_NET_BIND_SERVICE CAP_NET_RAW
AmbientCapabilities=CAP_NET_ADMIN CAP_NET_BIND_SERVICE CAP_NET_RAW
ExecStart=/root/tuic/tuic-server -c /var/www/config.json
Restart=on-failure
RestartSec=10
LimitNOFILE=infinity

[Install]
WantedBy=multi-user.target
EOL




systemctl daemon-reload
systemctl enable tuic > /dev/null 2>&1
systemctl restart tuic
chmod 646 /var/www/config.json
chown www-data:www-data /var/www/config.json
clear
echo "TUIC Installed :)"
