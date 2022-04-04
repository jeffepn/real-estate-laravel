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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
        integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @realestatelaravelStyles
</head>

<body>
    <main id="content-realestate">
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
    @realestatelaravelScripts
</body>

</html>
