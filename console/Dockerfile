FROM yiisoftware/yii2-php:8.4-fpm-nginx

RUN apt-get update -yqq && apt-get install -y gettext-base

ENTRYPOINT ["php", "./yii"]