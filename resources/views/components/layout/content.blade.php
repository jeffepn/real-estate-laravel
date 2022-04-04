@php
$useTemplate = config('realestatelaravel.use_template');
$template = $useTemplate ? 'jprealestate::layout.template' : config('realestatelaravel.template');
@endphp

@extends($template)

@if ($useTemplate)

@section('content')
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
