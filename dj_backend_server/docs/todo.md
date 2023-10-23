## FILEPATH: app.py

TODO list of tasks to be completed.

# PDF

- Enable the "Delete" button and display a confirmation popup to remove the file (or folder), the corresponding database record, and any related data in the Vector Database.s
- Evaluate the necessity of the "Resync on PDF" feature; consider discontinuing it if not needed.
- Implement various statuses for PDF uploads, such as "Uploaded," "Parsed," "Successful," and "Failed."
- Display error messages if the upload job fails.
- Retrieve and possibly modify data from the Vector Database for review.

# WEBSITE

- Implement a "Resync on Website" feature.
- Investigate why PNG or other binary files are being parsed from a website.
- If a PDF or DOC file is discovered during parsing, push it to the database via the PDF Handler.

# CHAT

- Check why have localhost inside of JS and replace programatically with real URL.

# SERVER - DOCKER

- Implement NGINX to have a possibility to add SSL.