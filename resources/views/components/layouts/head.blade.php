<head>
    @if(env('APP_ENV') == 'production')
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-VZN3ZPDQC1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', 'G-VZN3ZPDQC1', {
                    'user_id': '{{auth()->guest() ? "GUEST" : auth()->user()->id}}'
                });
        </script>

        {{-- Microsoft Clarity --}}
        <script type="text/javascript">
            (function(c,l,a,r,i,t,y){
                    c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
                    t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
                    y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
                })(window, document, "clarity", "script", "hbp3mhwtnp");
        </script>
    @endif

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' | ' . env('APP_NAME') : env('APP_NAME') }}</title>

    <!-- Fonts -->
    {{-- @preloadFonts --}}

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @livewireStyles
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css"> --}}
    {{-- @lagoonStyles --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" /> --}}

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <style>
        @media print {
           .noprint {
              visibility: hidden;
           }
        }
    </style>

    <style>
        [x-cloak] {
            display: none;
        }
    </style>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> --}}
    @stack('custom_scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}
    <!-- https://www.chartjs.org/ -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js"></script>
    {{-- <script defer src="https://unpkg.com/@alpinejs/ui@3.13.7-beta.0/dist/cdn.min.js"></script> --}}

    <!-- Alpine Plugins -->
    <script defer src="https://unpkg.com/@alpinejs/ui@3.14.0-beta.0/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/@alpinejs/focus@3.14.0/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    {{-- ALPINE CORE INCLUDED WITH LIVEWIRE --}}

    {{-- <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script> --}}

    @stack('custom_styles')
</head>
