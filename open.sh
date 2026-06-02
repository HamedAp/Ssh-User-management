#!/bin/bash

# ============================================
# OpenVPN Installer (Steps Only)
# ============================================

# ---------- Function ----------
print_step() {
    echo ""
    echo "===================================="
    echo "STEP $1: $2"
    echo "===================================="
}

# ---------- CPU Check ----------
CPU=$(uname -i)

if [[ "$CPU" == "aarch64" ]]; then
    echo "Your CPU type is not supported yet."
    exit 1
fi

clear

echo "OpenVPN Installation Script"
echo "By HamedAp"

# ---------- TUN Check ----------
print_step "1" "Checking TUN Device"

if [[ ! -e /dev/net/tun ]] || ! (exec 7<>/dev/net/tun) 2>/dev/null; then
    echo "TUN device is not enabled."
    echo "Enable TUN before running this installer."
    exit 1
fi

echo "TUN device is available."

# ---------- Get Server Address ----------
print_step "2" "Getting Server Address"

ipv4=$(curl -s ipv4.icanhazip.com)

echo "Enter your domain or IP address."
read -p "Default IP is ${ipv4}. Press ENTER to use it: " serveraddress

if [[ -n "$serveraddress" ]]; then
    ipv4="$serveraddress"
fi

echo "Using address: $ipv4"

# ---------- Add OVPM Repository ----------
print_step "3" "Adding OVPM Repository"

sudo sed -i '/ovpm/d' /etc/apt/sources.list

echo "deb [trusted=yes] https://cad.github.io/ovpm/deb/ ovpm main" | \
sudo tee -a /etc/apt/sources.list > /dev/null

# ---------- Update Packages ----------
print_step "4" "Updating Packages"

sudo apt update -y

# ---------- Install OpenVPN ----------
print_step "5" "Installing OpenVPN"

sudo apt install openvpn -y

# ---------- Install OVPM ----------
print_step "6" "Installing OVPM"

sudo apt install ovpm -y

# ---------- Enable OVPM Service ----------
print_step "7" "Starting OVPM Service"

sudo systemctl start ovpmd
sudo systemctl enable ovpmd

# ---------- Initialize VPN ----------
print_step "8" "Initializing VPN"

sudo ovpm vpn init --hostname "$ipv4"

# ---------- Configure Sudoers ----------
print_step "9" "Configuring Sudo Permissions"

sudo sed -i '/ovpm/d' /etc/sudoers

echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/ovpm' | \
sudo EDITOR='tee -a' visudo

# ---------- Web Directory Setup ----------
print_step "10" "Creating Web Directory"

sudo mkdir -p /var/www/html/p/open/
sudo touch /var/www/html/p/open/index.php
sudo chown -R www-data:www-data /var/www/html/p/open

# ---------- MIME Configuration ----------
print_step "11" "Configuring MIME Type"

echo "application/x-openvpn-profile ovpn" | \
sudo tee -a /etc/mime.types > /dev/null

sudo systemctl restart apache2

# ---------- Finished ----------
print_step "12" "Installation Complete"

echo "OpenVPN installed successfully."
echo "Have fun :)"
