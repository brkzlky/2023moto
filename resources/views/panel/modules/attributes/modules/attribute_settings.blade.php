<form action="{{ route('admin.attributes.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card-body py-4">
        <div class="row mt-5">

            <div class="col-lg-6 col-sm-6">
                <div class="form-group">
                    <label>Attribute Name(EN)</label>
                    <input name="name_en" type="text" class="form-control" value="{{ $attribute->name_en }}" />
                </div>
            </div>
            <div class="col-lg-6 col-sm-6">
                <div class="form-group">
                    <label>Attribute Name(AR)</label>
                    <input name="name_ar" type="text" class="form-control" value="{{ $attribute->name_ar }}" />
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-end">
        <input name="location_guid" type="hidden" value="{{ $attribute->attribute_guid }}">
        <button type="submit" class="btn btn-primary mr-2">Save</button>
    </div>
</form>
