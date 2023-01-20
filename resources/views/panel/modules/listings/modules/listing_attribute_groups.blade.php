<div class="accordion accordion-light  accordion-toggle-arrow" id="accordionExample5">
    @foreach ($attribute_groups as $key => $attribute_group)
    <div class="card">
        <div class="card-header" id="heading{{ $attribute_group->ag_guid }}">
            <div class="card-title {{ Session::has('successUpdateAttributeGroup'. $attribute_group->ag_guid) ? '' :  (Session::has('successUpdateAttributeGroup') ? 'collapsed' : ($key==0 ? '' : 'collapsed')) }}"
                data-toggle="collapse" data-target="#collapse{{ $attribute_group->ag_guid }}">
                {{ $attribute_group->name_en }}
            </div>
        </div>
        <div id="collapse{{ $attribute_group->ag_guid }}"
            class="collapse {{ Session::has('successUpdateAttributeGroup'. $attribute_group->ag_guid) ? 'show' : (Session::has('successUpdateAttributeGroup') ? '' :($key==0 ? 'show' : '')) }}"
            data-parent="#accordionExample5">
            <div class="card-body">
                <form action="{{ route('admin.listings.attribute_update') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-5">
                        @foreach ($attribute_group->attribute_info as $attribute)
                        <div class="col-lg-3 col-sm-3">
                            <div class="form-group">
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-danger">
                                        <input {{ $attribute->checked ? 'checked' : '' }} value="{{
                                        $attribute->attribute_guid }}" type="checkbox"
                                        name="attributes_guid[]" />
                                        <span></span>
                                        {{ $attribute->name_en }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <input name="ag_guid" type="hidden" value="{{ $attribute_group->ag_guid }}">
                        <input name="listing_guid" type="hidden" value="{{ $listing->listing_guid }}">
                        <button type="submit" class="btn btn-primary mr-2">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
