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

                <div class="space-y-4 mb-8">
                    <!-- Company Name -->
                    <ul class="space-y-2 sm:flex sm:space-y-0 sm:space-x-2 lg:space-y-2 lg:space-x-0 lg:flex-col mb-4">
                        <li>
                            <button onclick="window.location.href='{{ route('onboarding.website') }}'"
                                    class="w-full h-full text-left py-3 px-4 rounded bg-white border border-slate-200 hover:border-slate-300 shadow-sm duration-150 ease-in-out">
                                <div class="flex flex-wrap items-center justify-between mb-0.5">
                                    <span class="font-semibold text-slate-800">Website</span>
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
                                    <span class="font-semibold text-slate-800">PDF files</span>
                                </div>
                                <div class="text-sm">We will scan your PDF files and extract knowledge and any
                                    information
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

            </div>
        </div>

    </div>

@endsection

@section('scripts')

@endsection
