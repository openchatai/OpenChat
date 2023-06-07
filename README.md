
<!-- ALL-CONTRIBUTORS-BADGE:START - Do not remove or modify this section -->
[![All Contributors](https://img.shields.io/badge/all_contributors-2-orange.svg?style=flat-square)](#contributors-)
<!-- ALL-CONTRIBUTORS-BADGE:END -->



[![](https://dcbadge.vercel.app/api/server/yjEgCgvefr?style=flat&compact=True)](https://discord.gg/yjEgCgvefr)
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

https://github.com/openchatai/OpenChat/assets/32633162/c1e0cea1-6627-47c3-becc-c7ab6f8c5b2d


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
- [ ] Support Slack integration (allow users to connect chatbots with their Slack workspaces)
- [ ] Support Intercom integration (enable users to sync chat conversations with Intercom)
- [ ] Support offline open-source models (e.g., Alpaca, LLM drivers)
- [ ] Support Vertex AI and Palm as LLMs
- [ ] Support Confluence, Notion, Office 365, and Google Workspace
- [ ] Refactor the codebase to be API ready
- [ ] Create a new UI designer for website-embedded chatbots
- [ ] Support custom input fields for chatbots
- [ ] Support pre-defined messages with a single click
- [ ] Support offline usage: this is a major feature, OpenChat will operate fully offline with no internet connection at this stage (offline LLMs, offline Vector DBs)

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

> Note: for pincone db, make sure that the dimension is equal to 1536 

- Navigate to the repository folder and run the following command:
```
make install
```

> :warning: **Windows users might not be able to install OpenChat easily and we are working on fixing that.**


Once the installation is complete, you can access the OpenChat console at: http://localhost:8000



## ❤️ Thanks:
- To [@mayooear](https://github.com/mayooear) for his work and tutorial on chatting with PDF files, we utilized a lot of his code in the LLM server.



## Disclaimer:
We quickly built this project to validate the idea, so please excuse any shortcomings in the code. You may come across several areas that require enhancements, and we truly appreciate your support by opening issues, submitting pull requests, and providing suggestions.


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
    </tr>
  </tbody>
</table>

<!-- markdownlint-restore -->
<!-- prettier-ignore-end -->

<!-- ALL-CONTRIBUTORS-LIST:END -->

This project follows the [all-contributors](https://github.com/all-contributors/all-contributors) specification. Contributions of any kind welcome!
