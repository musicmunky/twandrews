#!/bin/bash

echo "Starting websocket process..."
nohup php -q /var/www/twandrews.com/public_html/php/runwebsock.php > /dev/null 2>&1 &
