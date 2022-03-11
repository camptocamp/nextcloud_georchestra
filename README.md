## Build and start

```bash
docker-compose build
docker-compose up -d
```

Now NextCloud should be available at: https://georchestra-127-0-1-1.traefik.me/files

## Run tests (do not pass yet)

```bash
    docker-compose run \
        --user `id -u` \
        --entrypoint="" \
        -v $(PWD)/extension:/src/extension \
        nextcloud make -C /src/extension/ test
```

## Test an upgrade

```bash
# Start with version 22.2.2-apache
docker-compose down -v
docker-compose build --build-arg BASETAG=22.2.2-apache
docker-compose up -d
docker-compose logs -f nextcloud

# Copy src volume content in a temporary directory
sudo rm -rf /tmp/nextcloud-app_nextcloud_src
sudo cp -rp /home/amorvan/data/docker/volumes/nextcloud-app_nextcloud_src/_data /tmp/nextcloud-app_nextcloud_src

# Try an upgrade to 22.2.3-apache
sudo rm -rf /home/amorvan/data/docker/volumes/nextcloud-app_nextcloud_src/_data
sudo cp -rp /tmp/nextcloud-app_nextcloud_src /home/amorvan/data/docker/volumes/nextcloud-app_nextcloud_src/_data
docker-compose build --build-arg BASETAG=22.2.3-apache
docker-compose rm --stop -f nextcloud
docker-compose up -d
docker-compose logs -f nextcloud
```

## Links

Show NextCloud logs:

docker-compose exec nextcloud sh -c "tail -f data/nextcloud.log | jq"

NextCloud base image:

https://github.com/nextcloud/docker

Debuggage VSCode:

https://confluence.camptocamp.com/confluence/display/GEOR/NextCloud

Postmortem:

https://confluence.camptocamp.com/confluence/display/GEOR/Postmortem+Projet+Nextcloud


# Debug LDAP Sync

Set log level to debug:

```bash
docker-compose exec --user www-data nextcloud php occ config:system:set --type="integer" --value="0" "loglevel"
```

Run synchronization:

```bash
cat <<EOF | docker-compose exec -T nextcloud_db psql -U nextcloud
UPDATE oc_jobs SET last_run = 0, reserved_at = 0 WHERE id = 36;
UPDATE oc_appconfig SET configvalue = '0' WHERE appid = 'user_ldap' AND configkey = 's01_lastChange';
EOF

docker-compose exec --user www-data nextcloud php cron.php
```

Display list of LDAP users:

```sql
cat <<EOF | docker-compose exec -T nextcloud_db psql -U nextcloud
SELECT * FROM oc_ldap_user_mapping;
EOF
```

Display logs:

```bash
make logs
```
