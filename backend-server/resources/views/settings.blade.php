@extends('layout.app', ['title' => __('Dashboard')])
@section('content')
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
                                        <input id="name" name="name" class="form-input w-full" type="text"
                                               value="{{$bot->getName()}}">
                                    </div>
                                    <div class="sm:w-1/3">
                                        <label class="block text-sm font-medium mb-1" for="business-id">Bot ID</label>
                                        <input id="business-id" class="form-input w-full disabled" type="text"
                                               value="{{$bot->getId()->toString()}}" disabled>
                                    </div>
                                </div>
                            </section>

                            <!-- Enhanced privacy -->
                            <section>
                                <h3 class="text-xl leading-snug text-slate-800 font-bold mb-1">ِCustom Context</h3>
                                <div class="text-sm">You can change your bot initial context / prompt from here. also
                                    you can change the bot response language.
                                </div>
                                <div class="sm:flex sm:items-center space-y-4 sm:space-y-0 sm:space-x-4 mt-5">
                                    <div class="sm:w-1/3">
                                        <label class="block text-sm font-medium mb-1" for="name">Manual context</label>
                                        <textarea name="prompt_message" id="" class="form-input w-full promptMessage"
                                                  id="promptMessage" rows="10">{{$bot->getPromptMessage()}}</textarea>
                                    </div>
                                    <span style="margin-left: 1rem; margin-right: 1rem;">✨or ✨</span>
                                    <div class="sm:w-1/3">
                                        <div class="1-click-box">
                                            <div class="m-1.5">
                                                <!-- Start -->
                                                <button
                                                    class="btn border-slate-200 hover:border-slate-300 text-indigo-500"
                                                    onclick="fillPrompt('customer_support')">💁 Customer Support
                                                </button>
                                                <button
                                                    class="btn border-slate-200 hover:border-slate-300 text-indigo-500"
                                                    onclick="fillPrompt('search_engine')">🔍 Search Engine
                                                </button>

                                                <!-- End -->
                                            </div>
                                            <div class="m-1.5">
                                                <!-- Start -->
                                                <button
                                                    class="btn border-slate-200 hover:border-slate-300 text-indigo-500"
                                                    onclick="fillPrompt('teacher')">🧑‍🏫 Teacher
                                                </button>
                                                <button
                                                    class="btn border-slate-200 hover:border-slate-300 text-indigo-500"
                                                    onclick="fillPrompt('critical_thinker')">🤔 Critical thinker
                                                </button>
                                                <!-- End -->
                                            </div>
                                            <div class="m-1.5">
                                                <!-- Start -->
                                                <button
                                                    class="btn border-slate-200 hover:border-slate-300 text-indigo-500"
                                                    onclick="fillPrompt('software_engineer')">👨‍💻 Software Engineer
                                                </button>
                                                <button
                                                    class="btn border-slate-200 hover:border-slate-300 text-indigo-500"
                                                    onclick="fillPrompt('ghost_writer')">📚Ghost Writer
                                                </button>
                                                <!-- End -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </section>

                            <!-- Password -->
                            <section>
                                <h3 class="text-xl leading-snug text-slate-800 font-bold mb-1">Delete the bot</h3>
                                <div class="text-sm">Deleting the bot will delete all the data associated with it.</div>
                                <div class="mt-5">
                                    <a class="btn border-slate-200 shadow-sm text-indigo-500"
                                       href="{{route('chatbot.settings.delete', ['id' =>$bot->getId()])}}">Delete</a>
                                </div>
                            </section>
                        </div>

                            <!-- Panel footer -->
                            <footer>
                                <div class="flex flex-col px-6 py-5 border-t border-slate-200">
                                    <div class="flex self-end">
                                        <button class="btn bg-indigo-500 hover:bg-indigo-600 text-white ml-3"
                                                type="submit">Save Changes
                                        </button>
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
    <script>
        // Enum for different cases
        const Cases = {
            CUSTOMER_SUPPORT: 'customer_support',
            SEARCH_ENGINE: 'search_engine',
            TEACHER: 'teacher',
            CRITICAL_THINKER: 'critical_thinker',
            SOFTWARE_ENGINEER: 'software_engineer',
            GHOST_WRITER: 'ghost_writer',
        };

        // Enum for prompts
        const Prompts = {
            [Cases.CUSTOMER_SUPPORT]: `You are a helpful AI customer support agent. Use the following pieces of context to answer the question at the end.
If you don't know the answer, just say you don't know. DO NOT try to make up an answer.
If the question is not related to the context, politely respond that you are tuned to only answer questions that are related to the context.

{context}

Question: {question}
Helpful answer in markdown:
`,

            [Cases.SEARCH_ENGINE]: `You are an AI-powered search engine. Use the following pieces of context to generate search results for the query.
If you cannot find relevant information, respond with a message indicating no results were found.

{context}

Query: {question}
Search results in markdown:
`,

            [Cases.TEACHER]: `You are an AI teacher. Use the following pieces of context to provide an educational response to the question.
If the question is outside the scope of the context, kindly inform the user that it's beyond your expertise.

{context}

Question: {question}
Educational response in markdown:
`,

            [Cases.CRITICAL_THINKER]: `You are an AI critical thinker. Use the following pieces of context to analyze and provide a thoughtful response to the question.
If the question doesn't align with the context, respond by acknowledging the question but indicating that it's not within the purview of critical thinking.

{context}

Question: {question}
Thoughtful response in markdown:
`,

            [Cases.SOFTWARE_ENGINEER]: `You are an AI software engineer. Use the following pieces of context to address the technical question or provide programming assistance.
If the question is unrelated to software engineering, politely state that it's outside the domain of your expertise.

{context}

Question: {question}
Technical response in markdown:
`,

            [Cases.GHOST_WRITER]: `You are an AI ghost writer. Use the following pieces of context to craft a creative and engaging piece of writing in response to the request.
If the request is not within the scope of the context, politely explain that it's outside the realm of ghost writing.

{context}

Request: {question}
Ghost writing in markdown:
`,
        };

        console.log(prompt);



        function fillPrompt(content) {
            event.preventDefault();
            document.getElementsByClassName('promptMessage')[0].innerHTML = Prompts[content];
        }

    </script>
@endsection
