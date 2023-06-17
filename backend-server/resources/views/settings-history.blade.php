@extends('layout.app', ['title' => __('Dashboard')])
@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="mb-8"
             style="display: flex;flex-direction: row; justify-content: space-between; align-items: center">

            <!-- Title -->
            <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">{{$bot->getName()}}: History ✨</h1>

        </div>

        <div class="bg-white shadow-lg rounded-sm mb-8">
            <div class="flex flex-col md:flex-row md:-mr-px">

                <!-- Sidebar -->
                @include('layout.sidebar-bot-page')

                <!-- Panel -->
                <div class="grow">

                    <!-- Panel body -->
                    <div class="p-6 space-y-6">

                        <!-- Business Profile -->
                        <section>
                            <h3 class="text-xl leading-snug text-slate-800 font-bold mb-1">History</h3>
                            <div class="text-sm">Browse your chatbots history</div>
                        </section>


                        <div class="relative flex">

                            <!-- Messages sidebar -->
                            <div id="messages-sidebar"
                                 class="absolute z-20 top-0 bottom-0 w-full md:w-auto md:static md:top-auto md:bottom-auto -mr-px md:translate-x-0 transition-transform duration-200 ease-in-out -translate-x-full"
                            '">
                            <div
                                class="sticky top-16 bg-white overflow-x-hidden overflow-y-auto no-scrollbar shrink-0 border-r border-slate-200 md:w-72 xl:w-80 h-[calc(100vh-64px)]">

                                <!-- #Marketing group -->
                                <div>

                                    <!-- Group body -->
                                    <div class="px-5 py-4">
                                        <!-- Direct messages -->
                                        <div class="mt-4">
                                            <div class="text-xs font-semibold text-slate-400 uppercase mb-3">
                                                Conversations
                                            </div>
                                            <ul class="mb-6">
                                                @foreach($chatHistory as $history)
                                                    <li class="-mx-2">
                                                        <button
                                                            class="flex items-center justify-between w-full p-2 rounded chat-history-button"
                                                            onclick="fetchChatHistory('{{ $history->session_id }}')"
                                                            id="{{ $history->session_id }}"
                                                        >
                                                            <div class="flex items-center truncate">
                                                                <img class="w-8 h-8 rounded-full mr-2"
                                                                     src="/dashboard/images/user-32-01.jpg" width="32"
                                                                     height="32" alt="User 01">
                                                                <div class="truncate">
                                                                    <span
                                                                        class="text-sm font-medium text-slate-800">
                                                                        {{$history->first_message}}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="flex items-center ml-2">
                                                                <div
                                                                    class="text-xs inline-flex font-medium bg-indigo-400 text-white rounded-full text-center leading-5 px-2">{{$history->total_messages}}</div>
                                                            </div>
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Messages body -->
                        <div
                            class="grow flex flex-col md:translate-x-0 transition-transform duration-300 ease-in-out translate-x-0" style="overflow:scroll;max-height: 70vh"
                            id="chat-content">

                            <div class="border-t border-slate-200">
                                <div class="max-w-2xl m-auto mt-16">

                                    <div class="text-center px-4">
                                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-t from-slate-200 to-slate-100 mb-4">
                                            <svg class="w-5 h-6 fill-current" viewBox="0 0 20 24">
                                                <path class="text-slate-500" d="M10 10.562l9-5-8.514-4.73a1 1 0 00-.972 0L1 5.562l9 5z"></path>
                                                <path class="text-slate-300" d="M9 12.294l-9-5v10.412a1 1 0 00.514.874L9 23.294v-11z"></path>
                                                <path class="text-slate-400" d="M11 12.294v11l8.486-4.714a1 1 0 00.514-.874V7.295l-9 4.999z"></path>
                                            </svg>
                                        </div>
                                        <h2 class="text-2xl text-slate-800 font-bold mb-2">Select a conversation to get started</h2>
                                        <div class="mb-6">Select a conversation from the left to view the chat history
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>

@endsection


@section('scripts')
    <script>
        function fetchChatHistory(sessionId) {
            const buttons = document.querySelectorAll('.chat-history-button');
            buttons.forEach(button => {
                button.classList.remove('bg-indigo-100');
            });

            // Add the class to the clicked button
            const clickedButton = document.getElementById(sessionId);
            clickedButton.classList.add('bg-indigo-100');

            // Make an AJAX request to the /history/session_id endpoint
            fetch(`/widget/chat-history/{{$bot->getId()}}/${sessionId}`)
                .then(response => response.text())
                .then(data => {
                    // Update the content of #chat-content
                    document.getElementById('chat-content').innerHTML = data;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
@endsection
