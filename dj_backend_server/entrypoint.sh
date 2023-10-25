#!/bin/bash

# Define the file path as a variable
CHAT_JS_FILE="/app/web/static/chat.js"

# Actual replacement
sed -i "s|http://0.0.0.0:8000|${APP_URL}|g" $CHAT_JS_FILE

# Check if the pattern with APP_URL already exists
if grep -q '("${APP_URL}/chat/init")' $CHAT_JS_FILE; then
  echo "Pattern with APP_URL already exists, doing nothing."

# Check if the pattern with the default URL exists
elif grep -q '("http://0.0.0.0:8000/chat/init")' "$CHAT_JS_FILE"; then
  echo "Replacing default URL with APP_URL."
  sed -i "s|http://0.0.0.0:8000|${APP_URL}|g" "$CHAT_JS_FILE"

# If none of the above conditions are met, append APP_URL to /chat/init
else
  echo "Appending APP_URL to /chat/init."
  sed -i "s|/chat/init|${APP_URL}/chat/init|g" "$CHAT_JS_FILE"
fi

# Start your app normally
exec "$@"   