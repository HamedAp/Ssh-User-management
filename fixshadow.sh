#!/bin/bash

cat >  /root/fixshadowsocks.sh << ENDOFFILE
#!/bin/bash

if systemctl is-active --quiet shadowsocks; then
    echo "shadowsocks is already running"
else
    if sudo systemctl start shadowsocks; then
        echo "shadowsocks started successfully"
    else
        echo "Failed to start shadowsocks"
        exit 1
    fi
fi

if systemctl is-active --quiet s-box; then
    echo "s-box is already running"
else
    if sudo systemctl start s-box; then
        echo "s-box started successfully"
    else
        echo "Failed to start s-box"
        exit 1
    fi
fi



ENDOFFILE
sudo chmod a+rx /root/fixshadowsocks.sh


(sudo crontab -l ; echo "* * * * * bash /root/fixshadowsocks.sh >/dev/null 2>&1") | crontab -

clear
echo "Shadowsocks Fixer Installed ."
