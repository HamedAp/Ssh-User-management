#!/bin/bash

# =============================================
# ShaHaN Panel SSL Installation Script
# By HamedAp - Clean & Improved Version
# =============================================

printshahan() {
    local text="$1"
    local delay="${2:-0.08}"
    for ((i = 0; i < ${#text}; i++)); do
        echo -n "${text:$i:1}"
        sleep "$delay"
    done
    echo
}

isRoot() {
    [ "$EUID" -eq 0 ]
}

if ! isRoot; then
    echo "Sorry, you need to run this script as root."
    exit 1
fi

clear
echo ""
printshahan "ShaHaN Panel SSL Installation :) By HamedAp" 0.1
echo ""

# ====================== Input ======================
read -rp "Please enter the pointed domain / sub-domain name: " domain

if [[ -z "$domain" ]]; then
    echo "Error: Domain name cannot be empty."
    exit 1
fi

# Stop Apache temporarily
systemctl stop apache2 2>/dev/null || true

# ====================== Colors ======================
RED="\033[31m"
GREEN="\033[32m"
YELLOW="\033[33m"
PLAIN='\033[0m'

red()    { echo -e "${RED}$1${PLAIN}"; }
green()  { echo -e "${GREEN}$1${PLAIN}"; }
yellow() { echo -e "${YELLOW}$1${PLAIN}"; }

# ====================== OS Detection ======================
REGEX=("debian" "ubuntu" "centos|red hat|kernel|oracle linux|alma|rocky" "amazon linux" "fedora")
RELEASE=("Debian" "Ubuntu" "CentOS" "CentOS" "Fedora")

CMD=(
    "$(grep -i pretty_name /etc/os-release 2>/dev/null | cut -d'"' -f2)"
    "$(hostnamectl 2>/dev/null | grep -i system | cut -d: -f2)"
    "$(lsb_release -sd 2>/dev/null)"
    "$(grep -i description /etc/lsb-release 2>/dev/null | cut -d'"' -f2)"
    "$(grep . /etc/redhat-release 2>/dev/null)"
)

for i in "${CMD[@]}"; do
    SYS="$i"
    [[ -n $SYS ]] && break
done

for ((int = 0; int < ${#REGEX[@]}; int++)); do
    if [[ $(echo "$SYS" | tr '[:upper:]' '[:lower:]') =~ ${REGEX[int]} ]]; then
        SYSTEM="${RELEASE[int]}"
        break
    fi
done

[[ -z $SYSTEM ]] && { red "Unsupported OS!"; exit 1; }

# ====================== Package Manager ======================
if [[ $SYSTEM != "CentOS" ]]; then
    apt-get update -qq
fi

if command -v apt-get >/dev/null; then
    PKG_UPDATE="apt-get update -qq"
    PKG_INSTALL="apt-get install -y"
elif command -v yum >/dev/null; then
    PKG_INSTALL="yum -y install"
else
    red "Unsupported package manager."
    exit 1
fi

$PKG_INSTALL curl wget sudo socat lsof cron 2>/dev/null || true

if [[ $SYSTEM == "CentOS" ]]; then
    systemctl start crond 2>/dev/null && systemctl enable crond 2>/dev/null
else
    systemctl start cron 2>/dev/null && systemctl enable cron 2>/dev/null
fi

# ====================== ACME.sh Installation ======================
yellow "Installing acme.sh..."
curl -s https://get.acme.sh | sh -s email="$(date +%s%N | md5sum | cut -c1-16)@gmail.com" >/dev/null 2>&1

source ~/.bashrc
~/.acme.sh/acme.sh --upgrade --auto-upgrade >/dev/null 2>&1
~/.acme.sh/acme.sh --set-default-ca --server letsencrypt >/dev/null 2>&1

green "ACME.sh installed successfully."

# ====================== Port 80 Check ======================
yellow "Checking port 80..."
if lsof -i:80 | grep -q LISTEN; then
    yellow "Port 80 is in use. Killing conflicting processes..."
    lsof -i:80 | awk 'NR>1 {print $2}' | xargs kill -9 2>/dev/null
    sleep 1
else
    green "Port 80 is free."
fi

# ====================== Certificate Issuance ======================
yellow "Requesting SSL certificate for: $domain"

ipv4=$(curl -s4m8 ipv4.icanhazip.com)
ipv6=$(curl -s6m8 ipv6.icanhazip.com 2>/dev/null || echo "")

domainIP=$(getent hosts "$domain" | awk '{print $1}' | head -n1)

if [[ $domainIP == "$ipv4" ]] || [[ $domainIP == "$ipv6" ]]; then
    if ~/.acme.sh/acme.sh --issue -d "$domain" --standalone -k ec-256 --insecure >/dev/null 2>&1; then
        green "Certificate successfully issued!"
        CERT_ISSUED=true
    else
        red "Failed to issue certificate."
        CERT_ISSUED=false
    fi
else
    red "Domain does not point to this server."
    CERT_ISSUED=false
fi

# ====================== Apache2 Configuration (Only if cert issued) ======================
if [[ "$CERT_ISSUED" == true ]] && command -v apache2 >/dev/null; then
    yellow "Configuring Apache2 with SSL..."

    mkdir -p /etc/apache2/ssl

    ~/.acme.sh/acme.sh --install-cert -d "$domain" \
        --key-file /etc/apache2/ssl/"${domain}".key \
        --fullchain-file /etc/apache2/ssl/"${domain}".crt \
        --ecc >/dev/null 2>&1

    # SSL params
    cat > /etc/apache2/conf-available/ssl-params.conf << 'EOF'
SSLCipherSuite EECDH+AESGCM:EDH+AESGCM
SSLProtocol -all +TLSv1.3 +TLSv1.2
SSLOpenSSLConfCmd Curves X25519:secp521r1:secp384r1:prime256v1
SSLHonorCipherOrder On
Header always set X-Frame-Options DENY
Header always set X-Content-Type-Options nosniff
SSLCompression off
SSLUseStapling on
SSLStaplingCache "shmcb:logs/stapling-cache(150000)"
SSLSessionTickets Off
EOF

    # Backup and configure default-ssl site
    cp /etc/apache2/sites-available/default-ssl.conf /etc/apache2/sites-available/default-ssl.conf.bak 2>/dev/null || true

    cat > /etc/apache2/sites-available/default-ssl.conf << EOF
<IfModule mod_ssl.c>
    <VirtualHost _default_:443>
        ServerAdmin webmaster@${domain}
        ServerName ${domain}
        DocumentRoot /var/www/html

        ErrorLog \${APACHE_LOG_DIR}/error.log
        CustomLog \${APACHE_LOG_DIR}/access.log combined

        SSLEngine on
        SSLCertificateFile /etc/apache2/ssl/${domain}.crt
        SSLCertificateKeyFile /etc/apache2/ssl/${domain}.key

        <FilesMatch '\.(cgi|shtml|phtml|php)$'>
            SSLOptions +StdEnvVars
        </FilesMatch>
        <Directory /usr/lib/cgi-bin>
            SSLOptions +StdEnvVars
        </Directory>
    </VirtualHost>
</IfModule>
EOF

    a2enmod ssl headers 2>/dev/null
    a2ensite default-ssl 2>/dev/null
    a2enconf ssl-params 2>/dev/null

    if apache2ctl configtest >/dev/null 2>&1; then
        systemctl restart apache2
        green "Apache2 SSL configuration completed successfully!"
    else
        red "Apache2 configuration test failed."
    fi
elif [[ "$CERT_ISSUED" == true ]]; then
    yellow "Apache2 not detected. Skipping web server configuration."
fi

echo ""
green "======================================"
green "HTTPS Address : https://${domain}"
green "======================================"
