<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="base-url" content="{{ url('') }}">
        <meta name="lang" content="{{ app()->getLocale() }}">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>TGN Batches - @yield('title') </title>

        <link href="{{ asset('vendor/ajtarragona/css/tbatches-backend.css') }}" rel="stylesheet">
        <link href="{{ asset('vendor/ajtarragona/css/tbatches.css') }}" rel="stylesheet">
        
        @yield('css')
	    @yield('meta')
	    @yield('head-js')

    </head>
    <body class="h-100 overflow-hidden">
        <div class="h-100">
            @yield('body')
     
        </div>

        @yield('pre-js')
        <script src="{{ asset('vendor/ajtarragona/js/bootstrap.js')}}" language="JavaScript"></script>
        <script src="{{ asset('vendor/ajtarragona/js/tbatches.js')}}" language="JavaScript"></script>
        <script src="{{ asset('vendor/ajtarragona/js/tbatches-backend.js')}}" language="JavaScript"></script>
        @yield('js')
        
    </body>

</html>
