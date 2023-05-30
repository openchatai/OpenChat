@extends('marketing.layout.app', ['title' => __('Dashboard')])
@section('content')

    <style>
        /* Define the CSS transition for the fade-out effect */
        .fade-out {
            opacity: 0;
            transition: opacity 0.5s ease-out;
        }

        @media (max-width: 767px) {
            #hero-text {
                height: 17rem;
            }
        }

        @media (min-width: 768px) {
            #hero-text {
                height: 15rem;
            }
        }

    </style>
    <main class="grow">

        <!-- Hero -->
        <section class="relative">

            <!-- Illustration behind hero content -->
            <div class="absolute left-1/2 transform -translate-x-1/2 bottom-0 pointer-events-none -z-1"
                 aria-hidden="true">
                <svg width="1360" height="578" viewBox="0 0 1360 578" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient x1="50%" y1="0%" x2="50%" y2="100%" id="illustration-01">
                            <stop stop-color="#FFF" offset="0%"/>
                            <stop stop-color="#EAEAEA" offset="77.402%"/>
                            <stop stop-color="#DFDFDF" offset="100%"/>
                        </linearGradient>
                    </defs>
                    <g fill="url(#illustration-01)" fill-rule="evenodd">
                        <circle cx="1232" cy="128" r="128"/>
                        <circle cx="155" cy="443" r="64"/>
                    </g>
                </svg>
            </div>

            <div class="max-w-6xl mx-auto px-4 sm:px-6">

                <!-- Hero content -->
                <div class="pt-32 pb-12 md:pt-40 md:pb-20">

                    <!-- Section header -->
                    <div class="text-center pb-12 md:pb-16">
                        <h1 class="text-5xl md:text-6xl font-extrabold leading-tighter tracking-tighter mb-4" id="hero-text"
                            data-aos="zoom-y-out">Your own ChatGPT for <br><span
                                class="bg-clip-text text-transparent bg-gradient-to-r from-blue-500 to-teal-400 "
                                id="changeable">your website</span></h1>
                        <div class="max-w-3xl mx-auto">
                            <p class="text-xl text-gray-600 mb-8" data-aos="zoom-y-out" data-aos-delay="150">
                                Build a custom a chatbot like ChatGPT for your website or for personal use. No coding
                                required.
                            </p>
                            <div class="max-w-xs mx-auto sm:max-w-none sm:flex sm:justify-center" data-aos="zoom-y-out"
                                 data-aos-delay="300">
                                @if(\Illuminate\Support\Facades\Auth::user())
                                    <div>
                                        <a class="btn text-white bg-blue-600 hover:bg-blue-700 w-full mb-4 sm:w-auto sm:mb-0"
                                           href="{{route('index')}}">Dashboard</a>
                                        <a class="btn text-gray-200 bg-gray-900 hover:bg-gray-800 w-full mb-4 sm:w-auto sm:mb-0"
                                           href="{{route('marketing.pricing')}}">Pricing</a>
                                    </div>
                                @else
                                    <div>
                                        <a class="btn text-white bg-blue-600 hover:bg-blue-700 w-full mb-4 sm:w-auto sm:mb-0"
                                           href="{{route('marketing.register')}}">Start for free</a>
                                        <a class="btn text-gray-200 bg-gray-900 hover:bg-gray-800 w-full mb-4 sm:w-auto sm:mb-0"
                                           href="{{route('marketing.pricing')}}">Pricing</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Hero image -->
                    <div x-data="{ modalExpanded: false }">
                        <div class="relative flex justify-center mb-8" data-aos="zoom-y-out" data-aos-delay="450">
                            <div class="flex flex-col justify-center">

                                <video class="mx-auto rounded shadow-xl"
                                       width="768" height="432" autoplay muted playsinline loop>
                                    <source src="/marketing/videos/video.mp4"  type="video/mp4">
                                    <!-- Add additional source tags for different video formats if needed -->
                                    Your browser does not support the video tag.
                                </video>

                            </div>
                            <button
                                class="absolute top-full flex items-center transform -translate-y-1/2 bg-white rounded-full font-medium group p-4 shadow-lg"
                                @click.prevent="modalExpanded = true" aria-controls="modal">
                                <svg class="w-6 h-6 fill-current text-gray-400 group-hover:text-blue-600 shrink-0"
                                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10zm0 2C5.373 24 0 18.627 0 12S5.373 0 12 0s12 5.373 12 12-5.373 12-12 12z"/>
                                    <path d="M10 17l6-5-6-5z"/>
                                </svg>
                                <span class="ml-3">Watch the full video (1 min)</span>
                            </button>
                        </div>

                        <!-- Modal backdrop -->
                        <div
                            class="fixed inset-0 z-50 bg-white bg-opacity-75 transition-opacity backdrop-blur-sm"
                            x-show="modalExpanded"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-out duration-100"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            aria-hidden="true"
                            x-cloak
                        ></div>

                        <!-- Modal dialog -->
                        <div
                            id="modal"
                            class="fixed inset-0 z-50 overflow-hidden flex items-center justify-center transform px-4 sm:px-6"
                            role="dialog"
                            aria-modal="true"
                            aria-labelledby="modal-headline"
                            x-show="modalExpanded"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-out duration-200"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            x-cloak
                        >
                            <div class="bg-white overflow-auto max-w-6xl w-full max-h-full"
                                 @click.outside="modalExpanded = false" @keydown.escape.window="modalExpanded = false">
                                <div class="relative pb-9/16">
                                    <video x-init="$watch('modalExpanded', value => value ? $el.play() : $el.pause())"
                                           class="absolute w-full h-full" width="1920" height="1080" loop controls>
                                        <source src="/marketing/videos/video.mp4" type="video/mp4"/>
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </section>

        <!-- Features -->
        <section class="relative">

            <!-- Section background (needs .relative class on parent and next sibling elements) -->
            <div class="absolute inset-0 bg-gray-100 pointer-events-none mb-16" aria-hidden="true"></div>
            <div class="absolute left-0 right-0 m-auto w-px p-px h-20 bg-gray-200 transform -translate-y-1/2"></div>

            <div class="relative max-w-6xl mx-auto px-4 sm:px-6">
                <div class="pt-12 md:pt-20">

                    <!-- Section content -->
                    <div class="md:grid md:grid-cols-12 md:gap-6" x-data="{ tab: '1' }">

                        <!-- Content -->
                        <div class="max-w-xl md:max-w-none md:w-full mx-auto md:col-span-7 lg:col-span-6 md:mt-6">
                            <div class="md:pr-4 lg:pr-12 xl:pr-16 mb-8">
                                <h3 class="h3 mb-3">Live demo</h3>
                                <p class="text-xl text-gray-600">We have prepared multiple demos on what you can build
                                    with our tools in less than 5 minutes - same amount of time </p>
                            </div>
                            <!-- Tabs buttons -->
                            <div class="mb-8 md:mb-0">
                                <button
                                    :class="tab !== '1' ? 'bg-white shadow-md border-gray-200 hover:shadow-lg' : 'bg-gray-200 border-transparent'"
                                    class="text-left flex items-center text-lg p-5 rounded border transition duration-300 ease-in-out mb-3"
                                    @click.prevent
                                    @click="tab = '1'"
                                >
                                    <div>
                                        <div class="font-bold leading-snug tracking-tight mb-1">
                                            AI Customer support agent for OpenChat
                                        </div>
                                        <div class="text-gray-600">Take collaboration to the next level with security
                                            and administrative features built for teams.
                                        </div>
                                    </div>
                                    <div
                                        class="flex justify-center items-center w-8 h-8 bg-white rounded-full shadow shrink-0 ml-3">
                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 12 12"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M11.953 4.29a.5.5 0 00-.454-.292H6.14L6.984.62A.5.5 0 006.12.173l-6 7a.5.5 0 00.379.825h5.359l-.844 3.38a.5.5 0 00.864.445l6-7a.5.5 0 00.075-.534z"/>
                                        </svg>
                                    </div>
                                </button>
                                <button
                                    :class="tab !== '2' ? 'bg-white shadow-md border-gray-200 hover:shadow-lg' : 'bg-gray-200 border-transparent'"
                                    class="text-left flex items-center text-lg p-5 rounded border transition duration-300 ease-in-out mb-3"
                                    @click.prevent
                                    @click="tab = '2'"
                                >
                                    <div>
                                        <div class="font-bold leading-snug tracking-tight mb-1">
                                            AI Chatbot trained on 200 pages PDF document
                                        </div>
                                        <div class="text-gray-600">Take collaboration to the next level with security
                                            and administrative features built for teams.
                                        </div>
                                    </div>
                                    <div
                                        class="flex justify-center items-center w-8 h-8 bg-white rounded-full shadow shrink-0 ml-3">
                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 12 12"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M11.854.146a.5.5 0 00-.525-.116l-11 4a.5.5 0 00-.015.934l4.8 1.921 1.921 4.8A.5.5 0 007.5 12h.008a.5.5 0 00.462-.329l4-11a.5.5 0 00-.116-.525z"
                                                fill-rule="nonzero"/>
                                        </svg>
                                    </div>
                                </button>
                                <button
                                    :class="tab !== '3' ? 'bg-white shadow-md border-gray-200 hover:shadow-lg' : 'bg-gray-200 border-transparent'"
                                    class="text-left flex items-center text-lg p-5 rounded border transition duration-300 ease-in-out"
                                    @click.prevent
                                    @click="tab = '3'"
                                >
                                    <div>
                                        <div class="font-bold leading-snug tracking-tight mb-1">
                                            AI Chatbot trained on a whole Confluence workspace
                                        </div>
                                        <div class="text-gray-600">Take collaboration to the next level with security
                                            and administrative features built for teams.
                                        </div>
                                    </div>
                                    <div
                                        class="flex justify-center items-center w-8 h-8 bg-white rounded-full shadow shrink-0 ml-3">
                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 12 12"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M11.334 8.06a.5.5 0 00-.421-.237 6.023 6.023 0 01-5.905-6c0-.41.042-.82.125-1.221a.5.5 0 00-.614-.586 6 6 0 106.832 8.529.5.5 0 00-.017-.485z"
                                                fill="#191919" fill-rule="nonzero"/>
                                        </svg>
                                    </div>
                                </button>
                            </div>
                        </div>
                        <!-- Tabs items -->
                        <div
                            class="max-w-xl md:max-w-none md:w-full mx-auto md:col-span-5 lg:col-span-6 mb-8 md:mb-0 md:order-1"
                            data-aos="zoom-y-out">
                            <div class="relative flex flex-col text-center lg:text-right" id="async-chat-iframes">

                            </div>
                        </div>


                    </div>

                    <!-- Section header -->
                    <div class="max-w-3xl mx-auto text-center pb-12 md:pb-16 " style="margin-top: 4rem;">
                        <h1 class="h2 mb-4">How the he*k does this work?</h1>
                        <p class="text-xl text-gray-600">Once you provide your website, PDF files, etc... , we use
                            powerful crawlers to crawl your data and transform it to knowledge using LLMs! .</p>
                    </div>

                    <!-- Top image -->
                    <div class="pb-12 md:pb-16">

                    </div>
                </div>
            </div>
        </section>

        <!-- Features blocks -->
        <section class="relative">

            <!-- Section background (needs .relative class on parent and next sibling elements) -->
            <div class="absolute inset-0 top-1/2 md:mt-24 lg:mt-0 bg-gray-900 pointer-events-none"
                 aria-hidden="true"></div>
            <div
                class="absolute left-0 right-0 bottom-0 m-auto w-px p-px h-20 bg-gray-200 transform translate-y-1/2"></div>

            <div class="relative max-w-6xl mx-auto px-4 sm:px-6">
                <div class="py-12 md:py-20">

                    <!-- Section header -->
                    <div class="max-w-3xl mx-auto text-center pb-12 md:pb-20">
                        <h2 class="h2 mb-4">What can I do with OpenChat?</h2>
                        <p class="text-xl text-gray-600">Well, a lot. </p>
                    </div>

                    <!-- Items -->
                    <div
                        class="max-w-sm mx-auto grid gap-6 md:grid-cols-2 lg:grid-cols-3 items-start md:max-w-2xl lg:max-w-none">

                        <!-- 1st item -->
                        <div class="relative flex flex-col items-center p-6 bg-white rounded shadow-xl">
                            <svg class="w-16 h-16 p-1 -mt-1 mb-2" viewBox="0 0 64 64"
                                 xmlns="http://www.w3.org/2000/svg">
                                <g fill="none" fill-rule="evenodd">
                                    <rect class="fill-current text-blue-600" width="64" height="64" rx="32"/>
                                    <g stroke-width="2">
                                        <path class="stroke-current text-blue-300"
                                              d="M34.514 35.429l2.057 2.285h8M20.571 26.286h5.715l2.057 2.285"/>
                                        <path class="stroke-current text-white"
                                              d="M20.571 37.714h5.715L36.57 26.286h8"/>
                                        <path class="stroke-current text-blue-300" stroke-linecap="square"
                                              d="M41.143 34.286l3.428 3.428-3.428 3.429"/>
                                        <path class="stroke-current text-white" stroke-linecap="square"
                                              d="M41.143 29.714l3.428-3.428-3.428-3.429"/>
                                    </g>
                                </g>
                            </svg>
                            <h4 class="text-xl font-bold leading-snug tracking-tight mb-1">Customer support chatbot</h4>
                            <p class="text-gray-600 text-center">Trained on your very own data whatever it is</p>
                        </div>

                        <!-- 2nd item -->
                        <div class="relative flex flex-col items-center p-6 bg-white rounded shadow-xl">
                            <svg class="w-16 h-16 p-1 -mt-1 mb-2" viewBox="0 0 64 64"
                                 xmlns="http://www.w3.org/2000/svg">
                                <g fill="none" fill-rule="evenodd">
                                    <rect class="fill-current text-blue-600" width="64" height="64" rx="32"/>
                                    <g stroke-width="2" transform="translate(19.429 20.571)">
                                        <circle class="stroke-current text-white" stroke-linecap="square" cx="12.571"
                                                cy="12.571" r="1.143"/>
                                        <path class="stroke-current text-white"
                                              d="M19.153 23.267c3.59-2.213 5.99-6.169 5.99-10.696C25.143 5.63 19.514 0 12.57 0 5.63 0 0 5.629 0 12.571c0 4.527 2.4 8.483 5.99 10.696"/>
                                        <path class="stroke-current text-blue-300"
                                              d="M16.161 18.406a6.848 6.848 0 003.268-5.835 6.857 6.857 0 00-6.858-6.857 6.857 6.857 0 00-6.857 6.857 6.848 6.848 0 003.268 5.835"/>
                                    </g>
                                </g>
                            </svg>
                            <h4 class="text-xl font-bold leading-snug tracking-tight mb-1">Private tutor</h4>
                            <p class="text-gray-600 text-center">Upload your books, and ask the bot any question</p>
                        </div>

                        <!-- 3rd item -->
                        <div class="relative flex flex-col items-center p-6 bg-white rounded shadow-xl">
                            <svg class="w-16 h-16 p-1 -mt-1 mb-2" viewBox="0 0 64 64"
                                 xmlns="http://www.w3.org/2000/svg">
                                <g fill="none" fill-rule="evenodd">
                                    <rect class="fill-current text-blue-600" width="64" height="64" rx="32"/>
                                    <g stroke-width="2">
                                        <path class="stroke-current text-blue-300"
                                              d="M34.743 29.714L36.57 32 27.43 43.429H24M24 20.571h3.429l1.828 2.286"/>
                                        <path class="stroke-current text-white" stroke-linecap="square"
                                              d="M34.743 41.143l1.828 2.286H40M40 20.571h-3.429L27.43 32l1.828 2.286"/>
                                        <path class="stroke-current text-blue-300" d="M36.571 32H40"/>
                                        <path class="stroke-current text-white" d="M24 32h3.429"
                                              stroke-linecap="square"/>
                                    </g>
                                </g>
                            </svg>
                            <h4 class="text-xl font-bold leading-snug tracking-tight mb-1">Internal knowledge</h4>
                            <p class="text-gray-600 text-center">Your employees doesnot have to waste more time searching Confluence</p>
                        </div>

                        <!-- 4th item -->
                        <div class="relative flex flex-col items-center p-6 bg-white rounded shadow-xl">
                            <svg class="w-16 h-16 p-1 -mt-1 mb-2" viewBox="0 0 64 64"
                                 xmlns="http://www.w3.org/2000/svg">
                                <g fill="none" fill-rule="evenodd">
                                    <rect class="fill-current text-blue-600" width="64" height="64" rx="32"/>
                                    <g stroke-width="2">
                                        <path class="stroke-current text-white"
                                              d="M32 37.714A5.714 5.714 0 0037.714 32a5.714 5.714 0 005.715 5.714"/>
                                        <path class="stroke-current text-white"
                                              d="M32 37.714a5.714 5.714 0 015.714 5.715 5.714 5.714 0 015.715-5.715M20.571 26.286a5.714 5.714 0 005.715-5.715A5.714 5.714 0 0032 26.286"/>
                                        <path class="stroke-current text-white"
                                              d="M20.571 26.286A5.714 5.714 0 0126.286 32 5.714 5.714 0 0132 26.286"/>
                                        <path class="stroke-current text-blue-300"
                                              d="M21.714 40h4.572M24 37.714v4.572M37.714 24h4.572M40 21.714v4.572"
                                              stroke-linecap="square"/>
                                    </g>
                                </g>
                            </svg>
                            <h4 class="text-xl font-bold leading-snug tracking-tight mb-1">Share with client</h4>
                            <p class="text-gray-600 text-center">Create private chatbot and share them with your clients</p>
                        </div>

                        <!-- 5th item -->
                        <div class="relative flex flex-col items-center p-6 bg-white rounded shadow-xl">
                            <svg class="w-16 h-16 p-1 -mt-1 mb-2" viewBox="0 0 64 64"
                                 xmlns="http://www.w3.org/2000/svg">
                                <g fill="none" fill-rule="evenodd">
                                    <rect class="fill-current text-blue-600" width="64" height="64" rx="32"/>
                                    <g stroke-width="2">
                                        <path class="stroke-current text-white"
                                              d="M19.429 32a12.571 12.571 0 0021.46 8.89L23.111 23.11A12.528 12.528 0 0019.429 32z"/>
                                        <path class="stroke-current text-blue-300"
                                              d="M32 19.429c6.943 0 12.571 5.628 12.571 12.571M32 24a8 8 0 018 8"/>
                                        <path class="stroke-current text-white" d="M34.286 29.714L32 32"/>
                                    </g>
                                </g>
                            </svg>
                            <h4 class="text-xl font-bold leading-snug tracking-tight mb-1">Personal library</h4>
                            <p class="text-gray-600 text-center">Upload your books, texts, and then ask the bot any question</p>
                        </div>

                        <!-- 6th item -->
                        <div class="relative flex flex-col items-center p-6 bg-white rounded shadow-xl">
                            <svg class="w-16 h-16 p-1 -mt-1 mb-2" viewBox="0 0 64 64"
                                 xmlns="http://www.w3.org/2000/svg">
                                <g fill="none" fill-rule="evenodd">
                                    <rect class="fill-current text-blue-600" width="64" height="64" rx="32"/>
                                    <g stroke-width="2" stroke-linecap="square">
                                        <path class="stroke-current text-white"
                                              d="M29.714 40.358l-4.777 2.51 1.349-7.865-5.715-5.57 7.898-1.147L32 21.13l3.531 7.155 7.898 1.147L40 32.775"/>
                                        <path class="stroke-current text-blue-300"
                                              d="M44.571 43.429H34.286M44.571 37.714H34.286"/>
                                    </g>
                                </g>
                            </svg>
                            <h4 class="text-xl font-bold leading-snug tracking-tight mb-1">Your use case?</h4>
                            <p class="text-gray-600 text-center">You can use the bot as you wish! it's that simple</p>
                        </div>

                    </div>

                </div>
            </div>
        </section>


        <!-- Cta -->
        <section style="margin-top: 4rem;">
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                <div class="pb-12 md:pb-20">

                    <!-- CTA box -->
                    <div class="bg-blue-600 rounded py-10 px-8 md:py-16 md:px-12 shadow-2xl" data-aos="zoom-y-out">

                        <div class="flex flex-col lg:flex-row justify-between items-center">

                            <!-- CTA content -->
                            <div class="mb-6 lg:mr-16 lg:mb-0 text-center lg:text-left">
                                <h3 class="h3 text-white mb-2">Build your own ChatGPT for free!</h3>
                                <p class="text-white text-lg opacity-75">We have a generous free tier available to get
                                    you started right away.</p>
                            </div>

                            <!-- CTA button -->
                            <div>
                                <a class="btn text-blue-600 bg-gradient-to-r from-blue-100 to-white" href="{{route('marketing.register')}}">Get
                                    started for free</a>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </section>

    </main>
@endsection

@section('scripts')
    <script>

        // on load (native JS)
        setTimeout(function () {
            // Define an array of words to cycle through
            var words = [
                'your customer support',
                'your pdf books',
                'your <img style="width: 100px; display: inline" src="https://cdn.worldvectorlogo.com/logos/notion-logo-1.svg"> Notion',
                'your <img style="width: 100px; display: inline" src="https://www.svgrepo.com/show/353597/confluence.svg"> Confluence',
                'internal knowledge',
                'your website'
            ];

            // Get the changeable span element
            var changeableElement = document.getElementById('changeable');

            // Initialize a variable to keep track of the current word index
            var currentWordIndex = 0;

            // Set an interval to change the word every 2 seconds
            setInterval(function () {
                // Get the current word from the array
                var currentWord = words[currentWordIndex];

                // Apply a fade-out effect by adding a CSS class
                changeableElement.classList.add('fade-out');

                // Set a timeout to change the word after the fade-out effect completes
                setTimeout(function () {
                    // Update the content of the span element with the new word
                    changeableElement.innerHTML = currentWord;

                    // Apply a fade-in effect by removing the CSS class
                    changeableElement.classList.remove('fade-out');

                    // Increment the current word index
                    currentWordIndex = (currentWordIndex + 1) % words.length;
                }, 500); // Adjust the duration of the fade-out effect (in milliseconds)
            }, 2000); // Adjust the interval between word changes (in milliseconds)
        }, 1000);

        window.onload = function () {
            document.getElementById('async-chat-iframes').innerHTML = `               <!-- Item 1 -->
                                <div
                                    class="w-full"
                                    x-show="tab === '1'"
                                    x-transition:enter="transition ease-in-out duration-700 transform order-first"
                                    x-transition:enter-start="opacity-0 translate-y-16"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in-out duration-300 transform absolute"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 -translate-y-16"
                                >
                                    <section
                                        style="height: 100%; overflow: auto; text-align: right; border-radius: 20px;box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;">
                                        <iframe
                                            src="{{route('chat', ['token' => 'eQq6ssQp5TbFyB9xkg4I'])}}"
                                            class="w-full h-96" style="height: 60vh;"
                                            frameborder="0"></iframe>
                                    </section>
                                </div>
                                <!-- Item 2 -->
                                <div
                                    class="w-full"
                                    x-show="tab === '2'"
                                    x-transition:enter="transition ease-in-out duration-700 transform order-first"
                                    x-transition:enter-start="opacity-0 translate-y-16"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in-out duration-300 transform absolute"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 -translate-y-16"
                                >
                                    <section
                                        style="height: 100%; overflow: auto; text-align: right; border-radius: 20px;box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;">
                                        <iframe
                                            src="{{route('chat', ['token' => 'quwtbH2dXLTIcyIwDtTL'])}}"
                                            class="w-full h-96" style="height: 60vh;"
                                            frameborder="0"></iframe>
                                    </section>
                                </div>
                                <!-- Item 3 -->
                                <div
                                    class="w-full"
                                    x-show="tab === '3'"
                                    x-transition:enter="transition ease-in-out duration-700 transform order-first"
                                    x-transition:enter-start="opacity-0 translate-y-16"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in-out duration-300 transform absolute"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 -translate-y-16"
                                >
                                    <section
                                        style="height: 100%; overflow: auto; text-align: right; border-radius: 20px;box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;">
                                        <iframe
                                            src="{{route('chat', ['token' => 'quwtbH2dXLTIcyIwDtTL'])}}"
                                            class="w-full h-96" style="height: 60vh;"
                                            frameborder="0"></iframe>
                                    </section>
                                </div>`
        }
    </script>
@endsection
