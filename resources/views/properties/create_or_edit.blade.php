@extends('jpviews::layout.template')


@section('content')
@isset($propertyId)
<re-create-or-edit-properties id="{{$propertyId}}"></re-create-or-edit-properties>
@endisset

@empty($propertyId)
<re-create-or-edit-properties></re-create-or-edit-properties>
@endempty
@endsection