# Openchat v1.1 (August 11, 2023)


## Added 🚀

- 🔥 Completely rewrote codebase in Django for improved performance and maintainability. Includes faster web scraping and PDF parsing using Jinja2.

- ➖ Removed dependency on llm-server microservice.

- ➕ Added support for Azure OpenAI and OpenAI APIs. 

- ⚡️ Significantly reduced build times.

- 🛠️ Enhanced developer environment with VSCode debugging scripts.

- 🐇 Integrated Celery task queue and debugging. 

- 🔃 Faster hot reloading for development.

- ♻️ Fully backward compatible with previous version.

## Known Issues
While testing and refinement is still underway, the product is ready for use. We will continuously improve and enhance the product in subsequent releases. Please contact us if any significant issues are encountered.

Here is an example Special Notes section:

## Special Notes

- The new Django codebase allows for easier customization and extensibility. See docs for details. 

- With the addition of Celery, it's important to properly configure and run Celery workers for async task handling.

- A huge thank you to all our users who have provided feedback and support leading up to this milestone release! We couldn't have done it without you.


Here is one way to improve that documentation link:

## Documentation

For complete documentation on the new Django codebase, please see:

- [Opechat Django Backend](dj_backend_server/readme.md) - Provides overview, setup instructions, API references, and customization guides for the Django backend services.