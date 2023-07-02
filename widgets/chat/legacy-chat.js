function toggleChat() {
    var e = document.getElementById("chat-box");
    e.classList.contains("open") ? (e.classList.remove("open"), e.classList.add("closed")) : (e.classList.remove("closed"), e.classList.add("open"))
}

function initializeChatWidget(e) {
    setTimeout(function () {
        var t = document.createElement("div");
        t.id = "chat-container";
        var a = document.createElement("div");
        a.id = "chat-bubble", a.onclick = toggleChat, a.innerHTML = '<svg id="ic_bubble" fill="#FFFFFF" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>';
        var i = document.createElement("div");
        i.id = "chat-box", i.classList.add("closed");
        var d = "http://localhost:8000/chat/" + e.token;
        i.innerHTML = '<iframe src="' + d + '" style="height: 100%; width: 100%;" frameborder="0"></iframe>', t.appendChild(a), t.appendChild(i), document.body.appendChild(t)
    }, 0)
}

function loadCSS() {
    var e = document.createElement("link");
    e.rel = "stylesheet", e.href = "http://localhost:8000/chat.css", document.head.appendChild(e)
}

loadCSS();

