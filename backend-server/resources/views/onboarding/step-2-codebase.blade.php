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

                <h1 class="text-3xl text-slate-800 font-bold mb-6">GitHub Repo information ✨</h1>
                <!-- Form -->
                @if ($errors->has('repo'))
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
                                        Please provide a valid GitHub repository url.
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
                <form action="{{route('onboarding.codebase.create')}}" method="POST">
                    @csrf
                    <div class="space-y-4 mb-8">
                        <!-- Company Name -->
                        <div>
                            <label class="block text-sm font-medium mb-1" for="website-name">Repository url (must have <pre style="display: inline">main</pre> branch) <span
                                    class="text-rose-500">*</span></label>
                            <input id="repo-url" class="form-input w-full" type="text" name="repo" required autocomplete="off">
                        </div>

                        <div id="token-block" style="display: none;">
                            <p>
                                You can set the GITHUB_ACCESS_TOKEN environment variable to a GitHub access token to increase the rate limit and access private repositories. We will ignore binary files like images.
                            </p>
                        </div>

                        <div class="flex items-center justify-between space-x-6 mb-8">
                            <div>
                                <a class="text-sm underline hover:no-underline cursor-pointer" id="show-token">
                                    Your repo is private?
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <a class="text-sm underline hover:no-underline" href="{{route('onboarding.data-source')}}">&lt;-
                            Back</a>
                        <button type="submit" class="btn bg-indigo-500 hover:bg-indigo-600 text-white ml-auto">Next Step
                            -&gt;
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>

@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the link element by its ID
            var showTokenLink = document.getElementById('show-token');
            // Get the token block element by its ID
            var tokenBlock = document.getElementById('token-block');
            // Add a click event listener to the link
            showTokenLink.addEventListener('click', function() {
                // Show or hide the token block based on its current display state
                if (tokenBlock.style.display === 'none') {
                    tokenBlock.style.display = 'block';
                } else {
                    tokenBlock.style.display = 'none';
                }
            });
        });
    </script>
@endsection
