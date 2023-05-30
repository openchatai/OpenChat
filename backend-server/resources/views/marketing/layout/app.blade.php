<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>OpenChat - Build your own ChatGPT</title>
    <meta name="description" content="OpenChat - Build your own ChatGPT for yor website, PDF files, Notion and many more integrations for free, no coding required!">
    <meta name="author" content="OpenChat">


    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="/marketing/css/vendors/aos.css" rel="stylesheet">
    <link href="/marketing/style.css" rel="stylesheet">
</head>

<body class="font-inter antialiased bg-white text-gray-900 tracking-tight">

<!-- Page wrapper -->
<div class="flex flex-col min-h-screen overflow-hidden supports-[overflow:clip]:overflow-clip">

    <!-- Site header -->
    <header class="fixed w-full z-30 md:bg-opacity-90 transition duration-300 ease-in-out" x-data="handleHeader" @scroll.window="isTop" :class="{ 'bg-white backdrop-blur-sm shadow-lg' : !top }">
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('handleHeader', () => ({
                    top: true,
                    isTop() {
                        this.top = window.pageYOffset < 10
                    },
                    init() {
                        this.isTop()
                    }
                }))
            })
        </script>
        <div class="max-w-6xl mx-auto px-5 sm:px-6">
            <div class="flex items-center justify-between h-16 md:h-20">

                <!-- Site branding -->
                <div class="shrink-0 mr-4">
                    <!-- Logo -->
                    <a class="block" href="{{route('marketing')}}" aria-label="OpenChat" style="display: flex;justify-content: center; align-items: center">
                        <svg class="w-8 h-8" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <radialGradient cx="21.152%" cy="86.063%" fx="21.152%" fy="86.063%" r="79.941%" id="header-logo">
                                    <stop stop-color="#4FD1C5" offset="0%" />
                                    <stop stop-color="#81E6D9" offset="25.871%" />
                                    <stop stop-color="#338CF5" offset="100%" />
                                </radialGradient>
                            </defs>
                            <rect width="32" height="32" rx="16" fill="url(#header-logo)" fill-rule="nonzero" />
                        </svg>
                        <strong style="margin-left: .75rem;">
                            OpenChat
                        </strong>
                    </a>

                </div>

                <!-- Desktop navigation -->
                <nav class="hidden md:flex md:grow">

                    <!-- Desktop menu links -->
                    <ul class="flex grow justify-end flex-wrap items-center">

                    </ul>

                    <!-- Desktop sign in links -->
                    <ul class="flex grow justify-end flex-wrap items-center">
                        <li>
                            <a class="font-medium text-gray-600 hover:text-gray-900 px-5 py-3 flex items-center transition duration-150 ease-in-out" href="{{route('marketing.login')}}">Sign in</a>
                        </li>
                        <li>
                            <a class="btn-sm text-gray-200 bg-gray-900 hover:bg-gray-800 ml-3" href="{{route('marketing.register')}}">
                                <span>Start for free</span>
                                <svg class="w-3 h-3 fill-current text-gray-400 shrink-0 ml-2 -mr-1" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.707 5.293L7 .586 5.586 2l3 3H0v2h8.586l-3 3L7 11.414l4.707-4.707a1 1 0 000-1.414z" fill-rule="nonzero" />
                                </svg>
                            </a>
                        </li>
                    </ul>

                </nav>


            </div>
        </div>
    </header>

    <!-- Page content -->

    @yield('content')

    <!-- Site footer -->
    <footer>
        <div class="max-w-6xl mx-auto px-4 sm:px-6">

            <!-- Top area: Blocks -->

            <!-- Bottom area -->
            <div class="md:flex md:items-center md:justify-between py-4 md:py-8 border-t border-gray-200">


                <!-- Copyrights note -->
                <div class="text-sm text-gray-600 mr-4">&copy; Openchat.so - Amsterdam /  All rights reserved.</div>

            </div>

        </div>
    </footer>

</div>

<script src="/marketing/js/vendors/alpinejs.min.js" defer></script>
<script src="/marketing/js/vendors/aos.js"></script>
<script src="/marketing/js/main.js"></script>
<script src="/chat.js"></script>
@if(!request()->routeIs('chat'))
    <script>
        var chatConfig = {
            token: "eQq6ssQp5TbFyB9xkg4I",
        };
        initializeChatWidget(chatConfig);
    </script>
@endif
@yield('scripts')


</body>

</html>
