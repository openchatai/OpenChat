


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

**Important disclaimer:**
> :warning: **This project is not production ready, meant for local environment at this early stage, We quickly built this project to validate the idea, so please excuse any shortcomings in the code. You may come across several areas that require enhancements, and we truly appreciate your support by opening issues, submitting pull requests, and providing suggestions.**

---- 
OpenChat is an everyday user chatbot console that simplifies the utilization of large language models. With the advancements in AI, the installation and usage of these models have become overwhelming. OpenChat aims to address this challenge by providing a two-step setup process to create a comprehensive chatbot console. It serves as a central hub for managing multiple customized chatbots.

> Currently, OpenChat supports GPT models, and we are actively working on incorporating various open-source drivers that can be activated with a single click.




## Try it out:
**You can try it out on [openchat.so](http://openchat.so/)**

https://github.com/openchatai/OpenChat/assets/32633162/112a72a7-4314-474b-b7b5-91228558370c


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

- Make sure you have docker installed. 

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

Once the installation is complete, you can access the OpenChat console at: http://localhost:8000

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
    </tr>
  </tbody>
</table>

<!-- markdownlint-restore -->
<!-- prettier-ignore-end -->

<!-- ALL-CONTRIBUTORS-LIST:END -->

This project follows the [all-contributors](https://github.com/all-contributors/all-contributors) specification. Contributions of any kind welcome!
