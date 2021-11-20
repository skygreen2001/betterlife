#!/bin/sh

until nc -z -w 1 betterlife 9000 || nc -z -w 1 127.0.0.1 9000 ; do
  echo "betterlife PHP is unavailable - sleeping"
  sleep 2
done

echo "betterlife PHP is up - executing command"

exec nginx -g 'daemon off;'