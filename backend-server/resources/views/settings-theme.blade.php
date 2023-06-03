@extends('layout.app', ['title' => __('Dashboard')])
@section('content')
    <style>
        .color-picker {
            display: flex;
            align-items: center;
        }

        .color-option {
            width: 40px;
            height: 40px;
            margin-right: 10px;
            cursor: pointer;
            border-radius: 50%;
        }

        .selected-color {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 2px solid #000;
            margin-left: 10px;
            transition: background-color 0.3s ease;
        }

    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/prismjs/themes/prism.css" />

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

                                <div style="margin-top: 1.5rem">
                                    <!-- Start -->
                                    <div>
                                        <div class="flex items-center justify-between">
                                            <label class="block text-sm font-medium mb-1" for="tooltip">💻 Embed on your
                                                web app</label>
                                            <div class="relative ml-2" x-data="{ open: false }"
                                                 @mouseenter="open = true" @mouseleave="open = false">
                                                <button class="block" aria-haspopup="true" :aria-expanded="open" @focus="open = true" @focusout="open = false" @click.prevent="" aria-expanded="false">
                                                    <svg class="w-4 h-4 fill-current text-slate-400" viewBox="0 0 16 16">
                                                        <path d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm0 12c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1zm1-3H7V4h2v5z"></path>
                                                    </svg>
                                                </button>
                                                <div class="z-10 absolute bottom-full left-1/2 -translate-x-1/2">
                                                    <div class="min-w-60 bg-slate-800 text-slate-200 px-2 py-1 rounded overflow-hidden mb-2" x-show="open" x-transition:enter="transition ease-out duration-200 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-out duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
                                                        <div class="text-sm">Copy and paste this JS snippet to your website `head` tag.
                                                            <br>
                                                            <br>
                                                            <a href="#"> read more here -> </a></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <textarea id="tooltip" class="form-input w-full" style="height: 250px" type="text" disabled ><script src="{{asset('chat.js')}}"></script>
<script>
    var chatConfig = {
    token: "{{$bot->getToken()}}",
    };
    initializeChatWidget(chatConfig);
</script>
                                            </textarea>


                                    </div>
                                    <!-- End -->
                                </div>


                            </section>


                        </div>



                        <!-- iFrame Section -->
                        <section class="sm:w-1/2" style="height: 100%; width: 45%; overflow: auto; text-align: right; border-radius: 20px;box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;">
                            <iframe src="{{route('chat', ['token' => $bot->getToken()])}}" class="w-full h-96" style="height: 60vh;"
                                    frameborder="0"></iframe>
                        </section>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/prismjs/prism.js"></script>

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
        setInterval(fetchDataSourcesUpdates, 1000);
    </script>

@endsection
