ARG BASETAG=22.2.2-apache
FROM nextcloud:$BASETAG as runtime

RUN apt update && apt install -y wait-for-it jq

COPY extension/ /usr/src/nextcloud/apps/georchestra/
COPY runtime /

# Move the official nextcloud install file. to, first, wait for the db, then install
# nextcloud, then eventually other scripts
#RUN cp /entrypoint.sh /docker-entrypoint.d/01-install
# remove the "exec $@" instruction at the end of the "official" install script.
RUN head -n -1 /entrypoint.sh > /docker-entrypoint.d/01-install && chmod +x /docker-entrypoint.d/01-install

#COPY docker/run.sh /run.sh
#WORKDIR /var/www/html/
#RUN ls -alih && pwd
#RUN php occ app:install groupfolders

ENTRYPOINT ["/docker-entrypoint.sh"]
CMD ["apache2-foreground"]


FROM runtime AS tester

RUN apt update && apt install -y git

# Install XDebug
RUN cd /usr/src && \
    git clone https://github.com/xdebug/xdebug.git && \
    cd xdebug && \
    phpize && \
    ./configure --enable-xdebug && \
    make && \
   make install
COPY runtime/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN mkdir -p /app /home/user && chmod 777 /home/user
ENV HOME=/home/user
