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
                            <a class="flex items-center justify-center w-6 h-6 rounded-full text-xs font-semibold bg-slate-100 text-slate-500"
                            >4</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="px-4 py-8">
            <div class="max-w-md mx-auto">

                <h1 class="text-3xl text-slate-800 font-bold mb-6">In the meanwhile, Let's do some configurations ✨</h1>

                <div class="font-medium text-slate-800 text-sm mb-6">While we are crawling your website, you can do some
                    configurations on your chatbot
                </div>
                <hr>
                <!-- Form -->
                <form action="{{route('onboarding.config.create', ['id' => request()->route('id')])}}" method="POST" id="character">
                    @csrf
                    <div class="space-y-4 mb-8 mt-4">
                        <!-- Company Name -->

                        <h3 class="text-lg font-bold text-slate-800">What type of character that chatbot must be?</h3>

                        <div class="font-medium text-slate-800 text-sm mb-6">Choose the preferred bot character, wise &
                            strict character will force the bot to only answer questions that has been seen before,
                            while the Knowledgeable makes the bot a bit more fixable and try to be creative with answers
                        </div>


                        <div class="flex items-center justify-between space-x-6 mb-8">
                            <div class="sm:flex space-y-3 sm:space-y-0 sm:space-x-4 mb-8">
                                <label class="flex-1 relative block cursor-pointer">
                                    <input type="radio" name="character_name" value="wise" class="peer sr-only"
                                           checked="">
                                    <div
                                        class="h-full text-center bg-white px-4 py-6 rounded border border-slate-200 hover:border-slate-300 shadow-sm duration-150 ease-in-out">
                                        <div class="emoji" style="font-size: 50px">
                                            🦉
                                        </div>
                                        <div class="font-medium text-slate-800 mb-1">Wise & Strict</div>
                                        <div class="text-sm">Only answers questions within the given knowledge.</div>
                                    </div>
                                    <div
                                        class="absolute inset-0 border-2 border-transparent peer-checked:border-indigo-400 rounded pointer-events-none"
                                        aria-hidden="true"></div>
                                </label>
                                <label class="flex-1 relative block cursor-pointer">
                                    <input type="radio" name="character_name" value="knowledgeable"
                                           class="peer sr-only">
                                    <div
                                        class="h-full text-center bg-white px-4 py-6 rounded border border-slate-200 hover:border-slate-300 shadow-sm duration-150 ease-in-out">
                                        <div class="emoji" style="font-size: 50px">
                                            🙆‍
                                        </div>
                                        <div class="font-medium text-slate-800 mb-1">Knowledgeable</div>
                                        <div class="text-sm">Answers questions within the given knowledge and beyond.
                                        </div>
                                    </div>
                                    <div
                                        class="absolute inset-0 border-2 border-transparent peer-checked:border-indigo-400 rounded pointer-events-none"
                                        aria-hidden="true"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <a class="text-sm underline hover:no-underline" href="{{route('onboarding.welcome')}}">&lt;-
                            Back</a>
                        <button class="btn bg-indigo-500 hover:bg-indigo-600 text-white ml-auto" type="submit"
                                form="character">Next Step -&gt;
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>

@endsection

@section('scripts')

@endsection
