@extends('layout.app', ['title' => __('Dashboard')])
@section('content')

    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Page header -->
        <div class="mb-8">

            <!-- Title -->
            <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">{{$bot->getName()}}: data sources  ✨</h1>
            <ul class="inline-flex flex-wrap text-sm font-medium">
                <li class="flex items-center">
                    <a class="text-slate-500 hover:text-indigo-500" href="{{route('index')}}">Home</a>
                    <svg class="h-4 w-4 fill-current text-slate-400 mx-3" viewBox="0 0 16 16">
                        <path d="M6.6 13.4L5.2 12l4-4-4-4 1.4-1.4L12 8z"></path>
                    </svg>
                </li>
                <li class="flex items-center">
                    <a class="text-slate-500 hover:text-indigo-500" >Data Sources</a>
                </li>

            </ul>
        </div>

        <div class="bg-white inline-flex shadow-lg rounded-sm mb-8" style="width: 100%;">
            <div class="flex flex-col md:flex-row md:-mr-px" style="width: 100%">

                <!-- Sidebar -->
                @include('layout.sidebar-bot-page')
                <!-- Panel -->
                <div class="grow">

                    <div class="p-6 space-y-6">
                        <section>
                            <h3 class="text-xl leading-snug text-slate-800 font-bold mb-1">Data sources</h3>
                            <div class="text-sm">Currently we only support web based sources, soon we will support Pdf,
                                Text and even videos!
                            </div>
                            @php
                                /** @var \App\Models\WebsiteDataSource[] $websiteDataSources */
                            @endphp
                            @foreach($websiteDataSources as $source)
                                <div style="margin-top: 1.5rem; margin-bottom: 1.5rem">
                                    <!-- Start -->
                                    <div class="rounded-sm border border-slate-200">
                                        <div class="overflow-x-auto">
                                            <table class="table-auto w-full divide-y divide-slate-200">
                                                <!-- Table body -->
                                                <tbody class="text-sm" x-data="{ open: false }">
                                                <!-- Row -->
                                                <tr>
                                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                        <div class="flex items-center text-slate-800">
                                                            <div
                                                                class="w-10 h-10 shrink-0 flex items-center justify-center bg-slate-100 rounded-full mr-2 sm:mr-3">
                                                                <img class="rounded-full ml-1"
                                                                     src="{{$source->getIcon()}}" width="40"
                                                                     height="40" alt="User 01">
                                                            </div>
                                                            <div class="font-medium text-slate-800">
                                                                <strong>website</strong>:
                                                                {{$source->getRootUrl()}}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                        <div class="text-left font-medium text-emerald-500">
                                                            {{$source->getCrawledPages()->count()}} pages scanned
                                                        </div>
                                                    </td>
                                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">

                                                        @if($source->getCrawlingStatus()->isPending())
                                                            <div
                                                                class="inline-flex font-medium bg-amber-100 text-amber-600 rounded-full text-center px-2.5 py-0.5">
                                                                {{$source->getCrawlingStatus()->getLabel()}}
                                                            </div>
                                                        @elseif($source->getCrawlingStatus()->isInProgress())
                                                            <div
                                                                class="inline-flex font-medium bg-blue-100 text-blue-600 rounded-full text-center px-2.5 py-0.5">
                                                                {{$source->getCrawlingStatus()->getLabel()}}
                                                            </div>
                                                        @elseif($source->getCrawlingStatus()->isCompleted())
                                                            <div
                                                                class="inline-flex font-medium bg-emerald-100 text-emerald-600 rounded-full text-center px-2.5 py-0.5">
                                                                {{$source->getCrawlingStatus()->getLabel()}}
                                                            </div>
                                                        @elseif($source->getCrawlingStatus()->isFailed())
                                                            <div
                                                                class="inline-flex font-medium bg-amber-100 text-amber-600 rounded-full text-center px-2.5 py-0.5">{{ $source->getCrawlingStatus()->getLabel() }}
                                                            </div>
                                                        @endif

                                                    </td>

                                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <svg
                                                                class="w-4 h-4 fill-current text-slate-400 shrink-0 mr-2"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M4.3 4.5c1.9-1.9 5.1-1.9 7 0 .7.7 1.2 1.7 1.4 2.7l2-.3c-.2-1.5-.9-2.8-1.9-3.8C10.1.4 5.7.4 2.9 3.1L.7.9 0 7.3l6.4-.7-2.1-2.1zM15.6 8.7l-6.4.7 2.1 2.1c-1.9 1.9-5.1 1.9-7 0-.7-.7-1.2-1.7-1.4-2.7l-2 .3c.2 1.5.9 2.8 1.9 3.8 1.4 1.4 3.1 2 4.9 2 1.8 0 3.6-.7 4.9-2l2.2 2.2.8-6.4z"></path>
                                                            </svg>
                                                            <div class="cursor-not-allowed">Re-sync
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
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                        <button class="text-rose-500 hover:text-rose-600 rounded-full cursor-not-allowed" disabled>
                                                            <span class="sr-only">Delete</span>
                                                            <svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                                                                <path d="M13 15h2v6h-2zM17 15h2v6h-2z"></path>
                                                                <path
                                                                    d="M20 9c0-.6-.4-1-1-1h-6c-.6 0-1 .4-1 1v2H8v2h1v10c0 .6.4 1 1 1h12c.6 0 1-.4 1-1V13h1v-2h-4V9zm-6 1h4v1h-4v-1zm7 3v9H11v-9h10z"></path>
                                                            </svg>
                                                        </button>
                                                    </td>
                                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                                        <div class="flex items-center">
                                                            <button
                                                                class="text-slate-400 hover:text-slate-500 transform"
                                                                :class="{ 'rotate-180': open }"
                                                                @click.prevent="open = !open" :aria-expanded="open"
                                                                aria-controls="description-01" aria-expanded="false">
                                                                <span class="sr-only">Menu</span>
                                                                <svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                                                                    <path
                                                                        d="M16 20l-5.4-5.4 1.4-1.4 4 4 4-4 1.4 1.4z"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!--
                                                Example of content revealing when clicking the button on the right side:
                                                Note that you must set a "colspan" attribute on the <td> element,
                                                and it should match the number of columns in your table
                                                -->
                                                <tr id="description-01" role="region" x-show="open"
                                                    style="display: none;">
                                                    <td colspan="10" class="px-2 first:pl-5 last:pr-5 py-3">
                                                        <div class="overflow-x-auto">
                                                            <table class="table-auto w-full"
                                                                   @click.stop="$dispatch('set-transactionopen', true)">
                                                                <!-- Table header -->
                                                                <thead
                                                                    class="text-xs font-semibold uppercase text-slate-500 border-t border-b border-slate-200">
                                                                <tr>

                                                                    <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                                        <div class="font-semibold text-left">Url
                                                                        </div>
                                                                    </th>
                                                                    <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                                        <div class="font-semibold text-left">Title
                                                                        </div>
                                                                    </th>
                                                                    <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                                        <div class="font-semibold text-left">Status
                                                                        </div>
                                                                    </th>
                                                                    <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                                        <div class="font-semibold text-right">On</div>
                                                                    </th>
                                                                </tr>
                                                                </thead>
                                                                <!-- Table body -->
                                                                <tbody
                                                                    class="text-sm divide-y divide-slate-200 border-b border-slate-200">
                                                                <!-- Row -->
                                                                @php
                                                                    /** @var \App\Models\CrawledPages $page */
                                                                $pages =$source->getCrawledPages()->get();
                                                                @endphp
                                                                @foreach($pages as $page)
                                                                    <tr>
                                                                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap md:w-1/2">
                                                                            <div class="flex items-center">
                                                                                <div
                                                                                    class="w-9 h-9 shrink-0 mr-2 sm:mr-3">
                                                                                    <img class="w-9 h-9 rounded-full"
                                                                                         src="/dashboard/images/transactions-image-01.svg"
                                                                                         width="36" height="36"
                                                                                         alt="Transaction 01">
                                                                                </div>
                                                                                <div class="font-medium text-slate-800">
                                                                                    {{ \Illuminate\Support\Str::limit($page->getUrl(), 40) }}

                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                                            <div class="text-left">
                                                                                {{ \Illuminate\Support\Str::limit($page->getTitle(), 20) }}
                                                                            </div>
                                                                        </td>
                                                                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                                            <div class="text-left">
                                                                                @if($page->getStatusCode() == 200)
                                                                                    <div
                                                                                        class="text-xs inline-flex font-medium bg-slate-100 text-slate-500 rounded-full text-center px-2.5 py-1">
                                                                                        Done
                                                                                    </div>
                                                                                @else
                                                                                    <div
                                                                                        class="text-xs inline-flex font-medium bg-red-100 text-red-500 rounded-full text-center px-2.5 py-1">
                                                                                        Error
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </td>
                                                                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                                                            <div
                                                                                class="text-right text-slate-700 font-medium">
                                                                                {{$page->getCreatedAt()->format('d.m.Y H:i')}}
                                                                            </div>
                                                                        </td>
                                                                    </tr>

                                                                @endforeach
                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- End -->
                                </div>
                            @endforeach


                            <hr style="margin-top: 2rem; margin-bottom: 2rem">
                            @php
                                /** @var \App\Models\PdfDataSource[] $pdfDataSources */
                            @endphp
                            @foreach($pdfDataSources as $source)
                                <div style="margin-top: 1.5rem; margin-bottom: 1.5rem">
                                    <!-- Start -->
                                    <div class="rounded-sm border border-slate-200">
                                        <div class="overflow-x-auto">
                                            <table class="table-auto w-full divide-y divide-slate-200">
                                                <!-- Table body -->
                                                <tbody class="text-sm" x-data="{ open: false }">
                                                <!-- Row -->
                                                <tr>
                                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                        <div class="flex items-center text-slate-800">
                                                            <div
                                                                class="w-10 h-10 shrink-0 flex items-center justify-center bg-slate-100 rounded-full mr-2 sm:mr-3">
                                                                <img class="rounded-full ml-1"
                                                                     src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQqqNpSZu2FoShbhK1F_9oUZY6cGuZ9OlWBeA&usqp=CAU"
                                                                     width="40"
                                                                     height="40" alt="User 01">
                                                            </div>
                                                            <div class="font-medium text-slate-800">
                                                                <strong>Bulk Upload of Pdf files</strong>

                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                        <div class="text-left font-medium text-emerald-500">
                                                            {{count($source->getFiles())}} files scanned
                                                        </div>
                                                    </td>
                                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                        @if($source->getStatus()->isPending())
                                                            <div
                                                                class="inline-flex font-medium bg-blue-100 text-blue-600 rounded-full text-center px-2.5 py-0.5">
                                                                {{$source->getStatus()->getLabel()}}
                                                            </div>
                                                        @elseif($source->getStatus()->isSuccessful())
                                                            <div
                                                                class="inline-flex font-medium bg-emerald-100 text-emerald-600 rounded-full text-center px-2.5 py-0.5">
                                                                {{ $source->getStatus()->getLabel() }}
                                                            </div>

                                                        @elseif($source->getStatus()->isFailed())
                                                            <div
                                                                class="inline-flex font-medium bg-amber-100 text-amber-600 rounded-full text-center px-2.5 py-0.5">{{ $source->getStatus()->getLabel() }}
                                                            </div>
                                                        @endif
                                                    </td>

                                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <svg
                                                                class="w-4 h-4 fill-current text-slate-400 shrink-0 mr-2"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M4.3 4.5c1.9-1.9 5.1-1.9 7 0 .7.7 1.2 1.7 1.4 2.7l2-.3c-.2-1.5-.9-2.8-1.9-3.8C10.1.4 5.7.4 2.9 3.1L.7.9 0 7.3l6.4-.7-2.1-2.1zM15.6 8.7l-6.4.7 2.1 2.1c-1.9 1.9-5.1 1.9-7 0-.7-.7-1.2-1.7-1.4-2.7l-2 .3c.2 1.5.9 2.8 1.9 3.8 1.4 1.4 3.1 2 4.9 2 1.8 0 3.6-.7 4.9-2l2.2 2.2.8-6.4z"></path>
                                                            </svg>
                                                            <div class="cursor-not-allowed">Re-sync
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
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                        <button
                                                            class="text-rose-500 hover:text-rose-600 rounded-full cursor-not-allowed"
                                                            disabled>
                                                            <span class="sr-only">Delete</span>
                                                            <svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                                                                <path d="M13 15h2v6h-2zM17 15h2v6h-2z"></path>
                                                                <path
                                                                    d="M20 9c0-.6-.4-1-1-1h-6c-.6 0-1 .4-1 1v2H8v2h1v10c0 .6.4 1 1 1h12c.6 0 1-.4 1-1V13h1v-2h-4V9zm-6 1h4v1h-4v-1zm7 3v9H11v-9h10z"></path>
                                                            </svg>
                                                        </button>
                                                    </td>

                                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                                        <div class="flex items-center">
                                                            <button
                                                                class="text-slate-400 hover:text-slate-500 transform"
                                                                :class="{ 'rotate-180': open }"
                                                                @click.prevent="open = !open" :aria-expanded="open"
                                                                aria-controls="description-01" aria-expanded="false">
                                                                <span class="sr-only">Menu</span>
                                                                <svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                                                                    <path
                                                                        d="M16 20l-5.4-5.4 1.4-1.4 4 4 4-4 1.4 1.4z"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </td>

                                                </tr>
                                                <!--
                                                Example of content revealing when clicking the button on the right side:
                                                Note that you must set a "colspan" attribute on the <td> element,
                                                and it should match the number of columns in your table
                                                -->

                                                <tr id="description-01" role="region" x-show="open"
                                                    style="display: none;">
                                                    <td colspan="10" class="px-2 first:pl-5 last:pr-5 py-3">
                                                        <div class="overflow-x-auto">
                                                            <table class="table-auto w-full"
                                                                   @click.stop="$dispatch('set-transactionopen', true)">
                                                                <!-- Table header -->
                                                                <thead
                                                                    class="text-xs font-semibold uppercase text-slate-500 border-t border-b border-slate-200">
                                                                <tr>

                                                                    <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                                        <div class="font-semibold text-left">Name
                                                                        </div>
                                                                    </th>
                                                                    <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                                        <div class="font-semibold text-left">Download
                                                                            URL
                                                                        </div>
                                                                    </th>
                                                                    <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                                        <div class="font-semibold text-left">Status
                                                                        </div>
                                                                    </th>
                                                                    <th class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                                        <div class="font-semibold text-right">On</div>
                                                                    </th>
                                                                </tr>
                                                                </thead>
                                                                <!-- Table body -->
                                                                <tbody
                                                                    class="text-sm divide-y divide-slate-200 border-b border-slate-200">

                                                                @foreach($source->getFiles() as $file)
                                                                    <tr>
                                                                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap md:w-1/2">
                                                                            <div class="flex items-center">
                                                                                <div
                                                                                    class="w-9 h-9 shrink-0 mr-2 sm:mr-3">
                                                                                    <img class="w-9 h-9 rounded-full"
                                                                                         src="/dashboard/images/transactions-image-01.svg"
                                                                                         width="36" height="36"
                                                                                         alt="Transaction 01">
                                                                                </div>
                                                                                <div class="font-medium text-slate-800">
                                                                                    pdf file

                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                                            <div class="text-left">
                                                                                <a href=" {{$file}}">download</a>
                                                                            </div>
                                                                        </td>

                                                                        <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
                                                                            <div
                                                                                class="text-right text-slate-700 font-medium">
                                                                                {{$source->getCreatedAt()->format('d.m.Y H:i')}}
                                                                            </div>
                                                                        </td>
                                                                    </tr>

                                                                @endforeach
                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- End -->
                                </div>
                            @endforeach


                            <hr style="margin-top: 2rem; margin-bottom: 2rem">
                            @php
                                /** @var \App\Models\CodebaseDataSource[] $codebaseDataSources */
                            @endphp
                            @foreach($codebaseDataSources as $source)
                                <div style="margin-top: 1.5rem; margin-bottom: 1.5rem">
                                    <!-- Start -->
                                    <div class="rounded-sm border border-slate-200">
                                        <div class="overflow-x-auto">
                                            <table class="table-auto w-full divide-y divide-slate-200">
                                                <!-- Table body -->
                                                <tbody class="text-sm" x-data="{ open: false }">
                                                <!-- Row -->
                                                <tr>
                                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                        <div class="flex items-center text-slate-800">
                                                            <div
                                                                class="w-10 h-10 shrink-0 flex items-center justify-center bg-slate-100 rounded-full mr-2 sm:mr-3">
                                                                <img class="rounded-full ml-1"
                                                                     src="https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png"
                                                                     width="40"
                                                                     height="40" alt="User 01">
                                                            </div>
                                                            <div class="font-medium text-slate-800">
                                                                <strong>Codebase ({{$source->getRepository()}})</strong>

                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                        @if($source->getCreatedAt()->diffInMinutes(now()) <= 3)
                                                            <div
                                                                class="inline-flex font-medium bg-blue-100 text-blue-600 rounded-full text-center px-2.5 py-0.5">
                                                                In progress (eta: {{$source->getCreatedAt()->addMinutes(3)->diffForHumans()}})
                                                            </div>
                                                        @else
                                                            <div
                                                                class="inline-flex font-medium bg-emerald-100 text-emerald-600 rounded-full text-center px-2.5 py-0.5">
                                                                Completed
                                                            </div>
                                                        @endif
                                                    </td>

                                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <svg
                                                                class="w-4 h-4 fill-current text-slate-400 shrink-0 mr-2"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M4.3 4.5c1.9-1.9 5.1-1.9 7 0 .7.7 1.2 1.7 1.4 2.7l2-.3c-.2-1.5-.9-2.8-1.9-3.8C10.1.4 5.7.4 2.9 3.1L.7.9 0 7.3l6.4-.7-2.1-2.1zM15.6 8.7l-6.4.7 2.1 2.1c-1.9 1.9-5.1 1.9-7 0-.7-.7-1.2-1.7-1.4-2.7l-2 .3c.2 1.5.9 2.8 1.9 3.8 1.4 1.4 3.1 2 4.9 2 1.8 0 3.6-.7 4.9-2l2.2 2.2.8-6.4z"></path>
                                                            </svg>
                                                            <div class="cursor-not-allowed">Re-sync
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
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
                                                        <button
                                                            class="text-rose-500 hover:text-rose-600 rounded-full cursor-not-allowed"
                                                            disabled>
                                                            <span class="sr-only">Delete</span>
                                                            <svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                                                                <path d="M13 15h2v6h-2zM17 15h2v6h-2z"></path>
                                                                <path
                                                                    d="M20 9c0-.6-.4-1-1-1h-6c-.6 0-1 .4-1 1v2H8v2h1v10c0 .6.4 1 1 1h12c.6 0 1-.4 1-1V13h1v-2h-4V9zm-6 1h4v1h-4v-1zm7 3v9H11v-9h10z"></path>
                                                            </svg>
                                                        </button>
                                                    </td>


                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- End -->
                                </div>
                            @endforeach
                            <section>
                                <div
                                    class="px-5 py-3 bg-indigo-50 border border-indigo-100 rounded-sm text-center xl:text-left xl:flex xl:flex-wrap xl:justify-between xl:items-center">
                                    <div class="text-slate-800 font-semibold mb-2 xl:mb-0">Want to add new sources?
                                    </div>

                                    <div class="m-1.5">
                                        <!-- Start -->
                                        <div x-data="{ modalOpen: false }">
                                            <button class="btn bg-indigo-500 hover:bg-indigo-600 text-white"
                                                    @click.prevent="modalOpen = true" aria-controls="plan-modal">Add new
                                                data source
                                            </button>
                                            <!-- Modal backdrop -->
                                            <div
                                                class="fixed inset-0 bg-slate-900 bg-opacity-30 z-50 transition-opacity"
                                                x-show="modalOpen" x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition ease-out duration-100"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0" aria-hidden="true"
                                                style="display: none;"></div>
                                            <!-- Modal dialog -->
                                            <div id="plan-modal" class="fixed inset-0 z-50 overflow-hidden flex items-center my-4 justify-center px-4 sm:px-6" role="dialog" aria-modal="true" x-show="modalOpen" x-transition:enter="transition ease-in-out duration-200" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in-out duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4" style="display: none;">
                                                <div class="bg-white rounded shadow-lg overflow-auto max-w-lg w-full max-h-full" @click.outside="modalOpen = false" @keydown.escape.window="modalOpen = false">
                                                    <!-- Modal header -->
                                                    <div class="px-5 py-3 border-b border-slate-200">
                                                        <div class="flex justify-between items-center">
                                                            <div class="font-semibold text-slate-800">Add new data source</div>
                                                            <button class="text-slate-400 hover:text-slate-500" @click="modalOpen = false">
                                                                <div class="sr-only">Close</div>
                                                                <svg class="w-4 h-4 fill-current">
                                                                    <path d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <!-- Modal content -->
                                                    <div class="px-5 pt-4 pb-1">
                                                        <div class="text-sm">
                                                            <div class="mb-4">When you add a new data source, we will train our AI on this data, the results is that your chatbot should be able to answer questions from this data & knowldge</div>
                                                            <!-- Options -->
                                                            <ul class="space-y-2 sm:flex sm:space-y-0 sm:space-x-2 lg:space-y-2 lg:space-x-0 lg:flex-col mb-4">
                                                                <li>
                                                                    <button onclick="window.location.href='{{ route('onboarding.other-data-sources-web', ['id' => request()->route('id')]) }}'"
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
                                                                        onclick="window.location.href = '{{ route('onboarding.other-data-sources-pdf', ['id' => request()->route('id')]) }}'"
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
                                                                        class="w-full h-full text-left py-3 px-4 rounded bg-white border border-slate-200 hover:border-slate-300 shadow-sm duration-150 ease-in-out">
                                                                        <div class="flex flex-wrap items-center justify-between mb-0.5">
                                                                            <span class="font-semibold text-slate-800">Confluence</span>
                                                                        </div>
                                                                        <div class="text-sm">Automatically extract data from your Confluence from your
                                                                            workspace
                                                                        </div>
                                                                    </button>
                                                                </li>
                                                            </ul>
                                                            <div class="text-xs text-slate-500 mb-6">🔥 We are working on many other data sources, such as Notion, Confluence, Word Documents and many more.</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End -->
                                    </div>
                                </div>
                            </section>

                        </section>

                    </div>
                    <!-- Business Profile -->
                </div>

            </div>
        </div>

    </div>
@endsection

@section('scripts')
@endsection
