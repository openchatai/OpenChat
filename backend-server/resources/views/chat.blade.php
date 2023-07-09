<script src="/chat.js?v6" type="text/javascript" defer></script>
<title>{{$bot->getName()}}</title>
<script>
    window.onload = function () {
        initializeChatWidget({
            token: "{{$bot->getToken()}}",
            isFullScreen: true
        })
    }
</script>
