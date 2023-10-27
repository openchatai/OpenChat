#!/bin/bash

# Remove 'http://' or 'https://' prefix from APP_URL
CLEANED_APP_URL=${APP_URL#http://}
CLEANED_APP_URL=${APP_URL#https://}

echo "Replacing APP_URL with $CLEANED_APP_URL"

# Define the file path as a variable, for example:
NGINX_CONF="/etc/nginx/nginx.conf"

sed "s|yourdomain.com|$CLEANED_APP_URL|g" NGINX_CONF > /tmp/nginx.conf
mv /tmp/nginx.conf NGINX_CONF

# Start your app normally
# exec nginx -g "daemon off;"
