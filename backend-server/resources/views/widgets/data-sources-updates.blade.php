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
                                <div class="mb-2">We are currently in the process of crawling your website to prepare
                                    your chatbot. Once the crawling is complete, your chatbot will be ready. To access all data sources, please <a
                                        class="underline"
                                        href="{{route('chatbot.settings-data', ['id' => request()->route('id')])}}">click
                                        here</a>.
                                </div>
                            </div>
                        @endif

                        @if($source->getCrawlingStatus()->isFailed())
                            <div>
                                <div class="text-3xl font-bold text-red-500">❌</div>
                                <div class="mb-2">
                                    We're sorry, <strong>but we're having trouble crawling your website
                                        ({{$source->getRootUrl()}}). </strong> Please double-check that your website is
                                    up and running.
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

                        </div>
                    </div>
                    </div>
            </div>
        </div>
    @endif

@endforeach

@foreach($pdfDataSources as $source)
    @if(!$source->getStatus()->isSuccessful())
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
                        @if($source->getStatus()->isPending())
                            <div>
                                <div class="text-3xl font-bold text-emerald-500"> ⌛
                                </div>
                                <div class="mb-2">We are currently in the process of processing your PDF files. It may
                                    take some time for the bot to ingest all the data. You can <a class="underline"
                                                                                                  href="{{route('chatbot.settings-data', ['id' => request()->route('id')])}}">click
                                        here</a> to open all data sources.
                                </div>
                            </div>
                        @endif

                        @if($source->getStatus()->isFailed())
                            <div>
                                <div class="text-3xl font-bold text-red-500">❌</div>
                                <div class="mb-2">We encountered a problem while training the bot on your PDF files. Our
                                    team has been alerted, and we will investigate the issue.
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>

    @endif



@endforeach
