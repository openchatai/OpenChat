#!/bin/bash

# This script will keep your local modifications in chat.js and search.js, 
# while also updating your branch with the latest changes from the remote repository. 
# Remember to resolve any merge conflicts that might arise when you apply the stashed changes.

# Stage the modified files
git add dj_backend_server/web/static/chat.js
git add dj_backend_server/web/static/search.js

# Fetch the latest updates from the remote repository
git fetch

# Stash the staged changes
git stash

# Pull the latest changes from the remote branch
git pull origin lvalics/OpenChat.git

# Apply the stashed changes
git stash pop

# End of script
