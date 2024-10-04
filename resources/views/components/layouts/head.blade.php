<head>
    @if(env('APP_ENV') == 'production')
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{env('GOOGLE_ANALYTICS_GTAG')}}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', '{{env('GOOGLE_ANALYTICS_GTAG')}}', {
                    'user_id': '{{auth()->guest() ? "GUEST" : auth()->user()->id}}'
                });
        </script>

        {{-- Microsoft Clarity --}}
        <script type="text/javascript">
            (function(c,l,a,r,i,t,y){
                    c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
                    t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
                    y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
                    window.clarity("set", "userId", "{{auth()->guest() ? "GUEST" : auth()->user()->id}}");
                })(window, document, "clarity", "script", "hbp3mhwtnp");
        </script>
    @endif

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' | ' . env('APP_NAME') : env('APP_NAME') }}</title>
    {{-- <script src="//cdn.tailwindcss.com"></script> --}}
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Fonts -->
    {{-- @preloadFonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..600&display=swap" rel="stylesheet">

    <!-- Styles -->
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}
    {{-- @vite('resources/css/app.css') --}}
    {{-- <script src="//cnd.tailwindcss.com"></script> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- @vite('resources/js/app.js') --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        [data-flux-button] {
            @apply bg-zinc-800 dark:bg-zinc-400 hover:bg-zinc-700 dark:hover:bg-zinc-300;
        }
    </style>
    {{-- @livewireStyles --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css"> --}}
    {{-- @lagoonStyles --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" /> --}}

    {{-- <style>
        @media print {
           .noprint {
              visibility: hidden;
           }
        }
    </style>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style> --}}

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
    {{-- @stack('custom_scripts') --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}

    {{-- ALPINE CORE INCLUDED WITH LIVEWIRE --}}
    <!-- Alpine Plugins -->
    <script defer src="https://unpkg.com/@alpinejs/ui@3.14.1-beta.0/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/@alpinejs/focus@3.14.1/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.14.1/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/@nextapps-be/livewire-sortablejs@0.4.0/dist/livewire-sortable.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/sort@3.x.x/dist/cdn.min.js"></script>

    {{-- <script defer src="https://unpkg.com/alpinejs@3.14.1/dist/cdn.min.js"></script> --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> --}}
    @fluxStyles
</head>
