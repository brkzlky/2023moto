<div class="card-header p-0">
    <div class="row">
        <div class="col-lg-6 col-sm-6">
            <div class="card-title">
                <form action="{{ route('admin.attribute_groups.attribute.add') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">

                        <div class="col-lg-9 col-xl-9">
                            <select style="width: 100%" class="form-control select2" id="kt_select2_3"
                                name="attr_guid[]" multiple="multiple">
                                @foreach ($attributes as $attribute)

                                <option value="{{ $attribute->attribute_guid }}">
                                    {{ $attribute->name_en }}
                                </option>
                                @endforeach
                            </select>
                            <input name="ag_guid" type="hidden" value="{{ $attribute_group->ag_guid }}">
                        </div>
                        <div class="col-lg-3 col-xl-3">
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary px-6 font-weight-bold">Add</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-6 col-sm-6">
            <div class="card-title">
                <form action="" method="GET" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-9 col-sm-9">
                            <div class="input-icon">
                                <input name="name_en_attribute" type="text" class="form-control"
                                    placeholder="Search..." />
                                <span>
                                    <i class=" flaticon2-search-1 text-muted"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-3">
                            <div>
                                <button type="submit"
                                    class="btn btn-light-primary px-6 font-weight-bold">Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@if (count($attribute_info) > 0)
<form action="{{ route('admin.attribute_groups.attribute.delete') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card-body p-0">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                    <thead>

                        <tr class="text-left">
                            <th style="max-width: 40px">Id</th>
                            <th style="min-width: 140px">Name(EN)</th>
                            <th style="min-width: 150px">Name(AR)</th>

                        </tr>

                    </thead>
                    <tbody>

                        @foreach ($attribute_info as $attribute)
                        <tr>
                            <td>
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-danger">
                                        <input value="{{ $attribute->attribute_guid }}" type="checkbox"
                                            name="attr_guid[]" />
                                        <span></span>
                                    </label>
                                </div>
                            </td>
                            <td>
                                <span class="text-dark-75 d-block font-size-lg"><a
                                        href="{{ route('admin.attributes.detail',$attribute->attribute_guid) }}"
                                        target="_blank">{{
                                        $attribute->name_en }}</a></span>
                            </td>
                            <td>
                                <span class="text-dark-75 d-block font-size-lg"><a
                                        href="{{ route('admin.attributes.detail',$attribute->attribute_guid) }}"
                                        target="_blank">{{
                                        $attribute->name_ar }}</a></span>
                            </td>
                        </tr>
                        @endforeach

                        <input name="ag_guid" type="hidden" value="{{ $attribute_group->ag_guid }}">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between">
        <div>
            <input name="ag_guid" type="hidden" value="{{ $attribute_group->ag_guid }}">
            <button type="submit" class="btn btn-danger">Delete</button>
        </div>
        @if($attribute_info->lastPage() > 1)
        {{
        $attribute_info->appends(request()->input())->links('panel.modules.global.paginator',['paginator'=>$attribute_info])
        }}
        @endif
    </div>
</form>

@else
<br />
<span class="text-dark-75 d-block font-size-lg text-lg-center">
    No attributes is attached to this attribute group</span>
<br />
@endif
