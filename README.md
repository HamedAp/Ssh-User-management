[![Total Downloads](https://img.shields.io/github/downloads/HamedAp/Ssh-User-management/total.svg)](https://github.com/HamedAp/Ssh-User-management/)
[![Last Version](https://img.shields.io/github/release/HamedAp/Ssh-User-management/all.svg)](https://github.com/HamedAp/Ssh-User-management/)
[![Last Release Date](https://img.shields.io/github/release-date/HamedAp/Ssh-User-management.svg)](https://github.com/HamedAp/Ssh-User-management/)
[![GitHub Stars](https://img.shields.io/tokei/lines/github/HamedAp/Ssh-User-management.svg)](https://github.com/HamedAp/Ssh-User-management/)
[![GitHub Stars](https://img.shields.io/github/stars/HamedAp/Ssh-User-management.svg)](https://github.com/HamedAp/Ssh-User-management/)
[![GitHub Forks](https://img.shields.io/github/forks/HamedAp/Ssh-User-management.svg)](https://github.com/HamedAp/Ssh-User-management/)

# ShaHaN SSH Panel

پنل مدیریت و فروش پروتکل ssh


❇️ Connection limitation

❇️ RestApi

❇️ Multi Server

❇️ Dynamic Expire time 



# جهت حمایت از ما ادرس ولت تتر 💰
USDT TRC20 Address :
TWAcjmHKhqMQ58xXo4do4RgALLkfMm61Ux

USDT ERC20 Address :
0x1426afae97fef9f4928e4e171593cae9f5c630cd
 
# اموزش نصب :

دستور زیر را در ترمینال خود وارد کرده و یوزر و پسورد ادمین را وارد کنید .

````
bash <(curl -Ls https://raw.githubusercontent.com/HamedAp/Ssh-User-management/master/install.sh --ipv4)
````

جهت آپدیت پنل نیز همان دستور بالا را وارد کرده ( یوزر و پسورد ادمین نیاز نیست - یوزر ها پاک نمیشوند ) 





# SSL Installer ( Only SSL - NOT Panel - Need Domain )

در صورتی که دامنه دارید بعد از دستور نصب ( دقت کنید بعد از دستور نصب )  این دستور را بزنید .


````
bash <(curl -Ls https://raw.githubusercontent.com/HamedAp/Ssh-User-management/master/ssl.sh --ipv4)
````


# Reset Admin Password

اگر نیاز به ریست یوزر و پسورد ادمین دارید این دستور را زده و دوباره دستور نصب را بزنید :

````
mysql -e "use ShaHaN;drop table setting;"
````

# Will Be Added On Next Update 

-Traffic Limit


# Preview
![](screenshot/index.PNG)
![](screenshot/online2.PNG)
![](screenshot/newuser.PNG)
![](screenshot/setting.PNG)
![](screenshot/filtering.PNG)
![](screenshot/menu.PNG)


# Special Thanks To [MasoodSajedi](https://github.com/masoodsajedi)
