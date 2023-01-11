#!/bin/bash
link=$('https://github.com/HamedAp/Ssh-User-management/archive/refs/heads/main.zip')

sudo wget -O update.zip $link
sudo unzip -o update.zip
