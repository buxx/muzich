FROM debian:9

RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -yq \
  git curl gnupg apt-transport-https lsb-release ca-certificates \
  zip unzip wget lsb-release mysql-server && \
  curl https://packages.sury.org/php/apt.gpg | apt-key add - && \
  echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list && \
  apt-get update && \
  apt-get install -yq  php5.6 php5.6-xml php5.6-gd libjpeg-dev php5.6-mongo php5.6-curl php5.6-mysql php5.6-mbstring

RUN git clone https://github.com/buxx/muzich.git && \
  cd muzich && \
  cp app/config/config.yml.template app/config/config.yml && \
  cp app/config/parameters.yml.template app/config/parameters.yml && \
  php composer.phar install

RUN service mysql start && \
  mysql --execute "CREATE DATABASE muzich;" && \
  mysql --execute "CREATE USER 'muzich'@'localhost';" && \
  mysql --execute "GRANT ALL PRIVILEGES ON muzich.* To 'muzich'@'localhost' IDENTIFIED BY 'muzich';" && \
  cd /muzich && \
  php app/console doctrine:schema:create && \
  php app/console doctrine:fixtures:load -n

WORKDIR /muzich
COPY docker_bootstrap.sh /muzich/bootstrap.sh
CMD ["/bin/bash", "/muzich/bootstrap.sh"]
