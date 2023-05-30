<div
    id="sidebar"
    class="flex flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-screen overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 lg:w-20 lg:sidebar-expanded:!w-64 2xl:!w-64 shrink-0 bg-slate-800 p-4 transition-all duration-200 ease-in-out"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-64'"
    @click.outside="sidebarOpen = false"
    @keydown.escape.window="sidebarOpen = false"
    x-cloak="lg"
    style="
    background: url('https://assets.website-files.com/6315e1e470d137f0d47520d5/633c41e6be56537436fa151f_Footer-Three-Bg.png');
    background-size: cover;
    "
>

    <!-- Sidebar header -->
    <div class="flex justify-between mb-10 pr-3 sm:px-2">
        <!-- Close button -->
        <button class="lg:hidden text-slate-500 hover:text-slate-400" @click.stop="sidebarOpen = !sidebarOpen" aria-controls="sidebar" :aria-expanded="sidebarOpen">
            <span class="sr-only">Close sidebar</span>
            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z" />
            </svg>
        </button>
        <!-- Logo -->
        <a class="block" href="index.html">
            <svg width="32" height="32" viewBox="0 0 32 32">
                <defs>
                    <linearGradient x1="28.538%" y1="20.229%" x2="100%" y2="108.156%" id="logo-a">
                        <stop stop-color="#A5B4FC" stop-opacity="0" offset="0%" />
                        <stop stop-color="#A5B4FC" offset="100%" />
                    </linearGradient>
                    <linearGradient x1="88.638%" y1="29.267%" x2="22.42%" y2="100%" id="logo-b">
                        <stop stop-color="#38BDF8" stop-opacity="0" offset="0%" />
                        <stop stop-color="#38BDF8" offset="100%" />
                    </linearGradient>
                </defs>
                <rect fill="#6366F1" width="32" height="32" rx="16" />
                <path d="M18.277.16C26.035 1.267 32 7.938 32 16c0 8.837-7.163 16-16 16a15.937 15.937 0 01-10.426-3.863L18.277.161z" fill="#4F46E5" />
                <path d="M7.404 2.503l18.339 26.19A15.93 15.93 0 0116 32C7.163 32 0 24.837 0 16 0 10.327 2.952 5.344 7.404 2.503z" fill="url(#logo-a)" />
                <path d="M2.223 24.14L29.777 7.86A15.926 15.926 0 0132 16c0 8.837-7.163 16-16 16-5.864 0-10.991-3.154-13.777-7.86z" fill="url(#logo-b)" />
            </svg>
        </a>
    </div>

    <!-- Links -->
    <div class="space-y-8">
        <!-- Pages group -->
        <div>
            <h3 class="text-xs uppercase text-slate-500 font-semibold pl-3">
                <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6" aria-hidden="true">•••</span>
                <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Pages</span>
            </h3>
            <ul class="mt-3">
                <!-- Dashboard -->
                <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0">
                    <a class="block text-slate-200 hover:text-white truncate transition duration-150" href="/">
                        <div class="flex items-center">
                            <svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24">
                                <path class="fill-current text-slate-600" d="M16 13v4H8v-4H0l3-9h18l3 9h-8Z" />
                                <path class="fill-current text-slate-400" d="m23.72 12 .229.686A.984.984 0 0 1 24 13v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1v-8c0-.107.017-.213.051-.314L.28 12H8v4h8v-4H23.72ZM13 0v7h3l-4 5-4-5h3V0h2Z" />
                            </svg>
                            <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Dashboard</span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
        <!-- More group -->
        <div>
            <h3 class="text-xs uppercase text-slate-500 font-semibold pl-3">
                <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6" aria-hidden="true">•••</span>
                <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">More</span>
            </h3>
            <ul class="mt-3">
                <!-- Authentication -->
                <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0" x-data="{ open: false }">
                    <a class="block text-slate-200 hover:text-white transition duration-150" :class="open && 'hover:text-slate-200'" href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24">
                                    <path class="fill-current text-slate-600" d="M8.07 16H10V8H8.07a8 8 0 110 8z" />
                                    <path class="fill-current text-slate-400" d="M15 12L8 6v5H0v2h8v5z" />
                                </svg>
                                <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Developers</span>
                            </div>
                            <!-- Icon -->
                            <div class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400" :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                    <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                </svg>
                            </div>
                        </div>
                    </a>
                    <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                        <ul class="pl-9 mt-1 hidden" :class="open ? '!block' : 'hidden'">
                            <li class="mb-1 last:mb-0">
                                <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate" href="signin.html">
                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Webhooks</span>
                                </a>
                            </li>
                            <li class="mb-1 last:mb-0">
                                <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate" href="signup.html">
                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Authentication keys</span>
                                </a>
                            </li>
                            <li class="mb-1 last:mb-0">
                                <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate" href="reset-password.html">
                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Logs</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Onboarding -->
                <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0" x-data="{ open: false }">
                    <a class="block text-slate-200 hover:text-white transition duration-150" :class="open && 'hover:text-slate-200'" href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24">
                                    <path class="fill-current text-slate-600" d="M19 5h1v14h-2V7.414L5.707 19.707 5 19H4V5h2v11.586L18.293 4.293 19 5Z" />
                                    <path class="fill-current text-slate-400" d="M5 9a4 4 0 1 1 0-8 4 4 0 0 1 0 8Zm14 0a4 4 0 1 1 0-8 4 4 0 0 1 0 8ZM5 23a4 4 0 1 1 0-8 4 4 0 0 1 0 8Zm14 0a4 4 0 1 1 0-8 4 4 0 0 1 0 8Z" />
                                </svg>
                                <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">API Documentation</span>
                            </div>
                            <!-- Icon -->
                            <div class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400" :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                    <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                </svg>
                            </div>
                        </div>
                    </a>
                    <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                        <ul class="pl-9 mt-1 hidden" :class="open ? '!block' : 'hidden'">
                            <li class="mb-1 last:mb-0">
                                <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate" href="onboarding-01.html">
                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Step 1</span>
                                </a>
                            </li>
                            <li class="mb-1 last:mb-0">
                                <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate" href="onboarding-02.html">
                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Step 2</span>
                                </a>
                            </li>
                            <li class="mb-1 last:mb-0">
                                <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate" href="onboarding-03.html">
                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Step 3</span>
                                </a>
                            </li>
                            <li class="mb-1 last:mb-0">
                                <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate" href="onboarding-04.html">
                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Step 4</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- Components -->
                <li class="px-3 py-2 rounded-sm mb-0.5 last:mb-0" x-data="{ open: false }">
                    <a class="block text-slate-200 hover:text-white transition duration-150" :class="open && 'hover:text-slate-200'" href="#0" @click.prevent="sidebarExpanded ? open = !open : sidebarExpanded = true">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="shrink-0 h-6 w-6" viewBox="0 0 24 24">
                                    <circle class="fill-current text-slate-600" cx="16" cy="8" r="8" />
                                    <circle class="fill-current text-slate-400" cx="8" cy="16" r="8" />
                                </svg>
                                <span class="text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Help</span>
                            </div>
                            <!-- Icon -->
                            <div class="flex shrink-0 ml-2 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">
                                <svg class="w-3 h-3 shrink-0 ml-1 fill-current text-slate-400" :class="open ? 'rotate-180' : 'rotate-0'" viewBox="0 0 12 12">
                                    <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
                                </svg>
                            </div>
                        </div>
                    </a>
                    <div class="lg:hidden lg:sidebar-expanded:block 2xl:block">
                        <ul class="pl-9 mt-1 hidden" :class="open ? '!block' : 'hidden'">
                            <li class="mb-1 last:mb-0">
                                <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate" href="component-button.html">
                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Button</span>
                                </a>
                            </li>
                            <li class="mb-1 last:mb-0">
                                <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate" href="component-form.html">
                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Input Form</span>
                                </a>
                            </li>
                            <li class="mb-1 last:mb-0">
                                <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate" href="component-dropdown.html">
                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Dropdown</span>
                                </a>
                            </li>
                            <li class="mb-1 last:mb-0">
                                <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate" href="component-alert.html">
                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Alert & Banner</span>
                                </a>
                            </li>
                            <li class="mb-1 last:mb-0">
                                <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate" href="component-modal.html">
                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Modal</span>
                                </a>
                            </li>
                            <li class="mb-1 last:mb-0">
                                <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate" href="component-pagination.html">
                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Pagination</span>
                                </a>
                            </li>
                            <li class="mb-1 last:mb-0">
                                <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate" href="component-tabs.html">
                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Tabs</span>
                                </a>
                            </li>
                            <li class="mb-1 last:mb-0">
                                <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate" href="component-breadcrumb.html">
                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Breadcrumb</span>
                                </a>
                            </li>
                            <li class="mb-1 last:mb-0">
                                <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate" href="component-badge.html">
                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Badge</span>
                                </a>
                            </li>
                            <li class="mb-1 last:mb-0">
                                <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate" href="component-avatar.html">
                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Avatar</span>
                                </a>
                            </li>
                            <li class="mb-1 last:mb-0">
                                <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate" href="component-tooltip.html">
                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Tooltip</span>
                                </a>
                            </li>
                            <li class="mb-1 last:mb-0">
                                <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate" href="component-accordion.html">
                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Accordion</span>
                                </a>
                            </li>
                            <li class="mb-1 last:mb-0">
                                <a class="block text-slate-400 hover:text-slate-200 transition duration-150 truncate" href="component-icons.html">
                                    <span class="text-sm font-medium lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Icons</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <!-- Expand / collapse button -->
    <div class="pt-3 hidden lg:inline-flex 2xl:hidden justify-end mt-auto">
        <div class="px-3 py-2">
            <button @click="sidebarExpanded = !sidebarExpanded">
                <span class="sr-only">Expand / collapse sidebar</span>
                <svg class="w-6 h-6 fill-current sidebar-expanded:rotate-180" viewBox="0 0 24 24">
                    <path class="text-slate-400" d="M19.586 11l-5-5L16 4.586 23.414 12 16 19.414 14.586 18l5-5H7v-2z" />
                    <path class="text-slate-600" d="M3 23H1V1h2z" />
                </svg>
            </button>
        </div>
    </div>

</div>
