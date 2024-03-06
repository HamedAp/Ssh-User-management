#!/bin/bash
sed -i 's@net.ipv6.conf.all.disable_ipv6 = 1@@' /etc/sysctl.conf
sed -i 's@net.ipv6.conf.default.disable_ipv6 = 1@@' /etc/sysctl.conf

sudo sysctl -p
clear
echo "ipv6 Enabled ! :) "
