## FILEPATH: app.py

TODO list of tasks to be completed.

### PDF

- "Delete" button also remove related data in the Vector Database.
- Implement various statuses for PDF uploads, such as "Uploaded," "Parsed," "Successful," and "Failed."
- Display error messages if the upload job fails.
- Retrieve and possibly modify data from the Vector Database for review.
- If the file exists but the hash differs when uploading a new PDF, delete the old data from the Vector Database (VD) and restart the job with the new file.
- Localize
- Edit TXT in editor (from file), then update it in Vector Database.

- (done 31.10.2023) If doc need OCR -> Send to LLM to correct the text, drop garbage text and return clean data to ingest into vector database.
- (done 31.10.2023) When uploading a new PDF, check if the filename already exists and whether the hash is identical; if so, skip the upload.
- (done 31.10.2023) REMOVED - Evaluate the necessity of the "Resync on PDF" feature; consider discontinuing it if not needed.
- (done 31.10.2023) Enable the "Delete" button and display a confirmation popup to remove the file (or folder), the corresponding database record, 
- (done 28.10.2023) Capture errors and warnings and log them into the database.

### WEBSITE

- Implement a "Resync on Website" feature.
- If a PDF or DOC file is discovered during parsing, push it to the database via the PDF Handler. Add a checkbox to this. Parse also PDF/Doc files.
- Implement a Smart Resync feature.
- Investigate why the sync occasionally stops and implement a restart mechanism if this occurs.
- Check this tables: failed_jobs, jobs, onboarding_steps, password_reset_tokens, personal_access_tokens, text_data_sources, web_pdfdatasourceerrorlog
- Title of the crawled URL is not read.
- Localize
- Deleting chatbot will not clear table website_data_sources, pdf_data_sources, crawled_pages, chatbot_settings, chat_histories
- Pagination for WEB and DATA listing.
- website_data_sources (table), scrapper completion improvement, recall to continue.
- LLM Settings add to dashboard (Temparature, OpenAI key, OpenAI Model.)

- (done 31.10.2023) Creating a new Chatbot, will be enabled in DB by default. Now it was empty the field. 
- (done 31.10.2023) Also URL site to be edit, mostly if is PDF site, need a valid URL for the website where the bubble chat will be implemented for CORS issues. 
- (done 29.10.2023) Crawler do not crawl binary files/url.
- (done 29.10.2023) Replace in chat.js and search.js the language strings.
- (done 28.10.2023) Capture errors and warnings and log them into the database.
- (done 28.10.2023) Investigate why PNG or other binary files are being parsed from a website.
- (done 28.10.2023) Chat history to show.
- (done 28.10.2023) Determine why the pages that have been crawled are not being displayed from the database.


### CHAT

- Mobile friendly

- (done 31.10.2023) Localize the chat.js and search.js
- (done 29.10.2023) Check why have localhost inside of JS and replace programatically with real URL.

### SERVER - DOCKER

- (done 26.10.2023) Implement NGINX to have a possibility to add SSL.
- (done 24.10.2023) Move the website_data_sources directory to an external volume to facilitate mounting on larger data storage and to ensure data persistence after system restarts.