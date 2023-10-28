#!/bin/bash
clear
echo ""
printshahan "This Script Will Reboot Server" 0.1
echo ""
echo ""
printshahan "Please Wait . . ." 0.1
echo ""
echo ""

rm -fr /var/log/auth.log
systemctl restart syslog
crontab -r -u www-data
reboot
