@content
@isset($propertyId)
<re-create-or-edit-properties id="{{$propertyId}}" tab="{{$tab}}"></re-create-or-edit-properties>
@endisset

@empty($propertyId)
<re-create-or-edit-properties tab="{{$tab}}"></re-create-or-edit-properties>
@endempty
@endcontent
