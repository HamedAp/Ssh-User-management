#!/bin/bash
# By Hamed Ap
# For Shahan Panel :) Dozdi Nakon BiShoor :))

apt install unzip -y
cd /root
if [[ -d "randomfakehtml-master" ]]; then
	cd randomfakehtml-master
else
	wget https://github.com/GFW4Fun/randomfakehtml/archive/refs/heads/master.zip
	unzip master.zip && rm master.zip
	cd randomfakehtml-master
	rm -rf assets
	rm ".gitattributes" "README.md" "_config.yml"
fi

RandomHTML=$(a=(*); echo ${a[$((RANDOM % ${#a[@]}))]} 2>&1)
echo "Random template name: ${RandomHTML}"

if [[ -d "${RandomHTML}" && -d "/var/www/html/" ]]; then
	cp -a ${RandomHTML}/. "/var/www/html/"
 clear
	echo  "Fake Website Installed ! :) "
else
	echo "Extraction error!"
fi
