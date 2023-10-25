#!/bin/bash

# Define the file path as a variable
CHAT_JS_FILE="/app/web/static/chat.js"

# Actual replacement
sed -i "s|http://0.0.0.0:8000|${APP_URL}|g" $CHAT_JS_FILE

# Check if /chat/init exists in the file
if grep -q "/chat/init" /app/web/static/chat.js; then
    # If it exists, run the sed command
    sed -i "s|/chat/init|${APP_URL}/chat/init|g" $CHAT_JS_FILE
fi

# Start your app normally
exec "$@"