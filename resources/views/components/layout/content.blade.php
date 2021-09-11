@extends(config('realestatelaravel.template'))

@if (config('realestatelaravel.use_template'))

@section(config('realestatelaravel.section_content'))
{{ $slot }}
@endsection

@else
@section(config('realestatelaravel.section_content'))
<div id="content-realestate">
    {{ $slot }}
    <re-container-toast id="container-toast-master"></re-container-toast>
</div>
@endsection
@endif