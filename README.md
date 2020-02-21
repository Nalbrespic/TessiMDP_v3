Tessi MD Portail
================
> A Symfony project created on July 10, 2017, 1:34 pm.  

Intranet app to handle all production of Montargis

technical informations (production env)
----------------------
- Debian 8
- nginx 1.6.2
- PHP 7.0 (FPM)
- Symfony 3.4
- MySQL 5.6 + Oracle (on other hosts)

main external dependency : https://github.com/Tessi-Tms/TmsLogisticBundle (logistic WS for Guyancourt)

install server
--------------
```
git clone https://github.com/Tessi-Tms/TessiMDP.git .
composer install
```
- fill-in values of ```app/config/parameters.yml``` prompted  
- copy and fill-in .dist files (...)


force update dependencies
-------------------------
- edit ```composer.json``` if needed  
```COMPOSER_MEMORY_LIMIT=-1 composer update```  
- test the application
```  
git add composer.json composer.lock  
git commit -m "updated dependencies"  
git push
```


update server
-------------
```
git pull
composer install
```
