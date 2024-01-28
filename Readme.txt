For Local ENV with docker compose
Need to install docker compose on local machine.

In Local Machine
** install docker
https://www.docker.com/get-started

** install composer as global
https://getcomposer.org/download/

** Add ssh key ot git hub
ssh-keygen
cat ~/.ssh/id_rsa.pub

** Set up local docker
cd ~
git clone git@github.com:aungkokoye/ukmac.git
cd ukmac
mkdir ukmac_symfony
cd ukmac_symfony
git init
git remote add origin git@github.com:aungkokoye/ukmac_symfony.git
git checkout master
git pull
composer install

** run docker
docker-compose up --build -d
docker-compose down

Now Web app is available at http://127.0.0.1:8750/

**go into webapp container
docker exec -it webapp bash

Inside webapp container
$ php bin/console doctrine:database:create  (**  That create database)
$ php bin/console doctrine:migrations:migrate  ( ** run migration)

Create New User
$ php bin/console app:create-user <email> <password>
Now Web app is available at http://127.0.0.1:8750/


Shut down web app
$ docker-compose down

++++++++    ============================================================================== +++++++++

Ubuntu Server
$ lsb_release -d  (* check Ubuntu version)
$ ssh <user_name>@<ip>


Apache
$ sudo apt-get update && sudo apt-get upgrade
$ sudo apt-get -y install apache2
$ apache2 -v   (** check  apache version)

$ sudo service apache2 restart
$ sudo apache2ctl configtest

PHP
$ sudo apt-add-repository ppa:ondrej/php
$ sudo apt-add-repository ppa:ondrej/apache2
$ sudo apt-get update
$ sudo apt-get -y install php7.4
$ php -v ( ** check php version)


MySql
$ sudo apt-get install mysql-server
$ mysql -V (** check mysql version)
$ sudo mysql_secure_installation
$ sudo mysql  <to mysql console>
$ sudo service mysql status
$ sudo service mysql stop
$ sudo service mysql start


Module Install
$ sudo apt install openssl php7.4-common php7.4-curl php7.4-json php7.4-mbstring php7.4-mysql php7.4-xml php7.4-zip
$ sudo apt install php7.4-common php7.4-bcmath openssl php7.4-json
$ sudo apt-get install php7.4-gd
$ sudo apt-get install php7.4-imagick
$ sudo apt install php7.4-mysql
$ php -v    (CHECK YOUR PHP VERSION)

$ a2enmod php7.4  (replace with your php version)
$ sudo service apache2 reload

Create User in MySql
$ sudo mysql  ( ** into mysql shell )
$ CREATE USER '<newuser>'@'localhost' IDENTIFIED BY '<password>';
$ GRANT ALL PRIVILEGES ON * . * TO '<newuser>'@'localhost';
$ FLUSH PRIVILEGES;

Mysql 8 config
$ cd /etc/mysql
$ sudo vim my.cnf

Add following line
[mysqld]
default_authentication_plugin= mysql_native_password

$ sudo service mysql stop
$ sudo service mysql start

Composer Install
https://getcomposer.org/download/

Git
Before we start git, need to change some permission and  ownership
$ sudo chown -R www-data:www-data /var/www/html
$ sudo chmod -R 771 /var/www/html

$ who ( *got current user)
$  sudo usermod -aG <currentuser>  www-data   // add current user to web server group
$  sudo usermod -aG www-data  <currentuser>  // add web server to current user group
$ cat /etc/group

Check as
grep <username> |  /etc/group

$ cd /var/www/html
$ git init
$ git remote add origin <http: url from git-hub account)
$ git pull origin master

$ sudo chown -R www-data:www-data /var/www/html
$ sudo chmod -R 777 /var/www/html/var


Symfony set up

Change Prod Env
$ change APP_ENV=prod in .env
$ composer dump-env prod
$ composer install --no-dev --optimize-autoloader
$ sudo chown -R www-data:www-data /var/www/html
$ sudo chmod -R 777 /var/www/html/var
$ php bin/console --env=prod cache:clear

$ vim /var/www/html/.env

Edit MySql connection string
Example DATABASE_URL="mysql://<username>:<password>@<ip address>:3306/db"

$ cd /var/www/html

$ php bin/console  doctrine:database:create
$ php bin/console doctrine:migrations:migrate

Apache host set up

$ sudo su

# cd /etc/apache2/sites-available/
# cp 000-default.conf 000-default.conf-copy
# rm 000-default.conf
# touch 000-default.conf
# cp /var/www/html/000-default cd /etc/apache2/sites-available/
# a2enmod rewrite
# a2ensite 000-default
# /etc/init.d/apache2 restart

WebSite User create

$ cd /var/www/html
$ php bin/console app:user-create <email> <password>
User: admin@loadberry.com , pass: @ungKM03Admin


Php setting for file upload size

$ sudo find / -name php.ini


Change these values in all files.

upload_max_filesize = 50M
post_max_size = 50M
memory_limit = 50M
max_execution_time = 120   (380)
max_input_time = 120

.env add to gitignore
git rm .env --cached
git commit -m "Stopped tracking .env File"

Add ssh user in Digital Ocean drop let
** create droplet with password enable

$ ssh root@ <ip>
# sudo adduser newuser
# usermod -aG sudo newuser
# exit

$ ssh <new_user>@ <ip>
$ cd ~
$ mkdir .ssh
$ cd .ssh
$ #exit

$ cat ~/.ssh/id_rsa.pub | ssh <new_user>@<ip> "cat >> ~/.ssh/authorized_keys"
