@php
$useTemplate = Jeffpereira\RealEstate\Utilities\Helpers\ConfigHelper::get('use_template');
$template = $useTemplate ? 'jprealestate::layout.template' :
Jeffpereira\RealEstate\Utilities\Helpers\ConfigHelper::get('template');
@endphp

@extends($template)

@if ($useTemplate)

@section('content')
{{ $slot }}
@endsection

@else
@section(Jeffpereira\RealEstate\Utilities\Helpers\ConfigHelper::get('section_content'))
<div id="content-realestate">
    {{ $slot }}
    <re-container-toast id="container-toast-master"></re-container-toast>
</div>
@endsection
@endif