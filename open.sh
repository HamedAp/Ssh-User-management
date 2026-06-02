#!/bin/bash

print_step() {
    clear
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
sleep 2

# ---------- TUN Check ----------
print_step "1" "Checking TUN Device"

if [[ ! -e /dev/net/tun ]] || ! (exec 7<>/dev/net/tun) 2>/dev/null; then
    echo "TUN device is not enabled."
    exit 1
fi

echo "TUN device detected."
sleep 2

# ---------- Get Server Address ----------
print_step "2" "Getting Server Address"

ipv4=$(curl -s ipv4.icanhazip.com)

read -p "Default IP is ${ipv4}. Press ENTER to use it: " serveraddress

if [[ -n "$serveraddress" ]]; then
    ipv4="$serveraddress"
fi

echo "Using: $ipv4"
sleep 2

# ---------- Add Repository ----------
print_step "3" "Adding Repository"

sudo sed -i '/ovpm/d' /etc/apt/sources.list &>/dev/null

echo "deb [trusted=yes] https://cad.github.io/ovpm/deb/ ovpm main" | \
sudo tee -a /etc/apt/sources.list &>/dev/null

echo "Repository added."
sleep 2

# ---------- Update Packages ----------
print_step "4" "Updating Packages"

sudo apt update -y &>/dev/null

echo "Packages updated."
sleep 2

# ---------- Install OpenVPN ----------
print_step "5" "Installing OpenVPN"

sudo apt install openvpn -y &>/dev/null

echo "OpenVPN installed."
sleep 2

# ---------- Install OVPM ----------
print_step "6" "Installing OVPM"

sudo apt install ovpm -y &>/dev/null

echo "OVPM installed."
sleep 2

# ---------- Enable Service ----------
print_step "7" "Starting OVPM Service"

sudo systemctl start ovpmd &>/dev/null
sudo systemctl enable ovpmd &>/dev/null

echo "OVPM service started."
sleep 2

# ---------- Initialize VPN ----------
print_step "8" "Initializing VPN"

sudo ovpm vpn init --hostname "$ipv4" &>/dev/null

echo "VPN initialized."
sleep 2

# ---------- Configure Sudo ----------
print_step "9" "Configuring Permissions"

sudo sed -i '/ovpm/d' /etc/sudoers &>/dev/null

echo 'www-data ALL=(ALL:ALL) NOPASSWD:/usr/bin/ovpm' | \
sudo EDITOR='tee -a' visudo &>/dev/null

echo "Permissions configured."
sleep 2

# ---------- Setup Web Directory ----------
print_step "10" "Creating Web Directory"

sudo mkdir -p /var/www/html/p/open/ &>/dev/null
sudo touch /var/www/html/p/open/index.php &>/dev/null
sudo chown -R www-data:www-data /var/www/html/p/open &>/dev/null
echo "Web directory created."
sleep 2

# ---------- Configure MIME ----------
print_step "11" "Configuring MIME Type"
echo "application/x-openvpn-profile ovpn" | \
sudo tee -a /etc/mime.types &>/dev/null
sudo systemctl restart apache2 &>/dev/null
echo "MIME configured."
sleep 2

# ---------- Install Traffic Script ----------
print_step "12" "Installing Traffic Monitor"
sudo wget -q -4 -O /root/open-traffic.sh \
https://raw.githubusercontent.com/HamedAp/ShahanPanel/refs/heads/main/open-traffic.sh &>/dev/null
(
    crontab -l 2>/dev/null
    echo "* * * * * bash /root/open-traffic.sh >/dev/null 2>&1"
) | crontab - &>/dev/null
chmod +x /root/open-traffic.sh &>/dev/null
echo "Traffic monitor installed."
sleep 2

# ---------- Finished ----------
print_step "12" "Installation Complete"

echo "OpenVPN installed successfully."
echo ""
echo "Server Address: $ipv4"
echo ""
echo "Have fun :)"
