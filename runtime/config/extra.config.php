<?php
$CONFIG = array(
    'debug' => boolval(getenv('NEXTCLOUD_DEBUG')) ?: false,
    'maintenance' => boolval(getenv('NEXTCLOUD_MAINTENANCE')) ?: false
);

