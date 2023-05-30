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
                            <a class="flex items-center justify-center w-6 h-6 rounded-full text-xs font-semibold bg-indigo-500 text-white"
                               href="{{route('onboarding.config', ['id' => request()->route('id')])}}"
                              >3</a>
                        </li>
                        <li>
                            <a class="flex items-center justify-center w-6 h-6 rounded-full text-xs font-semibold bg-indigo-500 text-white"
                              >4</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="px-4 py-8">
            <div class="max-w-md mx-auto">

                <div class="text-center">
                    <svg class="inline-flex w-16 h-16 fill-current mb-6" viewBox="0 0 64 64">
                        <circle class="text-emerald-100" cx="32" cy="32" r="32"></circle>
                        <path class="text-emerald-500" d="m28.5 41-8-8 3-3 5 5 12-12 3 3z"></path>
                    </svg>
                    <h1 class="text-3xl text-slate-800 font-bold mb-8">That is it! 🙌</h1>
                    <a class="btn bg-indigo-500 hover:bg-indigo-600 text-white" href="{{route('chatbot.settings-theme', ['id' => request()->route('id')])}}">Open your bot 🔥 -&gt;</a>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('scripts')

@endsection
