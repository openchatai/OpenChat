@extends('layout.app', ['title' => __('Dashboard')])
@section('content')
    <style>
        .image-uploader {
            border: 2px dashed #aaa;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            border-radius: 9px;
            background: #fafaf9;
        }

        .image-uploader img {
            max-width: 100%;
            max-height: 200px;
            margin-bottom: 1rem;
        }

        .img-thumbnail {
            padding: 0.25rem;
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
            width: 69px;
            margin-left: 4px;
            display: inline;
            margin-right: 1rem;
        }
    </style>

    <div class="min-h-screen h-full flex flex-col after:flex-1">


        <!-- Header -->

        <!-- Progress bar -->
        <div class="px-4 py-8">
            <div class="max-w-md mx-auto">

                <h1 class="text-3xl text-slate-800 font-bold mb-6">Website information ✨</h1>
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
                <form action="{{route('onboarding.other-data-sources-web.create', ['id'=> request()->route('id')])}}" method="POST">
                    @csrf
                    <div class="space-y-4 mb-8">
                        <!-- Company Name -->
                        <div>
                            <label class="block text-sm font-medium mb-1" for="website-name">Website url <span
                                    class="text-rose-500">*</span></label>
                            <input id="website-name" class="form-input w-full" type="text" name="website" required>
                        </div>

                        <div class="flex items-center justify-between space-x-6 mb-8">
                            <div>
                                <div class="font-medium text-slate-800 text-sm mb-1">Just to make sure we are on the
                                    same page 🫶
                                </div>
                                <div class="text-xs">
                                    Sometimes, we might face challenges when trying to crawl certain websites, especially the ones built using JavaScript (Single-Page Applications). However, we're currently working on adding headless browsing to our system so that we can support all kinds of websites.
                                </div>
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

@endsection
