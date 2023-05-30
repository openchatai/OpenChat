<!-- Page Intro -->
@php
    /**
     * @var \App\Models\WebsiteDataSource[] $dataSources
     */
@endphp
@foreach($dataSources as $source)
    @if(!$source->getCrawlingStatus()->isCompleted())
        <div
            class="flex flex-col col-span-full bg-white shadow-lg rounded-sm border border-slate-200">
            <div class="px-5 py-6">

                <div class="md:flex md:justify-between md:items-center">
                    <!-- Left side -->
                    <div class="flex items-center mb-4 md:mb-0">
                        <!-- Avatar -->
                        <div class="mr-4">
                            <div class="shrink-0 rounded-full mr-2 sm:mr-3 bg-rose-500 heart">
                                <svg class="w-9 h-9 fill-current text-rose-50" viewBox="0 0 36 36">
                                    <path
                                        d="M18 21a3 3 0 1 1 0-6 3 3 0 0 1 0 6Zm-4.95 3.363-.707-.707a8 8 0 0 1 0-11.312l.707-.707 1.414 1.414-.707.707a6 6 0 0 0 0 8.484l.707.707-1.414 1.414Zm9.9 0-1.414-1.414.707-.707a6 6 0 0 0 0-8.484l-.707-.707 1.414-1.414.707.707a8 8 0 0 1 0 11.312l-.707.707Z"></path>
                                </svg>
                            </div>
                        </div>
                        <!-- User info -->
                        @if($source->getCrawlingStatus()->isPending() || $source->getCrawlingStatus()->isInProgress())
                            <div>
                                <div
                                    class="text-3xl font-bold text-emerald-500">{{$source->getCrawlingProgress()}}
                                    % ⌛
                                </div>
                                <div class="mb-2">We are <strong
                                        class="font-medium text-slate-800"> still crawling your
                                        website </strong> and your chatbot will be ready once we
                                    are done (and we will email you too), <a
                                        href="{{route('chatbot.settings-data', ['id' => request()->route('id')])}}">click
                                        here to open all data sources</a>.
                                </div>
                            </div>
                        @endif

                        @if($source->getCrawlingStatus()->isFailed())
                            <div>
                                <div class="text-3xl font-bold text-red-500">❌</div>
                                <div class="mb-2">We are <strong
                                        class="font-medium text-slate-800"> unable to crawl your
                                        website </strong> please make sure that your website is
                                    online and accessible.
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>

    @endif

    @if($source->getCrawlingStatus()->isCompleted() && $source->getCreatedAt()->diffInMinutes(now()) <= 3)
        <div
            class="flex flex-col col-span-full ">
            <div class="px-5 pt-5 pb-0">

                <div x-show="open" x-data="{ open: true }">
                    <div class="px-4 py-2 rounded-sm text-sm bg-emerald-500 text-white">
                        <div class="flex w-full justify-between items-start">
                            <a href="{{route('chatbot.settings-data', ['id' => request()->route('id')])}}">

                                <div class="flex">
                                    <svg
                                        class="w-4 h-4 shrink-0 fill-current opacity-80 mt-[3px] mr-3"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8zM7 11.4L3.6 8 5 6.6l2 2 4-4L12.4 6 7 11.4z"></path>
                                    </svg>
                                    <div class="font-medium">
                                        🥳 We are done crawling your website! Your chatbot is
                                        ready to go with {{ $source->getRootUrl() }}
                                        knowledge.
                                    </div>
                                </div>

                            </a>

                            <button class="opacity-70 hover:opacity-80 ml-3 mt-[3px]"
                                    @click="open = false">
                                <div class="sr-only">Close</div>
                                <svg class="w-4 h-4 fill-current">
                                    <path
                                        d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    </div>
            </div>
        </div>
    @endif

@endforeach
