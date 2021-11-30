FROM camptocamp/nextcloud:22

COPY --chown=www-data:www-data extension/. /var/www/html/apps/georchestra/
#COPY docker/run.sh /run.sh
#WORKDIR /var/www/html/
#RUN ls -alih && pwd
#RUN php occ app:install groupfolders
#ENTRYPOINT ["/entrypoint.sh"]
#CMD ["/run.sh"]

