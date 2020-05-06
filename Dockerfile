FROM navidonskis/nginx-php5.6

MAINTAINER FEDOROV Artiom <fedorov.artiom@gmail.com>

COPY . /var/www/
COPY configs/nginx/sites-enabled:/etc/nginx/sites-enabled
COPY configs/php5.6/custom.ini:/etc/php/5.6/fpm/conf.d/custom.ini

