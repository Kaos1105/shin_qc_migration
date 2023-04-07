<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('images/qcsystem_logo.png') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'QC Management System') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script src="{{ asset('js/jquery-ui-i18n.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/myjs.js') }}"></script>
    <script src="{{ asset('js/Chart.js') }}"></script>
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/fullcalendar.js') }}"></script>
    <script src="{{ asset('js/ja.js') }}"></script>
    <script src="{{ asset('js/jquery.datetimepicker.full.js') }}"></script>
    <script src="{{ asset('js/qa-multi-form.js') }}"></script>
    <script src="{{ asset('js/quill.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}"  type="text/css"/>
    <!-- Fonts -->
    <link href="{{asset('css/css.css?family=Nunito')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/jquery-ui.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/jquery.dataTables.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/organization-chart.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/fullcalendar.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/jquery.datetimepicker.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('css/quill.snow.css')}}" rel="stylesheet" type="text/css"/>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/qa-multi-form.css') }}" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss','resources/sass/app.css','resources/js/app.js'])
</head>

@yield('br-defined')
<body>
    @include('partials._nav')
    <div class="absolute wrapper">
        @yield('breadcrumbs')
        @yield('content')
    </div>
    @yield('scripts')
    @include('partials._footer')
</body>
</html>
