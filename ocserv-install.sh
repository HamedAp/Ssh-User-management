#!/bin/bash
#By Hamed Ap
systemctl stop apache2

sed -i 's/#Port 22/Port 22/' /etc/ssh/sshd_config
po=$(cat /etc/ssh/sshd_config | grep "^Port")
sshport=$(echo "$po" | sed "s/Port //g")
networkcard=$(ip route get 8.8.8.8 | sed -nr 's/.*dev ([^\ ]+).*/\1/p')
read -rp "Please enter the pointed domain / sub-domain name: " domain
fakeEmail=$(date +%s%N | md5sum | cut -c 1-16)
fakeEmail=$fakeEmail@gmail.com
read -rp "Please enter the Port : " port


sudo apt update -y
sudo apt install ocserv ufw certbot -y
sudo systemctl start ocserv

sudo ufw allow 80,$port,$sshport/tcp
sudo ufw allow $sshport/udp


sudo mkdir /var/www/ocserv
sudo chown www-data:www-data /var/www/ocserv -R
sudo a2ensite $domain
sudo certbot certonly --standalone --preferred-challenges http --agree-tos --email $fakeEmail -d $domain



cat > /etc/ocserv/ocserv.conf << ENDOFFILE
auth = "plain[passwd=/etc/ocserv/ocpasswd]"
tcp-port = $port
udp-port = $port
run-as-user = nobody
run-as-group = daemon
socket-file = /run/ocserv.socket
server-cert = /etc/letsencrypt/live/$domain/fullchain.pem
server-key = /etc/letsencrypt/live/$domain/privkey.pem
ca-cert = /etc/ssl/certs/ssl-cert-snakeoil.pem
isolate-workers = true
max-clients = 128
max-same-clients = 2
server-stats-reset-time = 604800
keepalive = 300
dpd = 60
mobile-dpd = 300
switch-to-tcp-timeout = 25
try-mtu-discovery = true
cert-user-oid = 0.9.2342.19200300.100.1.1
compression = true
no-compress-limit = 256
tls-priorities = "NORMAL:%SERVER_PRECEDENCE:%COMPAT:-RSA:-VERS-SSL3.0:-ARCFOUR-128"
auth-timeout = 240
idle-timeout = 1200
mobile-idle-timeout = 1800
min-reauth-time = 300
max-ban-score = 80
ban-reset-time = 300
cookie-timeout = 300
deny-roaming = false
rekey-time = 172800
rekey-method = ssl
use-occtl = true
pid-file = /run/ocserv.pid
device = vpns
predictable-ips = true
default-domain = $domain
ipv4-network = 10.10.10.0
ipv4-netmask = 255.255.255.0
ipv6-network = fda9:4efe:7e3b:03ea::/48 
tunnel-all-dns = true
ping-leases = false
cisco-client-compat = true
dtls-legacy = true
ENDOFFILE

echo "net.ipv4.ip_forward = 1
net.ipv6.conf.all.forwarding=1" | sudo tee /etc/sysctl.d/60-custom.conf


echo "# NAT table rules
*nat
:POSTROUTING ACCEPT [0:0]
-A POSTROUTING -s 10.10.10.0/24 -o $networkcard -j MASQUERADE

COMMIT

# allow forwarding for trusted network
-A ufw-before-forward -s 10.10.10.0/24 -j ACCEPT
-A ufw-before-forward -d 10.10.10.0/24 -j ACCEPT
" >> /etc/ufw/before.rules


sudo ufw enable
sudo systemctl restart ufw

read -rp "Create New Username ( Enter Username ) : " username

sudo ocpasswd -c /etc/ocserv/ocpasswd $username

systemctl restart ocserv
systemctl restart apache2
