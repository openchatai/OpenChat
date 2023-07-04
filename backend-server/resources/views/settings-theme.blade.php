@extends('layout.app', ['title' => __('Dashboard')])
@section('content')

    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="mb-8">

            <!-- Title -->
            <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">{{$bot->getName()}}: try & share ✨</h1>
            <ul class="inline-flex flex-wrap text-sm font-medium">
                <li class="flex items-center">
                    <a class="text-slate-500 hover:text-indigo-500" href="{{route('index')}}">Home</a>
                    <svg class="h-4 w-4 fill-current text-slate-400 mx-3" viewBox="0 0 16 16">
                        <path d="M6.6 13.4L5.2 12l4-4-4-4 1.4-1.4L12 8z"></path>
                    </svg>
                </li>
                <li class="flex items-center">
                    <a class="text-slate-500 hover:text-indigo-500" >Try & Share</a>
                </li>

            </ul>
        </div>

        <div class="bg-white shadow-lg rounded-sm mb-8">
            <div class="flex flex-col md:flex-row md:-mr-px">

                <!-- Sidebar -->
                @include('layout.sidebar-bot-page')

                <!-- Panel -->
                <div class="grow">
                    <div class="data-sources-real-time" id="data-sources-updates-container">

                    </div>
                    <div class="p-6 space-y-6 sm:flex" style="display: flex; justify-content: space-between">
                        <div class="inputs">
                            <!-- Form Section -->
                            <section class="sm:w-1/2">
                                <h3 class="text-xl leading-snug text-slate-800 font-bold mb-1">Try & Share!</h3>
                                <div class="text-sm">Here you can try and play with your bot, also you can share it or embed it in your web apps</div>




                                <div style="margin-top: 2rem">
                                    <!-- Start -->
                                    <div>
                                        <div class="flex items-center justify-between">
                                            <label class="block text-sm font-medium mb-1" for="tooltip"> 🔗 Share with others!</label>
                                            <div class="relative ml-2" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                                <button class="block" aria-haspopup="true" :aria-expanded="open" @focus="open = true" @focusout="open = false" @click.prevent="" aria-expanded="false">
                                                    <svg class="w-4 h-4 fill-current text-slate-400" viewBox="0 0 16 16">
                                                        <path
                                                            d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm0 12c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1zm1-3H7V4h2v5z"></path>
                                                    </svg>
                                                </button>
                                                <div class="z-10 absolute bottom-full left-1/2 -translate-x-1/2">
                                                    <div
                                                        class="min-w-60 bg-slate-800 text-slate-200 px-2 py-1 rounded overflow-hidden mb-2"
                                                        x-show="open"
                                                        x-transition:enter="transition ease-out duration-200 transform"
                                                        x-transition:enter-start="opacity-0 translate-y-2"
                                                        x-transition:enter-end="opacity-100 translate-y-0"
                                                        x-transition:leave="transition ease-out duration-200"
                                                        x-transition:leave-start="opacity-100"
                                                        x-transition:leave-end="opacity-0" style="display: none;">
                                                        <div class="text-sm">Anyone with this link will be able to
                                                            interact with your chatbot
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="{{route('chat', ['token' => $bot->getToken()])}}" target="_blank"
                                           style="cursor: pointer!important;">
                                            <input style="cursor: pointer!important;" id="tooltip"
                                                   class="form-input w-full" type="text"
                                                   value="{{route('chat', ['token' => $bot->getToken()])}}" disabled>
                                        </a>
                                    </div>
                                    <!-- End -->
                                </div>
                                <div class="px-5 py-4 rounded-sm border border-slate-200 mt-5" x-data="{ open: false }">
                                    <button class="flex items-center justify-between w-full group mb-1"
                                            @click.prevent="open = !open" :aria-expanded="open" aria-expanded="false">
                                        <div class="text-sm text-slate-800 font-medium">🌎 Embed on your web app as a
                                            chat bubble
                                        </div>
                                        <svg
                                            class="w-8 h-8 shrink-0 fill-current text-slate-400 group-hover:text-slate-500 ml-3"
                                            :class="{ 'rotate-180': open }" viewBox="0 0 32 32">
                                            <path d="M16 20l-5.4-5.4 1.4-1.4 4 4 4-4 1.4 1.4z"></path>
                                        </svg>
                                    </button>

                                    <div class="text-sm" x-show="open" style="display: none;">
                                        <div class="img-container" style=" max-width: 520px">
                                            <img src="/dashboard/images/chat-widget-info.gif" alt="">
                                        </div>
                                        <div>
                                            <!-- Start -->
                                            <div>
                                                <div class="flex items-center justify-between" style="margin-top: 1rem; margin-bottom: 1rem;">
                                                    <strong>1. Copy the following code into your website head script </strong>
                                                </div>
                                                <textarea id="tooltip" class="form-input w-full" style="height: 250px"
                                                          type="text" disabled><script src="{{asset('chat.js')}}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var chatConfig = {
    token: "{{$bot->getToken()}}",
    };
    initializeChatWidget(chatConfig);
});
</script>
                                            </textarea>


                                            </div>
                                            <!-- End -->
                                        </div>
                                    </div>
                                </div>

                                <div class="px-5 py-4 rounded-sm border border-slate-200 mt-5" x-data="{ open: false }">
                                    <button class="flex items-center justify-between w-full group mb-1"
                                            @click.prevent="open = !open" :aria-expanded="open" aria-expanded="false">
                                        <div class="text-sm text-slate-800 font-medium">🔍 Embed on your web app as a
                                            search box
                                        </div>
                                        <svg
                                            class="w-8 h-8 shrink-0 fill-current text-slate-400 group-hover:text-slate-500 ml-3"
                                            :class="{ 'rotate-180': open }" viewBox="0 0 32 32">
                                            <path d="M16 20l-5.4-5.4 1.4-1.4 4 4 4-4 1.4 1.4z"></path>
                                        </svg>
                                    </button>

                                    <div class="text-sm" x-show="open" style="display: none;">
                                        <div class="img-container" style=" max-width: 520px">
                                            <img src="/dashboard/images/search-widget-info.gif" alt="">
                                        </div>
                                        <div>
                                            <!-- Start -->
                                            <div>
                                                <div class="flex items-center justify-between"
                                                     style="margin-top: 1rem; margin-bottom: 1rem;">
                                                    <strong>1. Copy the following code into your website head
                                                        script </strong>
                                                </div>
                                                <textarea id="tooltip" class="form-input w-full" style="height: 250px"
                                                          type="text" disabled><script src="{{asset('search.js')}}"></script>
<script>
    window.onload = () => {
        initilizeChatBot({
            initialFirstMessage: "Hello, Search OpenChat resources here.",
            token: "{{$bot->getToken()}}",
            //initiatorId: "search-openchat" // provide a unique id for the search widget if you want to have custom button
        });
    };
</script>
                                            </textarea>
                                                <div class="flex items-center justify-between"
                                                     style="margin-top: 1rem; margin-bottom: 1rem;">
                                                    <strong>2. Please read this documentation to see all the options for
                                                        the search widget</strong>
                                                </div>
                                            </div>
                                            <!-- End -->
                                        </div>
                                    </div>
                                </div>
                            </section>


                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        // Define a function to fetch and update the content
        function fetchDataSourcesUpdates() {
            // Make a fetch request to the server to fetch the updated content
            fetch('{{ route('widget.data-sources-updates', ['id' => $bot->getId()->toString()]) }}')
                .then(response => response.text())
                .then(data => {
                    // Update the content of the container with the fetched data
                    document.querySelector('#data-sources-updates-container').innerHTML = data;
                })
                .catch(error => {
                    console.error('Error fetching data sources updates:', error);
                });
        }

        // Call the fetchDataSourcesUpdates function initially
        fetchDataSourcesUpdates();

        // Call the fetchDataSourcesUpdates function every 1 second using setInterval
        setInterval(fetchDataSourcesUpdates, 2000);
    </script>

@endsection
