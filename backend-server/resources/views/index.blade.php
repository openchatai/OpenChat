@extends('layout.app', ['title' => __('Dashboard')])
@section('content')
    <style>

    </style>


    <div
        class="relative flex flex-col items-center justify-center px-4 sm:px-6 lg:px-8 py-8 lg:py-16 bg-indigo-500 overflow-hidden">
        <!-- Glow -->
        <div class="absolute pointer-events-none" aria-hidden="true">
            <svg width="512" height="512" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <radialGradient cx="50%" cy="50%" fx="50%" fy="50%" r="50%" id="ill-a">
                        <stop stop-color="#FFF" offset="0%"></stop>
                        <stop stop-color="#FFF" stop-opacity="0" offset="100%"></stop>
                    </radialGradient>
                </defs>
                <circle style="mix-blend-mode:overlay" cx="588" cy="650" r="256" transform="translate(-332 -394)" fill="url(#ill-a)" fill-rule="evenodd" opacity=".48"></circle>
            </svg>
        </div>
        <!-- Illustration -->
        <div class="absolute pointer-events-none" aria-hidden="true">
            <svg width="1280" height="361" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <defs>
                    <linearGradient x1="50%" y1="0%" x2="50%" y2="100%" id="ill2-b">
                        <stop stop-color="#A5B4FC" offset="0%"></stop>
                        <stop stop-color="#818CF8" offset="100%"></stop>
                    </linearGradient>
                    <linearGradient x1="50%" y1="24.537%" x2="50%" y2="100%" id="ill2-c">
                        <stop stop-color="#4338CA" offset="0%"></stop>
                        <stop stop-color="#6366F1" stop-opacity="0" offset="100%"></stop>
                    </linearGradient>
                    <path id="ill2-a" d="m64 0 64 128-64-20-64 20z"></path>
                    <path id="ill2-e" d="m40 0 40 80-40-12.5L0 80z"></path>
                </defs>
                <g fill="none" fill-rule="evenodd">
                    <g transform="rotate(51 -92.764 293.763)">
                        <mask id="ill2-d" fill="#fff">
                            <use xlink:href="#ill2-a"></use>
                        </mask>
                        <use fill="url(#ill2-b)" xlink:href="#ill2-a"></use>
                        <path fill="url(#ill2-c)" mask="url(#ill2-d)" d="M64-24h80v152H64z"></path>
                    </g>
                    <g transform="rotate(-51 618.151 -940.113)">
                        <mask id="ill2-f" fill="#fff">
                            <use xlink:href="#ill2-e"></use>
                        </mask>
                        <use fill="url(#ill2-b)" xlink:href="#ill2-e"></use>
                        <path fill="url(#ill2-c)" mask="url(#ill2-f)" d="M40.333-15.147h50v95h-50z"></path>
                    </g>
                </g>
            </svg>
        </div>
        <div class="relative w-full max-w-2xl mx-auto text-center">
            <div class="mb-5">
                <h1 class="text-2xl md:text-3xl text-white font-bold">👋 Welcome to OpenChat!</h1>
            </div>
        </div>
    </div>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">My chatbots ✨</h1>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">


                <!-- Create campaign button -->
                <a href="{{route('onboarding.welcome')}}" class="btn bg-indigo-500 hover:bg-indigo-600 text-white">
                    <svg class="w-4 h-4 fill-current opacity-50 shrink-0" viewBox="0 0 16 16">
                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z"></path>
                    </svg>
                    <span class="hidden xs:block ml-2">Create chatbot</span>
                </a>

            </div>

        </div>

        <!-- Cards -->
        <div class="grid grid-cols-12 gap-6">

            <!-- Card 1 -->
            @foreach($chatbots as $bot)
                <div class="col-span-full sm:col-span-6 xl:col-span-4 bg-white shadow-lg rounded-sm border border-slate-200">
                    <div class="flex flex-col h-full p-5">
                        <header>
                            <div class="flex items-center justify-between">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 bg-rose-500">
                                    <svg class="w-9 h-9 fill-current text-rose-50" viewBox="0 0 36 36">
                                        <path d="M25 24H11a1 1 0 01-1-1v-5h2v4h12v-4h2v5a1 1 0 01-1 1zM14 13h8v2h-8z"></path>
                                    </svg>
                                </div>
                            </div>
                        </header>
                        <div class="grow mt-2">
                            <a class="inline-flex text-slate-800 hover:text-slate-900 mb-1" href="{{route('chatbot.settings-theme', ['id' => $bot->getId()->toString()])}}">
                                <h2 class="text-xl leading-snug font-semibold">{{$bot->name}}</h2>
                            </a>
{{--                            <div class="text-sm">Lorem ipsum is placeholder text commonly used in the graphic, print, and publishing industries for previewing layouts.</div>--}}
                        </div>
                        <footer class="mt-5">
                            <div class="text-sm font-medium text-slate-500 mb-2">{{ $bot->created_at }}</div>
                            <div class="flex justify-between items-center">
                                <div>
{{--                                    <div class="text-xs inline-flex font-medium bg-emerald-100 text-emerald-600 rounded-full text-center px-2.5 py-1">--}}
{{--                                        {{$bot->getStatus()->getLabel()}}--}}
{{--                                    </div>--}}
                                </div>
                                <div>
                                    <a class="text-sm font-medium text-indigo-500 hover:text-indigo-600" href="{{route('chatbot.settings-theme', ['id' => $bot->getId()->toString()])}}">Open -&gt;</a>
                                </div>
                            </div>
                        </footer>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection

@section('scripts')
@endsection
