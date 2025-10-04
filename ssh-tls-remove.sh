sudo sed -i 's/<VirtualHost _default_:8443>/<VirtualHost _default_:443>/g' /etc/apache2/sites-available/default-ssl.conf
sudo sed -i 's/Listen 0.0.0.0:8443/Listen 0.0.0.0:443/g' /etc/apache2/ports.conf

systemctl stop stunnel4
systemctl stop sslh

apt remove stunnel4 sslh -y
rm -fr /etc/stunnel
rm -fr /etc/default/sslh

systemctl restart apache2

clear
echo "SSH TLS Removed ! "
