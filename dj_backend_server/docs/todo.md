## FILEPATH: app.py

TODO list of tasks to be completed.

### PDF

- Enable the "Delete" button and display a confirmation popup to remove the file (or folder), the corresponding database record, and any related data in the Vector Database.s
- Evaluate the necessity of the "Resync on PDF" feature; consider discontinuing it if not needed.
- Implement various statuses for PDF uploads, such as "Uploaded," "Parsed," "Successful," and "Failed."
- Display error messages if the upload job fails.
- Retrieve and possibly modify data from the Vector Database for review.
- When uploading a new PDF, check if the filename already exists and whether the hash is identical; if so, skip the upload.
- If the file exists but the hash differs when uploading a new PDF, delete the old data from the Vector Database (VD) and restart the job with the new file.
- Capture errors and warnings and log them into the database.
- Localize

### WEBSITE

- Implement a "Resync on Website" feature.
- Investigate why PNG or other binary files are being parsed from a website.
- If a PDF or DOC file is discovered during parsing, push it to the database via the PDF Handler.
- Implement a Smart Resync feature.
- Investigate why the sync occasionally stops and implement a restart mechanism if this occurs.
- Determine why the pages that have been crawled are not being displayed from the database.
- Capture errors and warnings and log them into the database.
- Localize

### CHAT

- Check why have localhost inside of JS and replace programatically with real URL.
- Localize the chat.jss and search.js

### SERVER - DOCKER

- Implement NGINX to have a possibility to add SSL.
- (done) Move the website_data_sources directory to an external volume to facilitate mounting on larger data storage and to ensure data persistence after system restarts.