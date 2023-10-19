


<p>
<img alt="GitHub Contributors" src="https://img.shields.io/github/contributors/openchatai/openchat" />
<img alt="GitHub Last Commit" src="https://img.shields.io/github/last-commit/openchatai/openchat" />
<img alt="" src="https://img.shields.io/github/repo-size/openchatai/openchat" />
<img alt="GitHub Issues" src="https://img.shields.io/github/issues/openchatai/openchat" />
<img alt="GitHub Pull Requests" src="https://img.shields.io/github/issues-pr/openchatai/openchat" />
<img alt="Github License" src="https://img.shields.io/badge/License-MIT-yellow.svg" />
<img alt="Discord" src="https://img.shields.io/discord/1110910277110743103?label=Discord&logo=discord&logoColor=white&style=plastic&color=d7b023)](https://discord.gg/Q8hHfdav" />
</p>

![](https://gcdnb.pbrd.co/images/gjX4atjx9uKT.png?o=1)

------
# 🔥 OpenChat

---- 
OpenChat is an everyday user chatbot console that simplifies the utilization of large language models. With the advancements in AI, the installation and usage of these models have become overwhelming. OpenChat aims to address this challenge by providing a two-step setup process to create a comprehensive chatbot console. It serves as a central hub for managing multiple customized chatbots.

> Currently, OpenChat supports GPT models, and we are actively working on incorporating various open-source drivers that can be activated with a single click.




## Try it out:
**You can try it out on [openchat.so](http://openchat.so/)**

https://github.com/openchatai/OpenChat/assets/32633162/112a72a7-4314-474b-b7b5-91228558370c

Chinese Video Tutorial:https://www.bilibili.com/video/BV1YX4y1H7oN

## 🏁 Current Features

- Create unlimited local chatbots based on GPT-3 (and GPT-4 if available).
- Customize your chatbots by providing PDF files, websites, and soon, integrations with platforms like Notion, Confluence, and Office 365.
- Each chatbot has unlimited memory capacity, enabling seamless interaction with large files such as a 400-page PDF.
- Embed chatbots as widgets on your website or internal company tools.
- Use your entire codebase as a data source for your chatbots (pair programming mode).
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
- [x] Support ingesting an entire codebase using GitHub API and use it as a data source with pair programming mode
- [x] Support pre-defined messages with a single click
- [X] Support offline vector DB
- [X] Re write the backend in Python Django
- [ ] **In progress: re-write the frontend in Next.js & TS**
- [ ] Support Slack integration (allow users to connect chatbots with their Slack workspaces)
- [ ] Support Intercom integration (enable users to sync chat conversations with Intercom)
- [ ] Support offline open-source models (e.g., Alpaca, LLM drivers)
- [ ] Support Vertex AI and Palm as LLMs
- [ ] Support Confluence, Notion, Office 365, and Google Workspace
- [ ] Refactor the codebase to be API ready
- [ ] Create a new UI designer for website-embedded chatbots
- [ ] Support custom input fields for chatbots
- [ ] Support offline usage: this is a major feature, OpenChat will operate fully offline with no internet connection at this stage (offline LLMs, offline Vector DBs)

We love hearing from you! Got any cool ideas or requests? We're all ears! So, if you have something in mind, give us a shout! 


## 🚀 Getting Started

- Make sure you have docker installed. 

- To begin, clone this Git repository:

```bash
git clone git@github.com:openchatai/OpenChat.git
```

---
### Setting Up Your Environment

**Note**: Starting July, Qdrant is our Preferred Open-Source Vector Store 🚀 No initial Pinecone registration required. To begin, delve into the comprehensive guide: [**Using Qdrant**](#using-qdrant), provided in the following section.

#### Before you begin, make sure to update the `common.env` file with the necessary keys:

```sh
OPENAI_API_KEY=# Retrieve from your [openai.com](https://www.openai.com) account
PINECONE_API_KEY=# Obtain from the "API Keys" tab in [pinecone](https://www.pinecone.io)
PINECONE_ENVIRONMENT=# Obtain after creating your index in [pinecone](https://www.pinecone.io)
VECTOR_STORE_INDEX_NAME=# Obtain after creating your index in [pinecone](https://www.pinecone.io)
STORE=pinecone
```


####  Using Azure OpenAI

- `USE_AZURE_OPENAI=true`: Whether to use the Azure OpenAI API.
- `AZURE_OPENAI_API_KEY`: Your Azure OpenAI API key.
- `AZURE_OPENAI_API_INSTANCE_NAME`: The name of your Azure OpenAI API instance.
- `AZURE_OPENAI_API_COMPLETIONS_DEPLOYMENT_NAME`: The name of the Azure OpenAI API deployment for completions.
- `AZURE_OPENAI_API_EMBEDDINGS_DEPLOYMENT_NAME`: The name of the Azure OpenAI API deployment for embeddings.

#### Using Qdrant
If you want to switch from Pinecone to Qdrant, you can set the following environment variables:
- `OPENAI_API_KEY`= Your open ai key
- `QDRANT_URL`: The URL of the Qdrant server.
- `STORE`: The store to use to store embeddings. Can be `qdrant` or `pinecone`.


#### Optional [To modify the chat behaviour]

`CHAIN_TYPE` = The type of chain to use: `conversation_retrieval` | `retrieval_qa`

- `retrieval_qa` -> [Learn more](https://python.langchain.com/docs/use_cases/question_answering/how_to/vector_db_qa)
- `conversation_retrieval` -> [Learn more](https://python.langchain.com/docs/use_cases/question_answering/how_to/chat_vector_db)

#### Using Prebuilt Images

If you're experiencing slow internet speeds or if Docker builds are taking a long time, consider using the prebuilt images for your respective architecture. Simply comment out the unnecessary image line in the `docker-compose.yml` file and uncomment the appropriate prebuilt image line.

Example:

```yaml
# Mac environment
image: codebanesr/openchat_llm_server:edge_amd64

# Or, for Linux environment
image: codebanesr/openchat_llm_server:edge
```


> Note: for pincone db, make sure that the dimension is equal to 1536 

- Navigate to the repository folder and run the following command (for MacOS or Linux):
```
make install
```


**or in case you are using Windows**
```
make.bat
```

Sure, here's the modified text with the additional line you requested:
# Getting Started with the Openchat Django App

Start your adventure of contributing to and using OpenChat, now remade using the Python programming language. You can begin by following the instructions in the guide available here: [OpenChat Python Guide](docs/django_release.md).

**Kindly be aware that the transition to the Python backend includes a significant alteration related to the Qdrant vector store, constituting a breaking change.**

Once the installation is complete, you can access the OpenChat console at: http://localhost:8000

## 🚀 Unleash the Power of Native LLM
Discover the latest addition: llama2 support. [Dive into this Guide to Harness LLAMA2 by Meta](docs/aug_26/readme.md) 📖🔮
***
### Full documentation [available here](https://docs.openchat.so/introduction)

## 🚀 Upgrade guide:

We do our best to not introduce breaking changes, so far, you only need to git pull and run `make install` whenever there is a new update.

## ❤️ Thanks:
- To [@mayooear](https://github.com/mayooear) for his work and tutorial on chatting with PDF files, we utilized a lot of his code in the LLM server.


## License
This project is licensed under the MIT License.



## Contributors ✨

Thanks goes to these wonderful people ([emoji key](https://allcontributors.org/docs/en/emoji-key)):

<!-- ALL-CONTRIBUTORS-LIST:START - Do not remove or modify this section -->
<!-- prettier-ignore-start -->
<!-- markdownlint-disable -->
<table>
  <tbody>
    <tr>
      <td align="center" valign="top" width="14.28%"><a href="https://github.com/eltociear"><img src="https://avatars.githubusercontent.com/u/22633385?v=4?s=100" width="100px;" alt="Ikko Eltociear Ashimine"/><br /><sub><b>Ikko Eltociear Ashimine</b></sub></a><br /><a href="#ideas-eltociear" title="Ideas, Planning, & Feedback">🤔</a> <a href="https://github.com/openchatai/OpenChat/commits?author=eltociear" title="Code">💻</a></td>
      <td align="center" valign="top" width="14.28%"><a href="https://github.com/jsindy"><img src="https://avatars.githubusercontent.com/u/4966007?v=4?s=100" width="100px;" alt="Joshua Sindy"/><br /><sub><b>Joshua Sindy</b></sub></a><br /><a href="https://github.com/openchatai/OpenChat/issues?q=author%3Ajsindy" title="Bug reports">🐛</a></td>
      <td align="center" valign="top" width="14.28%"><a href="https://github.com/erjanmx"><img src="https://avatars.githubusercontent.com/u/4899432?v=4?s=100" width="100px;" alt="Erjan Kalybek"/><br /><sub><b>Erjan Kalybek</b></sub></a><br /><a href="https://github.com/openchatai/OpenChat/commits?author=erjanmx" title="Documentation">📖</a></td>
      <td align="center" valign="top" width="14.28%"><a href="https://woahai.com/"><img src="https://avatars.githubusercontent.com/u/115117306?v=4?s=100" width="100px;" alt="WoahAI"/><br /><sub><b>WoahAI</b></sub></a><br /><a href="https://github.com/openchatai/OpenChat/issues?q=author%3AWoahai321" title="Bug reports">🐛</a> <a href="https://github.com/openchatai/OpenChat/commits?author=Woahai321" title="Code">💻</a></td>
      <td align="center" valign="top" width="14.28%"><a href="https://space.bilibili.com/1900783"><img src="https://avatars.githubusercontent.com/u/36354458?v=4?s=100" width="100px;" alt="Tommy in Tongji"/><br /><sub><b>Tommy in Tongji</b></sub></a><br /><a href="https://github.com/openchatai/OpenChat/commits?author=TommyZihao" title="Documentation">📖</a></td>
      <td align="center" valign="top" width="14.28%"><a href="https://resume.applesauce.co.in"><img src="https://avatars.githubusercontent.com/u/17947802?v=4?s=100" width="100px;" alt="codebane"/><br /><sub><b>codebane</b></sub></a><br /><a href="https://github.com/openchatai/OpenChat/commits?author=codebanesr" title="Code">💻</a> <a href="https://github.com/openchatai/OpenChat/commits?author=codebanesr" title="Documentation">📖</a></td>
      <td align="center" valign="top" width="14.28%"><a href="https://gloobus.it"><img src="https://avatars.githubusercontent.com/u/27447535?v=4?s=100" width="100px;" alt="lvalics"/><br /><sub><b>lvalics</b></sub></a><br /><a href="https://github.com/openchatai/OpenChat/commits?author=lvalics" title="Code">💻</a> <a href="https://github.com/openchatai/OpenChat/commits?author=lvalics" title="Documentation">📖</a></td>
    </tr>
  </tbody>
</table>

<!-- markdownlint-restore -->
<!-- prettier-ignore-end -->

<!-- ALL-CONTRIBUTORS-LIST:END -->

This project follows the [all-contributors](https://github.com/all-contributors/all-contributors) specification. Contributions of any kind welcome!
