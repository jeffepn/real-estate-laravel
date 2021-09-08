@extends(config('realestatelaravel.template'))


@section(config('realestatelaravel.section_content'))
<div id="content-realestate">
    {{ $slot }}
</div>
@endsection

@push('cssrealestate')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="/assets/css/realestatelaravel.css">
@endpush

@push('scriptsrealestate')
<script src="/assets/js/realestatelaravel.js"></script>
@endpush
