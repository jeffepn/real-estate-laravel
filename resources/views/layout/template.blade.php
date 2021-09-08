<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @section('title')
        {{config('app.name')}} - @yield('subtitle')
        @endsection
    </title>
    @stack('cssrealestate')
</head>

<body>
    <main id="app">
        @section('header')
        @include('jprealestate::layout.header')
        @show
        </header>
        <div id="container-master">
            <re-loading></re-loading>
            @yield('content')
        </div>
        @section('footer')
        @include('jprealestate::layout.footer')
        @show
        <re-container-toast id="container-toast-master"></re-container-toast>
    </main>
    @stack('scriptsrealestate')
</body>

</html>
