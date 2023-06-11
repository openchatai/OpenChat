@extends('layout.app', ['title' => __('Dashboard')])
@section('content')
    <div class="min-h-screen h-full flex flex-col after:flex-1">


        <!-- Header -->

        <!-- Progress bar -->
        <div class="px-4 pt-12 pb-8">
            <div class="max-w-md mx-auto w-full">
                <div class="relative">
                    <div class="absolute left-0 top-1/2 -mt-px w-full h-0.5 bg-slate-200" aria-hidden="true"></div>
                    <ul class="relative flex justify-between w-full">
                        <li>
                            <a class="flex items-center justify-center w-6 h-6 rounded-full text-xs font-semibold bg-indigo-500 text-white"
                               href="{{route('onboarding.welcome')}}">1</a>
                        </li>
                        <li>
                            <a class="flex items-center justify-center w-6 h-6 rounded-full text-xs font-semibold bg-indigo-500 text-white"
                               href="{{route('onboarding.website')}}">2</a>
                        </li>
                        <li>
                            <a class="flex items-center justify-center w-6 h-6 rounded-full text-xs font-semibold bg-slate-100 text-slate-500"
                            >3</a>
                        </li>
                        <li>
                            <a class="flex items-center justify-center w-6 h-6 rounded-full text-xs font-semibold bg-slate-100 text-slate-500"
                            >4</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="px-4 py-8">
            <div class="max-w-md mx-auto">

                <h1 class="text-3xl text-slate-800 font-bold mb-6">Select your data source ✨</h1>
                <p style="margin-bottom: 1rem">Select the source of your data to train your new chatbot, <strong>you can
                        always add new sources later</strong></p>
                <!-- Form -->
                @if ($errors->has('website'))
                    <div x-show="open" x-data="{ open: true }" style="margin-bottom: 1rem;">
                        <div class="px-4 py-2 rounded-sm text-sm bg-amber-100 border border-amber-200 text-amber-600">
                            <div class="flex w-full justify-between items-start">
                                <div class="flex">
                                    <svg class="w-4 h-4 shrink-0 fill-current opacity-80 mt-[3px] mr-3"
                                         viewBox="0 0 16 16">
                                        <path
                                            d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm0 12c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1zm1-3H7V4h2v5z"></path>
                                    </svg>
                                    <div>Please enter a valid website URL, it's important that your website is live and
                                        accessible
                                    </div>
                                </div>
                                <button class="opacity-70 hover:opacity-80 ml-3 mt-[3px]" @click="open = false">
                                    <div class="sr-only">Close</div>
                                    <svg class="w-4 h-4 fill-current">
                                        <path
                                            d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="space-y-4 mb-8">
                    <!-- Company Name -->
                    <ul class="space-y-2 sm:flex sm:space-y-0 sm:space-x-2 lg:space-y-2 lg:space-x-0 lg:flex-col mb-4">
                        <li>
                            <button onclick="window.location.href='{{ route('onboarding.website') }}'"
                                    class="w-full h-full text-left py-3 px-4 rounded bg-white border border-slate-200 hover:border-slate-300 shadow-sm duration-150 ease-in-out">
                                <div class="flex flex-wrap items-center justify-between mb-0.5">
                                    <span class="font-semibold text-slate-800"> 🔗 Website</span>
                                </div>
                                <div class="text-sm">We will crawl your website and extract the knowledge
                                    automatically.
                                </div>
                            </button>
                        </li>

                        <li>
                            <button
                                onclick="window.location.href = '{{ route('onboarding.pdf') }}'"
                                class="w-full h-full text-left py-3 px-4 rounded bg-white border border-slate-200 hover:border-slate-300 shadow-sm duration-150 ease-in-out">
                                <div class="flex flex-wrap items-center justify-between mb-0.5">
                                    <span class="font-semibold text-slate-800">📚 PDF files</span>
                                </div>
                                <div class="text-sm">We will scan your PDF files and extract knowledge and any
                                    information
                                </div>
                            </button>
                        </li>

                        <li>
                            <button
                                onclick="window.location.href = '{{ route('onboarding.codebase') }}'"
                                class="w-full h-full text-left py-3 px-4 rounded bg-white border border-slate-200 hover:border-slate-300 shadow-sm duration-150 ease-in-out">
                                <div class="flex flex-wrap items-center justify-between mb-0.5">
                                    <span class="font-semibold text-slate-800">💻 Codebase</span>
                                </div>
                                <div class="text-sm">
                                    Provide a link to your codebase and we will extract the knowledge and any information
                                </div>
                            </button>
                        </li>

                        <li>
                            <button
                                class="w-full h-full text-left py-3 px-4 rounded bg-white border border-slate-200 hover:border-slate-300 shadow-sm duration-150 ease-in-out cursor-not-allowed" >
                                <div class="flex flex-wrap items-center justify-between mb-0.5">
                                    <span class="font-semibold text-slate-800">Confluence
                                              <div
                                                  class="inline-flex items-center text-xs font-medium text-slate-100 bg-slate-700 rounded-full text-center px-2 py-0.5"
                                                  style="margin-left: 0.3rem">
                                                                    <svg
                                                                        class="w-3 h-3 shrink-0 fill-current text-amber-500 mr-1"
                                                                        viewBox="0 0 12 12">
                                                                        <path
                                                                            d="M11.953 4.29a.5.5 0 00-.454-.292H6.14L6.984.62A.5.5 0 006.12.173l-6 7a.5.5 0 00.379.825h5.359l-.844 3.38a.5.5 0 00.864.445l6-7a.5.5 0 00.075-.534z"></path>
                                                                    </svg>
                                                                    <span>soon </span>
                                                                </div>
                                    </span>
                                </div>
                                <div class="text-sm">Automatically extract data from your Confluence from your
                                    workspace
                                </div>
                            </button>
                        </li>
                        <li>
                            <button
                                class="w-full h-full text-left py-3 px-4 rounded bg-white border border-slate-200 hover:border-slate-300 shadow-sm duration-150 ease-in-out cursor-not-allowed">
                                <div class="flex flex-wrap items-center justify-between mb-0.5">
                                    <span class="font-semibold text-slate-800">
                                        Notion
                                    <div
                                        class="inline-flex items-center text-xs font-medium text-slate-100 bg-slate-700 rounded-full text-center px-2 py-0.5"
                                        style="margin-left: 0.3rem">
                                                                    <svg
                                                                        class="w-3 h-3 shrink-0 fill-current text-amber-500 mr-1"
                                                                        viewBox="0 0 12 12">
                                                                        <path
                                                                            d="M11.953 4.29a.5.5 0 00-.454-.292H6.14L6.984.62A.5.5 0 006.12.173l-6 7a.5.5 0 00.379.825h5.359l-.844 3.38a.5.5 0 00.864.445l6-7a.5.5 0 00.075-.534z"></path>
                                                                    </svg>
                                                                    <span>soon </span>
                                                                </div>

                                    </span>
                                </div>
                                <div class="text-sm">Automatically extract data from your
                                    Notion
                                    from your
                                    workspace
                                </div>
                            </button>
                        </li>
                        <li>
                            <button
                                class="w-full h-full text-left py-3 px-4 rounded bg-white border border-slate-200 hover:border-slate-300 shadow-sm duration-150 ease-in-out cursor-not-allowed">
                                <div class="flex flex-wrap items-center justify-between mb-0.5">
                                    <span class="font-semibold text-slate-800">
                                        Microsoft 360
                                    <div
                                        class="inline-flex items-center text-xs font-medium text-slate-100 bg-slate-700 rounded-full text-center px-2 py-0.5"
                                        style="margin-left: 0.3rem">
                                                                    <svg
                                                                        class="w-3 h-3 shrink-0 fill-current text-amber-500 mr-1"
                                                                        viewBox="0 0 12 12">
                                                                        <path
                                                                            d="M11.953 4.29a.5.5 0 00-.454-.292H6.14L6.984.62A.5.5 0 006.12.173l-6 7a.5.5 0 00.379.825h5.359l-.844 3.38a.5.5 0 00.864.445l6-7a.5.5 0 00.075-.534z"></path>
                                                                    </svg>
                                                                    <span>soon </span>
                                                                </div>

                                    </span>
                                </div>
                                <div class="text-sm">Automatically extract data from your
                                    Microsoft Word, Excel, Powerpoint
                                </div>
                            </button>
                        </li>

                        <li style="text-align: center">
                            ...
                            <br>

                            Many others to come 🔥️
                        </li>

                    </ul>

                </div>

				<div class="flex items-center justify-between">
                        <a class="text-sm underline hover:no-underline" href="{{route('onboarding.welcome')}}">&lt;-
                            Back</a>
                    </div>

            </div>
        </div>

    </div>

@endsection

@section('scripts')

@endsection
