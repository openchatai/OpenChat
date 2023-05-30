



[![](https://dcbadge.vercel.app/api/server/Q4DxvXGw?style=flat&compact=True)](https://discord.gg/Q4DxvXGw)
<a href="http://www.repostatus.org/#active"><img src="http://www.repostatus.org/badges/latest/active.svg" /></a>
![Tests](https://github.com/openchatai/OpenChat/actions/workflows/tests.yml/badge.svg)



![](https://gcdnb.pbrd.co/images/gjX4atjx9uKT.png?o=1)

------
# 🔥 OpenChat

**Important disclaimer:** This is an undergoing efforts to create a free & open source chatbot console that allows you to easily create unlimited chatbots using different models for your daily use. Our main goal is to make the interface simple and user-friendly for everyone. If you find this interesting, we would greatly appreciate your support in contributing to this project. We have a highly ambitious plan that we are determined to implement!

---- 
OpenChat is an everyday user chatbot console that simplifies the utilization of large language models. With the advancements in AI, the installation and usage of these models have become overwhelming. OpenChat aims to address this challenge by providing a two-step setup process to create a comprehensive chatbot console. It serves as a central hub for managing multiple customized chatbots.

> Currently, OpenChat supports GPT models, and we are actively working on incorporating various open-source drivers that can be activated with a single click.




## Try it out:
**You can try it out on [openchat.so](http://openchat.so/) (we use our own OpenAI/pinecone token for the demo, please be mindful on the usage, we will clear out bots every 3 hours)**

https://github.com/openchatai/OpenChat/assets/32633162/ba0a6f52-a4ed-4bfd-9f72-387d52402662


## 🏁 Current Features

- Create unlimited local chatbots based on GPT-3 (and GPT-4 if available).
- Customize your chatbots by providing PDF files, websites, and soon, integrations with platforms like Notion, Confluence, and Office 365.
- Each chatbot has unlimited memory capacity, enabling seamless interaction with large files such as a 400-page PDF.
- Embed chatbots as widgets on your website or internal company tools.
- And much more!

## 🛣️ Roadmap:
- [x] Create unlimited chatbots
- [x] Share chatbots via URL
- [x] Integrate chatbots on any website using JS (as a widget on the bottom right corner)
- [x] Support GPT-3 models
- [x] Support vector database to provide chatbots with larger memory
- [x] Accept websites as a data source
- [x] Accept PDF files as a data source
- [x] Support multiple data sources per chatbot
- [ ] Support Slack integration (allow users to connect chatbots with their Slack workspaces)
- [ ] Support Intercom integration (enable users to sync chat conversations with Intercom)
- [ ] Support offline open-source models (e.g., Alpaca, LLM drivers)
- [ ] Support Confluence, Notion, Office 365, and Google Workspace
- [ ] Refactor the codebase to be API ready
- [ ] Create a new UI designer for website-embedded chatbots
- [ ] Support custom input fields for chatbots
- [ ] Support pre-defined messages with a single click

We love hearing from you! Got any cool ideas or requests? We're all ears! So, if you have something in mind, give us a shout! 


## 🚀 Getting Started

- To begin, clone this Git repository:

```bash
git clone git@github.com:openchatai/OpenChat.git
```

- Update common.env with your keys:
```
OPENAI_API_KEY=# you can get it from your account in openai.com
PINECONE_API_KEY=# you can get from "API Keys" tab in pinecone
PINECONE_ENVIRONMENT=# you can get it after creating your index in pinecone
PINECONE_INDEX_NAME=# you can get it after creating your index in pinecone
```

- Navigate to the repository folder and run the following command:
```
make install
```

Once the installation is complete, you can access the OpenChat console at: http://localhost:8000



## ❤️ Thanks:
- To [@mayooear](https://github.com/mayooear) for his work and tutorial on chatting with PDF files, we utilized a lot of his code in the LLM server.



## Disclaimer:
We quickly built this project to validate the idea, so please excuse any shortcomings in the code. You may come across several areas that require enhancements, and we truly appreciate your support by opening issues, submitting pull requests, and providing suggestions.


## License
This project is licensed under the MIT License.
