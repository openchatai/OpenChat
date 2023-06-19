<div
    class="flex flex-nowrap overflow-x-scroll no-scrollbar md:block md:overflow-auto px-3 py-6 border-b md:border-b-0 md:border-r border-slate-200 min-w-60 md:space-y-3">
    <!-- Group 1 -->
    <div>
        <div class="text-xs font-semibold text-slate-400 uppercase mb-3">Bot Settings</div>
        <ul class="flex flex-nowrap md:block mr-3 md:mr-0">
            <li class="mr-0.5 md:mr-0 md:mb-0.5">
                <a class="flex items-center px-2.5 py-2 rounded whitespace-nowrap {{request()->routeIs('chatbot.settings-theme' ) ? 'bg-indigo-50' : ''}}"
                   href="{{route('chatbot.settings-theme',  ['id' => request()->route('id')])}}">
                    <svg class="w-4 h-4 shrink-0 fill-current text-slate-400  {{request()->routeIs('chatbot.settings-theme' ) ? 'text-indigo-400' : ''}} mr-2" viewBox="0 0 16 16">
                        <path
                            d="M15 4c.6 0 1 .4 1 1v10c0 .6-.4 1-1 1H3c-1.7 0-3-1.3-3-3V3c0-1.7 1.3-3 3-3h7c.6 0 1 .4 1 1v3h4zM2 3v1h7V2H3c-.6 0-1 .4-1 1zm12 11V6H2v7c0 .6.4 1 1 1h11zm-3-5h2v2h-2V9z"></path>
                    </svg>
                    <span class="text-sm font-medium  {{request()->routeIs('chatbot.settings-theme' ) ? 'text-slate-600 hover:text-slate-700 text-indigo-500' : ''}}">Try & Share</span>
                </a>
            </li>
            <li class="mr-0.5 md:mr-0 md:mb-0.5">
                <a class="flex items-center px-2.5 py-2 rounded whitespace-nowrap {{request()->routeIs('chatbot.settings' ) ? 'bg-indigo-50' : ''}}"
                   href="{{route('chatbot.settings',  ['id' => request()->route('id')])}}">
                    <svg class="w-4 h-4 shrink-0 fill-current mr-2 text-slate-400 {{request()->routeIs('chatbot.settings' ) ? 'text-indigo-400' : ''}}" viewBox="0 0 16 16">
                        <path
                            d="M12.311 9.527c-1.161-.393-1.85-.825-2.143-1.175A3.991 3.991 0 0012 5V4c0-2.206-1.794-4-4-4S4 1.794 4 4v1c0 1.406.732 2.639 1.832 3.352-.292.35-.981.782-2.142 1.175A3.942 3.942 0 001 13.26V16h14v-2.74c0-1.69-1.081-3.19-2.689-3.733zM6 4c0-1.103.897-2 2-2s2 .897 2 2v1c0 1.103-.897 2-2 2s-2-.897-2-2V4zm7 10H3v-.74c0-.831.534-1.569 1.33-1.838 1.845-.624 3-1.436 3.452-2.422h.436c.452.986 1.607 1.798 3.453 2.422A1.943 1.943 0 0113 13.26V14z"></path>
                    </svg>
                    <span
                        class="text-sm font-medium {{request()->routeIs('chatbot.settings' ) ? 'text-slate-600 hover:text-slate-700 text-indigo-500' : ''}}">General Settings</span>
                </a>
            </li>

            <li class="mr-0.5 md:mr-0 md:mb-0.5">
                <a class="flex items-center px-2.5 py-2 rounded whitespace-nowrap {{request()->routeIs('chatbot.settings-data' ) ? 'bg-indigo-50' : ''}}"
                   href="{{route('chatbot.settings-data',  ['id' => request()->route('id')])}}">
                    <svg
                        class="w-4 h-4 shrink-0 mr-2 fill-current text-slate-400  {{request()->routeIs('chatbot.settings-data' ) ? 'text-indigo-400' : ''}}"
                        viewBox="0 0 16 16">
                        <path
                            d="M3.414 2L9 7.586V16H7V8.414l-5-5V6H0V1a1 1 0 011-1h5v2H3.414zM15 0a1 1 0 011 1v5h-2V3.414l-3.172 3.172-1.414-1.414L12.586 2H10V0h5z"></path>
                    </svg>
                    <span
                        class="text-sm font-medium {{request()->routeIs('chatbot.settings-data' ) ? 'text-slate-600 hover:text-slate-700 text-indigo-500' : ''}}">Data & Knowledge</span>
                </a>
            </li>
            <li class="mr-0.5 md:mr-0 md:mb-0.5">
                <a class="flex items-center px-2.5 py-2 rounded whitespace-nowrap {{request()->routeIs('chatbot.settings-history' ) ? 'bg-indigo-50' : ''}}"
                   href="{{route('chatbot.settings-history',  ['id' => request()->route('id')])}}">
                    <svg
                        class="w-4 h-4 shrink-0 fill-current mr-2 text-slate-400 {{request()->routeIs('chatbot.settings-history' ) ? 'text-indigo-400' : ''}}"
                        viewBox="0 0 16 16">
                        <path
                            d="M14.3.3c.4-.4 1-.4 1.4 0 .4.4.4 1 0 1.4l-8 8c-.2.2-.4.3-.7.3-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l8-8zM15 7c.6 0 1 .4 1 1 0 4.4-3.6 8-8 8s-8-3.6-8-8 3.6-8 8-8c.6 0 1 .4 1 1s-.4 1-1 1C4.7 2 2 4.7 2 8s2.7 6 6 6 6-2.7 6-6c0-.6.4-1 1-1z"></path>
                    </svg>
                    <span
                        class="text-sm font-medium {{request()->routeIs('chatbot.settings-history' ) ? 'text-slate-600 hover:text-slate-700 text-indigo-500' : ''}}">History</span>
                </a>
            </li>

        </ul>
    </div>

</div>
