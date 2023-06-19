@extends('layout.app', ['title' => __('Dashboard'), 'doNotShowTopHeader' => true])
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="relative flex">


        <!-- Messages body -->
        <div class="grow flex flex-col md:translate-x-0 transition-transform duration-300 ease-in-out" style="height: 100vh">

            <!-- Header -->
            <div class="">
                <div
                    class="flex items-center justify-between bg-white border-b border-slate-200 px-4 sm:px-6 md:px-5 h-16">
                    <!-- People -->
                    <div class="flex items-center">
                        <!-- People list -->
                        <div class="flex -space-x-3 -ml-px">
                            <svg class="w-8 h-8" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <radialGradient cx="21.152%" cy="86.063%" fx="21.152%" fy="86.063%" r="79.941%"
                                                    id="header-logo">
                                        <stop stop-color="#4FD1C5" offset="0%"></stop>
                                        <stop stop-color="#81E6D9" offset="25.871%"></stop>
                                        <stop stop-color="#338CF5" offset="100%"></stop>
                                    </radialGradient>
                                </defs>
                                <rect width="32" height="32" rx="16" fill="url(#header-logo)"
                                      fill-rule="nonzero"></rect>
                            </svg>

                            <span class="ml-2" style="margin-left: 8px; font-weight: bold">{{$bot->getName()}}</span>
                        </div>
                    </div>
                    <!-- Buttons on the right side -->

                    @php
                        /** @var \App\Models\Chatbot $bot */
                    @endphp
                    @if($bot->getCodebaseDataSources()->count())
                        <div class="flex">
                            <div>
                                <!-- Start -->
                                <div x-data="{ modalOpen: false }">
                                    <button class="btn bg-slate-100 hover:bg-slate-200 "
                                            @click.prevent="modalOpen = true"
                                            aria-controls="announcement-modal">
                                        <svg class="w-4 h-4 fill-current text-slate-400" viewBox="0 0 16 16">
                                            <path
                                                d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm0 12c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1zm1-3H7V4h2v5z"/>
                                        </svg>
                                        <span style="margin-left: 0.5rem" id="current-mode">
                                        AI Assistant
                                    </span>
                                    </button>
                                    <!-- Modal backdrop -->
                                    <div class="fixed inset-0 bg-slate-900 bg-opacity-30 z-50 transition-opacity"
                                         x-show="modalOpen" x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                         x-transition:leave="transition ease-out duration-100"
                                         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                         aria-hidden="true" style="display: none;"></div>
                                    <!-- Modal dialog -->
                                    <div id="announcement-modal"
                                         class="fixed inset-0 z-50 overflow-hidden flex items-center my-4 justify-center px-4 sm:px-6"
                                         role="dialog" aria-modal="true" x-show="modalOpen"
                                         x-transition:enter="transition ease-in-out duration-200"
                                         x-transition:enter-start="opacity-0 translate-y-4"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in-out duration-200"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 translate-y-4" style="display: none;">
                                        <div class="bg-white rounded shadow-lg overflow-auto max-w-lg w-full max-h-full"
                                             @click.outside="modalOpen = false"
                                             @keydown.escape.window="modalOpen = false">
                                            <div class="p-6">
                                                <div class="relative">
                                                    <!-- Close button -->
                                                    <button
                                                        class="absolute top-0 right-0 text-slate-400 hover:text-slate-500"
                                                        @click="modalOpen = false">
                                                        <div class="sr-only">Close</div>
                                                        <svg class="w-4 h-4 fill-current">
                                                            <path
                                                                d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z"></path>
                                                        </svg>
                                                    </button>
                                                    <!-- Modal header -->
                                                    <div class="mb-2 text-center">
                                                        <!-- Icon -->
                                                        <div class="inline-flex mb-2">
                                                            <img src="/dashboard/images/announcement-icon.svg"
                                                                 width="80"
                                                                 height="80" alt="Announcement">
                                                        </div>
                                                        <div class="text-lg font-semibold text-slate-800">Conversation
                                                            mode
                                                        </div>
                                                    </div>
                                                    <!-- Modal content -->
                                                    <div class="text-center">
                                                        <div class="text-sm mb-5">
                                                            Choose the mode you want to use. You can switch between
                                                            modes at
                                                            any time.
                                                            <div class="flex items-center justify-between space-x-6"
                                                                 style="margin-top: 1rem; margin-bottom: 1rem">
                                                                <div
                                                                    class="sm:flex space-y-3 sm:space-y-0 sm:space-x-4 ">
                                                                    <label class="flex-1 relative block cursor-pointer">
                                                                        <input type="radio" class="peer sr-only" id=""
                                                                               name="mode" value="assistant"
                                                                               checked="">
                                                                        <div
                                                                            class="h-full text-center bg-white px-4 py-6 rounded border border-slate-200 hover:border-slate-300 shadow-sm duration-150 ease-in-out">
                                                                            <div class="emoji" style="font-size: 50px">
                                                                                🦉
                                                                            </div>
                                                                            <div
                                                                                class="font-medium text-slate-800 mb-1">
                                                                                AI
                                                                                Assistant
                                                                            </div>
                                                                            <div class="text-sm">
                                                                                Answer all sort of questions from the
                                                                                given
                                                                                datasources
                                                                            </div>
                                                                        </div>
                                                                        <div
                                                                            class="absolute inset-0 border-2 border-transparent peer-checked:border-indigo-400 rounded pointer-events-none"
                                                                            aria-hidden="true"></div>
                                                                    </label>
                                                                    <label class="flex-1 relative block cursor-pointer">
                                                                        <input type="radio" id="" name="mode"
                                                                               value="pair_programmer"
                                                                               class="peer sr-only">
                                                                        <div
                                                                            class="h-full text-center bg-white px-4 py-6 rounded border border-slate-200 hover:border-slate-300 shadow-sm duration-150 ease-in-out">
                                                                            <div class="emoji" style="font-size: 50px">
                                                                                🙆‍
                                                                            </div>
                                                                            <div
                                                                                class="font-medium text-slate-800 mb-1">
                                                                                Pair Programmer
                                                                            </div>
                                                                            <div class="text-sm">
                                                                                Answer your coding questions from the
                                                                                given
                                                                                code base datasource
                                                                            </div>
                                                                        </div>
                                                                        <div
                                                                            class="absolute inset-0 border-2 border-transparent peer-checked:border-indigo-400 rounded pointer-events-none"
                                                                            aria-hidden="true"></div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End -->
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Body -->
            <div class="grow px-4 sm:px-6 md:px-5 py-6" id="chat-panel">
                <div class="flex items-start mb-4 last:mb-0">
                    <div>
                        <div
                            class="text-sm bg-indigo-500 text-white p-3 rounded-lg rounded-tl-none border border-transparent shadow-md mb-1">
                            Hello, I'm {{$bot->getName()}}, and I'm here to help! What can I do for you today?
                        </div>
                        <div class="flex items-center justify-between">

                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="sticky bottom-0">
                <div
                    class="flex items-center justify-between bg-white border-t border-slate-200 px-4 sm:px-6 md:px-5 h-16">
                    <!-- Plus button -->
                    <!-- Message input -->
                    <form class="grow flex">
                        <div class="grow mr-3">
                            <label for="message-input" class="sr-only">Type a message</label>
                            <input id="message-input"
                                   class="form-input w-full bg-slate-100 border-transparent focus:bg-white focus:border-slate-300"
                                   type="text" placeholder="Write your question here..."  autocomplete="off"/>
                        </div>
                        <button
                            class="btn bg-indigo-500 hover:bg-indigo-600 text-white whitespace-nowrap"
                            onclick="sendMessage()">Send -&gt;
                        </button>
                    </form>
                </div>
                <div style="text-align: center; background: white;font-size: 12px">
                    Powered by <strong><a href="https://openchat.so" target="_blank" class="text-indigo-500 hover:text-indigo-600">OpenChat</a></strong>

                </div>
            </div>

        </div>

    </div>
@endsection

@section('scripts')
    <script>

        var currentMode = 'assistant';

        // Get the radio buttons
        const radioButtons = document.querySelectorAll('input[type="radio"]');

        // Get the current mode element
        const currentModeElement = document.getElementById('current-mode');

        // Add event listener to each radio button
        radioButtons.forEach((radioButton) => {
            radioButton.addEventListener('change', (event) => {
                // Update the current mode element
                currentModeElement.textContent = event.target.nextElementSibling.querySelector('.font-medium').textContent;
                currentMode = event.target.value;
            });
        });


        let messageHistory = [];

        function sendMessage() {
            // prevent form
            event.preventDefault();
            let chatPanel = document.getElementById('chat-panel');
            let messageInput = document.getElementById('message-input');
            let message = messageInput.value;
            if (message === '') return;

            // Add the message to the history (for context)
            messageHistory.push(message, chatPanel, messageInput);

            // Remove empty {} from the history
            messageHistory = messageHistory.filter(function (el) {
                return el != null;
            });

            // Push the message to the chat panel (before sending it to the backend)
            pushMessageFromUser(message, chatPanel, messageInput);

            // Prepare the csrf token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Disable the input
            messageInput.disabled = true;

            // Add loading icon to the chat panel
            addLoadingIcon(chatPanel);


            // Call the backend to send the message
            fetch("{{route('sendMessage', ['token' => $bot->getToken()])}}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({message: message, history: messageHistory, mode: currentMode}),
                timeout: 5000
            }).then(async response => {
                // if the response is ok, replace the content of the on-the-way div with a check mark
                if (response.ok) {
                    // Update the on the way icon to be green instead of the gray loading icon
                    manageOnTheWayIcon();

                    // Remove the loading icon
                    removeLoadingIcon(chatPanel);

                    // Enable the input
                    messageInput.disabled = false;

                    // Put the focus back on the input
                    messageInput.focus();

                    var botResponse = await response.json().then(data => {
                        return data;
                    });
                    var botMessage = botResponse.botReply
                    var botSources = botResponse.sources

                    console.log(botSources)
                    pushMessageFromBot(botMessage, chatPanel, messageInput, botSources);
                }

            }).catch(error => {
                pushMessageFromBot("Something went wrong with the bot.", chatPanel, messageInput);
                console.error(error);
            });


        }

        function manageOnTheWayIcon() {
            document.getElementById('on-the-way').innerHTML = `
                            <svg class="w-5 h-3 shrink-0 fill-current text-emerald-500" viewBox="0 0 20 12">
                                    <path d="M10.402 6.988l1.586 1.586L18.28 2.28a1 1 0 011.414 1.414l-7 7a1 1 0 01-1.414 0L8.988 8.402l-2.293 2.293a1 1 0 01-1.414 0l-3-3A1 1 0 013.695 6.28l2.293 2.293L12.28 2.28a1 1 0 011.414 1.414l-3.293 3.293z"></path>
                            </svg>
                        `

            // now remove the id from the on-the-way div
            document.getElementById('on-the-way').removeAttribute('id');
        }

        function addLoadingIcon(chatPanel) {
            let loadingIcon = `
               <div class="flex items-start mb-4 last:mb-0">
<!--                    <img class="rounded-full mr-4" src="/dashboard/images/user-40-11.jpg" width="40" height="40"-->
<!--                         alt="User 01"/>-->
                    <div>
                        <div
                            class="text-sm bg-indigo-500 text-white p-3 rounded-lg  border border-slate-200 shadow-md mb-1">
                            <svg class="fill-current text-slate-400" viewBox="0 0 15 3" width="15" height="3">
                                <circle cx="1.5" cy="1.5" r="1.5">
                                    <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite"
                                             begin="0.1"/>
                                </circle>
                                <circle cx="7.5" cy="1.5" r="1.5">
                                    <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite"
                                             begin="0.2"/>
                                </circle>
                                <circle cx="13.5" cy="1.5" r="1.5">
                                    <animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite"
                                             begin="0.3"/>
                                </circle>
                            </svg>
                        </div>
                    </div>
                </div>`

            chatPanel.insertAdjacentHTML('beforeend', loadingIcon);
        }

        function removeLoadingIcon(chatPanel) {
            chatPanel.removeChild(chatPanel.lastChild);
        }

        function pushMessageFromUser(message, chatPanel, messageInput) {
            let messageDiv = `
            <div class="flex items-start mb-4 last:mb-0" style="justify-content: end">
                <div>
                    <div
                        class="text-sm bg-white text-slate-800 p-3 rounded-lg border border-slate-200 shadow-md mb-1">
                        ${message}
                    </div>
                    <div id="on-the-way">
                       <div class="flex items-center justify-between" >
                            <svg class="w-3 h-3 shrink-0 fill-current text-slate-400" viewBox="0 0 12 12">
                                <path d="M10.28 1.28L3.989 7.575 1.695 5.28A1 1 0 00.28 6.695l3 3a1 1 0 001.414 0l7-7A1 1 0 0010.28 1.28z"></path>
                            </svg>
                        </div>
                    </div>

                </div>
<!--                <img class="rounded-full ml-4" src="/dashboard/images/user-40-11.jpg" width="40" height="40"-->
<!--                     alt="User 01"/>-->
            </div>
            `

            // append message to chat panel to be exactly the last child
            chatPanel.insertAdjacentHTML('beforeend', messageDiv);

            // clear message input
            messageInput.value = '';

            const scrollingElement = document.body;
            scrollingElement.scrollTop = scrollingElement.scrollHeight;
        }

        function pushMessageFromBot(message, chatPanel, messageInput, sources = []) {

            let sourcesHTML = '';
            if(sources.length > 0) {
                sourcesHTML = ``;
                sources.slice(0, 2).forEach((source, index) => {

                    sourcesHTML += `<button class="btn-sm bg-white border-slate-200 hover:border-slate-300 text-slate-500">
                                        <div class="w-1 h-3.5 bg-emerald-500 shrink-0"></div>
                                        <span class="ml-1.5">${source.metadata.source} ${source.metadata['loc.lines.from']}:${source.metadata['loc.lines.to']}</span>
                                    </button>`
                });
            }

            let messageDiv = `<div class="flex items-start mb-4 last:mb-0" style="opacity: 1;">
        <div>
            <div
                class="text-sm bg-indigo-500 text-white p-3 rounded-lg rounded-tl-none border border-transparent shadow-md mb-1">
                ${message}
            </div>
            <div class="flex items-center">
                ${sourcesHTML}
            </div>
        </div>
    </div>`

            // Append the hidden message to the chat panel
            chatPanel.insertAdjacentHTML('beforeend', messageDiv);

            // Scroll to the last message
            const scrollingElement = document.body;
            scrollingElement.scrollTop = scrollingElement.scrollHeight;
        }
    </script>
@endsection
