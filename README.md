# Muzi.ch

Muzi.ch is a website who permit to:

* Listen user shared music links
* Create an account and share music links
* Listen music links in automated player
* Create playlists
* Vote for shared links
* Favorite shared links
* Apply tag to owned shared music links
* Propose tags on shared music links
* Comment shared music links

## Install

Muzi.ch source code is old and corrently not maintened. The following howto
have been tested on debian 9 in a docker container.

```
# OS dependencies
apt-get install git curl gnupg apt-transport-https lsb-release ca-certificates zip unzip
# wget

# Need php5.x
curl https://packages.sury.org/php/apt.gpg | apt-key add -
echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list
apt-get update
apt-get install php5.6 php5.6-xml php5.6-gd libjpeg-dev php5.6-mongo php5.6-curl php5.6-mysql php5.6-mbstring

# Install muzi.ch
git clone https://gitea.bux.fr/bux/muzich.git
cd muzich
cp app/config/config.yml.template app/config/config.yml
cp app/config/parameters.yml.template app/config/parameters.yml
php composer.phar install

# Database
apt-get install mysql-server
service mysql start
mysql --execute "CREATE DATABASE muzich;"
mysql --execute "CREATE USER 'muzich'@'localhost';"
mysql --execute "GRANT ALL PRIVILEGES ON muzich.* To 'muzich'@'localhost' IDENTIFIED BY 'muzich';"

# First time, create database
php app/console doctrine:schema:create

# Start dev server
php app/console server:run
```

## Run tests

First time, execute:

```
# Get php unit 4
wget -O phpunit https://phar.phpunit.de/phpunit-4.phar
chmod +x phpunit

# Prepare database
mysql --execute "CREATE DATABASE muzich_test;"
mysql --execute "GRANT ALL PRIVILEGES ON muzich_test.* To 'muzich'@'localhost' IDENTIFIED BY 'muzich';"
```

```
./phpunit -c app src
```

## TODO

* Write a howto for installation
* Choose a licence
* Update dependencies
* list bugs and possible enhancements
* make tests gree
n