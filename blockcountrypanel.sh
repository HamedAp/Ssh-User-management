#!/bin/bash

CHECK_OS(){
	[[ $EUID -ne 0 ]] && echo "Run as root!" && exit 1
	if [[ -f /etc/redhat-release ]]; then Src="yum";release="centos";
	elif grep -Eqi "debian" /etc/issue; then Src="apt";release="debian";
	elif grep -Eqi "ubuntu" /etc/issue; then Src="apt";release="ubuntu";
	elif grep -Eqi "centos|red hat|redhat" /etc/issue; then Src="yum";release="centos";
	elif grep -Eqi "debian|raspbian" /proc/version; then Src="apt";release="debian";
	elif grep -Eqi "ubuntu" /proc/version; then Src="apt";release="ubuntu";
	elif grep -Eqi "centos|red hat|redhat" /proc/version; then Src="yum";release="centos";
	fi
}

CHECK_OS
	$Src -y update
	$Src -y install build-essential linux-headers-$(uname -r)
	$Src -y install curl gzip tar perl xtables-addons-common xtables-addons-dkms libtext-csv-xs-perl libmoosex-types-netaddr-ip-perl libnet-cidr-lite-perl iptables-persistent
	if [[ "${release}" == "debian" ]]; then
		printf 'y\n' | $Src -y install module-assistant xtables-addons-source
		printf 'y\n' | module-assistant prepare
		printf 'y\n' | module-assistant -f auto-install xtables-addons-source
	fi	
	rm -rf /usr/share/xt_geoip/ && mkdir -p /usr/share/xt_geoip/ && chmod a+rwx /usr/share/xt_geoip/
 	modprobe x_tables && modprobe xt_geoip
	lsmod | grep xt_geoip
	chmod +x -f /usr/lib/xtables-addons/xt_geoip_build
	chmod +x -f /usr/libexec/xtables-addons/xt_geoip_dl
	systemctl enable cron
 	ufw disable &> /dev/null
	crontab -l | grep -v "ipban-update.sh" | crontab -
	(crontab -l 2>/dev/null; echo "$(shuf -i 1-59 -n 1) $(shuf -i 1-23 -n 1) * * * bash /usr/share/ipban/ipban-update.sh >/dev/null 2>&1") | crontab -

	systemctl enable netfilter-persistent.service





mkdir -p /usr/share/ipban/ && chmod a+rwx /usr/share/ipban/
cat > "/usr/share/ipban/download-build-dbip.sh" << EOF
#!/bin/bash
	dtmp="/usr/share/xt_geoip/tmp/"
	rm -rf "\${dtmp}" && mkdir -p "\${dtmp}" && cd "\${dtmp}"
	# Download dbipLite Full
	timestamp=\$(date "+%Y-%m")
	curl -m 9 -fLO "https://download.db-ip.com/free/dbip-country-lite-\${timestamp}.csv.gz" &> /dev/null
	curl -m 9 -fLO "https://mailfud.org/geoip-legacy/GeoIP-legacy.csv.gz" -O &> /dev/null
	if [[ "\$?" != 0 ]]; then
	curl -m 9 -fLO "https://legacy-geoip-csv.ufficyo.com/Legacy-MaxMind-GeoIP-database.tar.gz" &> /dev/null
	fi		
	# Combine all csv and remove duplicates 
	gzip -dfq *.gz
	find . -name "*.tar" -exec tar xvf {} \;
	find . -name "*.tar.gz" -exec tar xvzf {} \;
	cat "\${dtmp}GeoIP-legacy.csv" | tr -d '"' | cut -d, -f1,2,5 > "\${dtmp}GeoIP-legacy-processed.csv"
	rm "\${dtmp}GeoIP-legacy.csv"
	cat *.csv > geoip.csv 
	sort -u geoip.csv -o "/usr/share/xt_geoip/dbip-country-lite.csv"
	rm -rf "\${dtmp}"
EOF
	chmod +x "/usr/share/ipban/download-build-dbip.sh"

# Create Update File
 mkdir -p /usr/share/ipban/ && chmod a+rwx /usr/share/ipban/
cat > "/usr/share/ipban/ipban-update.sh" << EOF
#!/bin/bash
	/usr/share/ipban/download-build-dbip.sh
	cd /usr/share/xt_geoip/
	/usr/libexec/xtables-addons/xt_geoip_build -s
	/usr/lib/xtables-addons/xt_geoip_build -D /usr/share/xt_geoip/
	cd && rm /usr/share/xt_geoip/dbip-country-lite.csv
	printf "\n\n" | sysctl -p && systemctl restart systemd-networkd.service iptables.service ip6tables.service
 	
EOF
chmod +x "/usr/share/ipban/ipban-update.sh"

sudo bash /usr/share/ipban/ipban-update.sh
iptables -F
iptables -A INPUT -p tcp --dport 80 -m geoip --src-cc "IR" -j "ACCEPT"
iptables -A INPUT -p tcp --dport 80  -j "DROP"
iptables -A INPUT -p tcp --dport 443 -m geoip --src-cc "IR" -j "ACCEPT"
iptables -A INPUT -p tcp --dport 443  -j "DROP"
iptables -A INPUT -p icmp -j DROP

url="https://raw.githubusercontent.com/HamedAp/Ssh-User-management/main/i.txt"
allcount=$(curl -s "$url" | wc -l)
curl -s "$url"  | while IFS= read -r line; do
((++line_number))
iptables -A OUTPUT -p tcp  --dport 80 -d $line -j DROP
iptables -A OUTPUT -p tcp  --dport 443 -d $line -j DROP
clear
echo "Iran IP Blocking ( List 1 ) : $line_number / $allcount "
done
