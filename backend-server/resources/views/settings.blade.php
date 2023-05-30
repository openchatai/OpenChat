@extends('layout.app', ['title' => __('Dashboard')])
@section('content')
    <style>
        .heart {
            animation: heartbeat 1.5s infinite;
        }

        @keyframes heartbeat {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.4);
            }
            100% {
                transform: scale(1);
            }
        }

        .not-ready-overlay {
            position: relative;
        }

        .not-ready-overlay::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #d9d9d9bd;
            filter: blur(19px);
            z-index: 1;
        }

        .not-ready-overlay-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #606060; /* Adjust the text color as desired */
            font-size: 24px; /* Adjust the font size as desired */
            z-index: 2;
            text-align: center;
        }

    </style>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="mb-8">

            <!-- Title -->
            <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">{{$bot->getName()}}: general settings✨</h1>
            <ul class="inline-flex flex-wrap text-sm font-medium">
                <li class="flex items-center">
                    <a class="text-slate-500 hover:text-indigo-500" href="{{route('index')}}">Home</a>
                    <svg class="h-4 w-4 fill-current text-slate-400 mx-3" viewBox="0 0 16 16">
                        <path d="M6.6 13.4L5.2 12l4-4-4-4 1.4-1.4L12 8z"></path>
                    </svg>
                </li>
                <li class="flex items-center">
                    <a class="text-slate-500 hover:text-indigo-500" >General Settings</a>
                </li>

            </ul>
        </div>

        <div class="bg-white shadow-lg rounded-sm mb-8">
            <div class="flex flex-col md:flex-row md:-mr-px">

                <!-- Sidebar -->
                @include('layout.sidebar-bot-page')

                <!-- Panel -->
                <div class="grow">
                    <form action="{{route('chatbot.settings.update', ['id' => request()->route('id')])}}" method="POST">
                        @if ($errors->any())
                            <div x-show="open" x-data="{ open: true }" style="margin-bottom: 1rem;">
                                <div class="px-4 py-2 rounded-sm text-sm bg-amber-100 border border-amber-200 text-amber-600">
                                    <div class="flex w-full justify-between items-start">
                                        <div class="flex">
                                            <svg class="w-4 h-4 shrink-0 fill-current opacity-80 mt-[3px] mr-3"
                                                 viewBox="0 0 16 16">
                                                <path
                                                    d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zm0 12c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1zm1-3H7V4h2v5z"></path>
                                            </svg>
                                            <div>

                                                @foreach($errors->all() as $error)
                                                    <p class="font-medium leading-snug">- {{ $error }}</p>
                                                @endforeach
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endif
                        @csrf
                        <!-- Panel body -->
                        <div class="p-6 space-y-6">

                            <!-- Business Profile -->
                            <section>
                                <h3 class="text-xl leading-snug text-slate-800 font-bold mb-1">General Settings</h3>
                                <div class="text-sm">This is your general bot settings such as name and id</div>
                                <div class="sm:flex sm:items-center space-y-4 sm:space-y-0 sm:space-x-4 mt-5">
                                    <div class="sm:w-1/3">
                                        <label class="block text-sm font-medium mb-1" for="name">Name</label>
                                        <input id="name" name="name" class="form-input w-full" type="text" value="{{$bot->getName()}}">
                                    </div>
                                    <div class="sm:w-1/3">
                                        <label class="block text-sm font-medium mb-1" for="business-id">Bot ID</label>
                                        <input id="business-id" class="form-input w-full disabled" type="text" value="{{$bot->getId()->toString()}}" disabled>
                                    </div>
                                </div>
                            </section>

                        </div>

                        <!-- Panel footer -->
                        <footer>
                            <div class="flex flex-col px-6 py-5 border-t border-slate-200">
                                <div class="flex self-end">
                                    <button class="btn bg-indigo-500 hover:bg-indigo-600 text-white ml-3" type="submit">Save Changes</button>
                                </div>
                            </div>
                        </footer>

                    </form>

                </div>

            </div>
        </div>

    </div>
@endsection

@section('scripts')
@endsection
