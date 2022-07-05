@content
@isset($propertyId)
<re-create-or-edit-projects id="{{$propertyId}}" tab="{{$tab}}"></re-create-or-edit-projects>
@endisset

@empty($propertyId)
<re-create-or-edit-projects tab="{{$tab}}"></re-create-or-edit-projects>
@endempty
@endcontent
