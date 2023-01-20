<form action="{{ route('admin.attribute_groups.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card-body py-4">
        <div class="row mt-5">
            <div class="col-lg-4 col-sm-4">
                <div class="form-group">
                    <label>Attribute Group Name(EN)</label>
                    <input name="name_en" type="text" class="form-control" value="{{ $attribute_group->name_en }}" />
                </div>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class="form-group">
                    <label>Attribute Group Name(AR)</label>
                    <input name="name_ar" type="text" class="form-control" value="{{ $attribute_group->name_ar }}" />
                </div>
            </div>

            <div class="col-lg-4 col-sm-4">
                <div class="form-group">
                    <label>Attribute Selection Type</label>
                    <select class="form-control" id="input_type" name="input_type">
                        <option value="checkbox">Checkbox</option>
                        <option value="selectbox">Selectbox</option>
                        <option value="input">Input</option>
                        <option value="radio">Radio</option>
                        <option value="range">Range</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class="form-group">
                    <label>Statuss</label>
                    <select class="form-control" id="status" name="status">
                        <option {{ $attribute_group->status  == 1 ? 'selected' : '' }} value="1">On</option>
                        <option {{ $attribute_group->status == 0 ? 'selected' : '' }} value="0">Off</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class="form-group">
                    <label>Multiple Select</label>
                    <select class="form-control" id="multiple_select" name="multiple_select">
                        <option {{ $attribute_group->multiple_select  == 1 ? 'selected' : '' }} value="1">On</option>
                        <option {{ $attribute_group->multiple_select  == 0 ? 'selected' : '' }} value="0">Off</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class="form-group">
                    <label>Filterable</label>
                    <select class="form-control" id="filterable" name="filterable">
                        <option {{ $attribute_group->filterable  == 1 ? 'selected' : '' }} value="1">On</option>
                        <option {{ $attribute_group->filterable  == 0 ? 'selected' : '' }} value="0">Off</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-end">
        <input name="ag_guid" type="hidden" value="{{ $attribute_group->ag_guid }}">
        <button type="submit" class="btn btn-primary mr-2">Save</button>
    </div>
</form>
