#!/bin/bash

if [ ! -f "/etc/systemd/system/s-box.service" ]; then
        echo "The service is not installed."
        return
    fi

    # Stop and disable the service
    sudo systemctl stop s-box.service
    sudo systemctl disable s-box.service >/dev/null 2>&1

    # Remove service file
    sudo rm /etc/systemd/system/s-box.service >/dev/null 2>&1
    sudo rm -rf /etc/s-box
    sudo rm -rf /root/shahanpanel
    sudo systemctl reset-failed
    clear
    echo "Uninstallation completed."
