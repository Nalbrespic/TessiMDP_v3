Tessi MD Portail
================
> A Symfony project created on July 10, 2017, 1:34 pm.  

Intranet app to handle all production of Montargis


technical informations (production env)
----------------------
- Debian 8
- nginx 1.6.2
- PHP 7.0.33 (FPM)
- Symfony 3.4.37
- MySQL 5.6 + Oracle (on other hosts)

main external dependency : https://github.com/Tessi-Tms/TmsLogisticBundle (logistic WS for Guyancourt)


install server
--------------
```
git clone git@github.com:Tessi-Tms/TessiMDP.git .
composer install
```
- fill-in values of ```app/config/parameters.yml``` prompted
    - database password
    - secret  


force update dependencies
-------------------------
- edit ```composer.json``` if needed  
```
COMPOSER_MEMORY_LIMIT=-1 composer update
rm -Rf app/cache/{dev,prod}
```
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
rm -Rf app/cache/{dev,prod}
```


development / qualification environment configuration
---------------------------------------
with SF profiling (ie. debug bar) enabled  
- edit ```web/app.php``` :  
```$kernel = new AppKernel('dev', true);```   
- (only for qualification) edit ```app/config/config_dev.yml``` :  
```database_name: ecommerce```
- then exec :  
```rm -Rf app/cache/{dev,prod}/```
  
qualif runs like dev , but on production database.
dev uses 'preprod' database.


empty preprod DB
----------------
```shell script
echo "SET FOREIGN_KEY_CHECKS = 0;" > ./temp.sql
mysqldump --add-drop-table --no-data -h 172.17.82.194 -u Tessi_Admin -p ecommerce_preprod | grep 'DROP TABLE' >> ./temp.sql # extract drop queries
    password
echo "SET FOREIGN_KEY_CHECKS = 1;" >> ./temp.sql
mysql -h 172.17.82.194 -u Tessi_Admin -p ecommerce_preprod < ./temp.sql # run script
    password
rm -f temp.sql # remove temp script
```


copy prod DB to preprod
-----------------------
```shell script
mysqldump --single-transaction=TRUE -h 172.17.82.194 -u Tessi_Admin -p ecommerce | gzip --fast ecommerce.sql.gz # dump prod
    password
gunzip < ecommerce.sql.gz | mysql -h 172.17.82.194 -u Tessi_Admin ecommerce_preprod # load preprod
    password
rm -f ecommerce.sql.gz # remote dump file
```
