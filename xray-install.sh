#!/bin/bash
if [ "$EUID" -ne 0 ]; then
    echo "Please run as root"
    exit 1
fi
apt-get update
apt-get install -y curl unzip uuid-runtime
bash -c "$(curl -L https://github.com/XTLS/Xray-install/raw/main/install-release.sh)" @ install
mkdir -p /var/www
chown www-data:www-data /var/www
chmod 775 /var/www
if [ ! -f /var/www/xray.json ]; then
    cat <<EOF > /var/www/xray.json
{
    "log": {
        "loglevel": "warning"
    },
    "api": {
        "services": [
            "HandlerService",
            "LoggerService",
            "StatsService"
        ],
        "tag": "api",
        "listen": "127.0.0.1:22223"
    },
    "stats": {},
    "inbounds": [],
    "outbounds": [
        {
            "protocol": "freedom",
            "tag": "direct"
        },
        {
            "protocol": "blackhole",
            "tag": "block"
        }
    ],
    "dns": {
        "servers": [
            "1.1.1.1",
            "8.8.8.8"
        ]
    },
    "routing": {
        "domainStrategy": "AsIs",
        "rules": [
            {
                "type": "field",
                "ip": [
                    "geoip:private"
                ],
                "outboundTag": "block"
            }
        ]
    }
}
EOF
fi
chown www-data:www-data /var/www/xray.json || true
chmod 664 /var/www/xray.json || true
cat <<EOF > /etc/systemd/system/xray-shahan.service
[Unit]
Description=Xray Custom Service
After=network.target nss-lookup.target
[Service]
User=root
CapabilityBoundingSet=CAP_NET_ADMIN CAP_NET_BIND_SERVICE
AmbientCapabilities=CAP_NET_ADMIN CAP_NET_BIND_SERVICE
NoNewPrivileges=true
ExecStart=/usr/local/bin/xray run -config /var/www/xray.json
Restart=on-failure
RestartPreventExitStatus=23
[Install]
WantedBy=multi-user.target
EOF
systemctl daemon-reload
systemctl enable xray-shahan.service
systemctl start xray-shahan.service || true
if [ -d /etc/sudoers.d ]; then
    echo "www-data ALL=(ALL) NOPASSWD: /usr/bin/systemctl restart xray-shahan.service, /usr/bin/uuidgen, /usr/local/bin/xray" > /etc/sudoers.d/xray-shahan
    chmod 440 /etc/sudoers.d/xray-shahan
    elif [ -f /etc/sudoers ]; then
    if ! grep -q "xray-shahan.service" /etc/sudoers; then
        echo "www-data ALL=(ALL) NOPASSWD: /usr/bin/systemctl restart xray-shahan.service, /usr/bin/uuidgen, /usr/local/bin/xray" >> /etc/sudoers
    fi
fi

clear
echo "Xray-Shahan installed successfully."
