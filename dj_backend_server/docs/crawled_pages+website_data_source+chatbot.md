Based on the models provided, here are the relationships between the 3 models:

- WebsiteDataSource has a foreign key to Chatbot (chatbot field), indicating a one-to-many relationship. Each WebsiteDataSource belongs to one Chatbot, while each Chatbot can have multiple WebsiteDataSources.

- CrawledPages has a foreign key to WebsiteDataSource (website_data_source field), indicating another one-to-many relationship. Each CrawledPage belongs to one WebsiteDataSource, while each WebsiteDataSource can have multiple CrawledPages. 

- CrawledPages also has a chatbot_id field, which presumably references the id of the Chatbot model. This suggests a direct many-to-one relationship between CrawledPages and Chatbot - each CrawledPage belongs to one Chatbot, while each Chatbot can have many CrawledPages.

- So in summary:
  - Chatbot has a one-to-many relationship with WebsiteDataSource
  - WebsiteDataSource has a one-to-many relationship with CrawledPages
  - Chatbot also has a direct many-to-one relationship with CrawledPages

The key relationships are the one-to-many between Chatbot -> WebsiteDataSource and WebsiteDataSource -> CrawledPages.


erDiagram
    Chatbot {
        uuid id
        string name
        string website
        string status
        text prompt_message
        string token
    }

    WebsiteDataSource {
        uuid id 
        chatbot_id uuid FK to Chatbot
        json html_files
        url root_url 
        image icon
        datetime vector_databased_last_ingested_at
        string crawling_status
        float crawling_progress
    }

    CrawledPages {
        uuid id
        uuid chatbot_id FK to Chatbot
        website_data_source_id uuid FK to WebsiteDataSource
        url url
        string title
        string status_code
        string content_file
    }

    Chatbot ||--o{ WebsiteDataSource : has
    WebsiteDataSource ||--o{ CrawledPages : has
    Chatbot }|--|| CrawledPages : has