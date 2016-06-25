# About IpCar project  

PHP script for website which allows to buy & sell used cars.


# Server requirements:
* PHP 5 or higher
* MySQL 5.3 or higher

# Installation steps:

## Execute MySQL database scripts.

**Create structure of the database**  

For English version execute /database/eng/structure/database.sql  

For Russian version execute /database/rus/structure/database.sql  


**Import data to the database**  

For English version execute all scripts from folder /database/eng/data  

For Russian version execute all scripts from folder /database/rus/data  

## Update configuration in /sources/init.php file  

**Set the language of the website:**  

For English version: $configuration->lang = "eng";  

For Russian version: $configuration->lang = "rus";  

**Update name and url of the website:**  

$configuration->wwwroot = 'http://www.siteroot.com';  

$configuration->siteName = 'siteroot.com';  

**Update email addresses:**  

$configuration->supportEmail = 'support@siteroot.com';
  
$configuration->contactEmail = 'contact@siteroot.com';  

**Configure connection to the database:**  

$db = array(  

"type" => 'mysql',  

"host" => 'localhost',  

"port" => "",  

"name" => 'dbname', 

"user" => 'dbuser',  

"pass" => 'dbpassword'  

);  


# Access management console:
1. Go to _http://web_site_domain/management_  

2. Login as administrator:  

* Email: admin@admin.com
* Password: 1

