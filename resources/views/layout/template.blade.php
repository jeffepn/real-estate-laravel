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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous" />
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous">
    </script>
    @stack('scriptsrealestate')
</body>

</html>
