#!/bin/bash

DIR=/docker-entrypoint.d/
if [[ -d "$DIR" ]]
then
    /bin/run-parts --verbose "$DIR" -a "$@"
fi

exec "$@"
