#!/bin/bash
sed -i 's@net.ipv6.conf.all.disable_ipv6 = 1@@' /etc/sysctl.conf
sed -i 's@net.ipv6.conf.default.disable_ipv6 = 1@@' /etc/sysctl.conf

echo "net.ipv6.conf.all.disable_ipv6 = 1
net.ipv6.conf.default.disable_ipv6 = 1" >> /etc/sysctl.conf

sudo sysctl -p
systemctl restart apache2

