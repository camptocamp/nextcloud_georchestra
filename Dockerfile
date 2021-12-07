FROM nextcloud:22-apache
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


