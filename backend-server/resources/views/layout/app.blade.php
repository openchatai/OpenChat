<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>OpenChat - Dashboard</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link href="/dashboard/css/vendors/flatpickr.min.css" rel="stylesheet">
    <link href="/dashboard/style.css" rel="stylesheet">
</head>

<body
    class="font-inter antialiased bg-slate-100 text-slate-600"
    :class="{ 'sidebar-expanded': sidebarExpanded }"
    x-data="{ sidebarOpen: false, sidebarExpanded: localStorage.getItem('sidebar-expanded') == 'true' }"
    x-init="$watch('sidebarExpanded', value => localStorage.setItem('sidebar-expanded', value))"
>

<script>
    if (localStorage.getItem('sidebar-expanded') == 'true') {
        document.querySelector('body').classList.add('sidebar-expanded');
    } else {
        document.querySelector('body').classList.remove('sidebar-expanded');
    }
</script>

<!-- Page wrapper -->
<div class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <div>
        <!-- Sidebar backdrop (mobile only) -->
        <div
            class="fixed inset-0 bg-slate-900 bg-opacity-30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
            :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'"
            aria-hidden="true"
            x-cloak
        ></div>

        <!-- Sidebar -->
{{--        @include('layout.sidebar')--}}
    </div>

    <!-- Content area -->
    <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">

        <!-- Site header -->
        @if(!isset($doNotShowTopHeader))
            @include('layout.header')
        @endif

        <main>
            @yield('content')
        </main>

    </div>

</div>

<script src="/dashboard/js/vendors/alpinejs.min.js" defer></script>
<script src="/dashboard/js/vendors/chart.js"></script>
<script src="/dashboard/js/vendors/moment.js"></script>
<script src="/dashboard/js/vendors/chartjs-adapter-moment.js"></script>
<script src="/dashboard/js/dashboard-charts.js"></script>
<script src="/dashboard/js/vendors/flatpickr.js"></script>
<script src="/dashboard/js/flatpickr-init.js"></script>


@yield('scripts')
</body>

</html>
