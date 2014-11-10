Yii2-CMS
========

A CMS written in Yii2, under heavy development, just for testing purpose. 

Only the backend works. 

Installation
------------

1. Download or clone all the files from the GitHub
2. Create a database and run the file (MySql) from the SQL folder. 
3. In the common/config/main-local.php file define your sql connection.
4. Install composer, if you don't have: 'curl -s http://getcomposer.org/installer | php' and 'mv composer.phar /usr/local/bin/composer'
5. Add to the composer with the following command: 'composer global require "fxp/composer-asset-plugin:1.0.0-beta3"'
6. Update your composer with 'composer self-update' 
7. Update with the command 'composer update'
8. Check the reqirements with yourhost/requirements.php (need php5-mcrypt, php5-intl)
9. Load the backend with: yourhost/backend/web
10. Login with admin@site.com:admin123
