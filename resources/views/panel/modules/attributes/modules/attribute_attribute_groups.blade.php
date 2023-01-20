<div class="card-header p-0">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card-title">
                <form action="{{ route('admin.attributes.detail',$attribute->attribute_guid) }}" method="GET"
                    enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-4 col-sm-4">
                            <div class="input-icon">
                                <input name="name_en" type="text" class="form-control" placeholder="Search..." />
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
@if (count($attribute_groups) > 0)

<div class="card-body p-0">
    <!--begin::Table-->
    <div class="table-responsive">

        <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
            <thead>

                <tr class="text-left">
                    <th style="min-width: 140px">Id</th>
                    <th style="min-width: 140px">Name(EN)</th>
                    <th style="min-width: 150px">Name(AR)</th>
                </tr>

            </thead>
            <tbody>

                @foreach ($attribute_groups as $attribute_group)
                <tr>
                    <td>
                        <span class="text-dark-75 d-block font-size-lg">{{
                            $attribute_group->id
                            }}</span>
                    </td>
                    <td>
                        <span class="text-dark-75 d-block font-size-lg"><a
                                href="{{ route('admin.attribute_groups.detail',$attribute_group->ag_guid) }}"
                                target="_blank">{{
                                $attribute_group->name_en }}</a></span>
                    </td>
                    <td>
                        <span class="text-dark-75 d-block font-size-lg"><a
                                href="{{ route('admin.attribute_groups.detail',$attribute_group->ag_guid) }}"
                                target="_blank">{{
                                $attribute_group->name_ar }}</a></span>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>

    </div>
</div>

@if($attribute_groups->lastPage() > 1)
<div class="card-footer d-flex justify-content-end">
    {{
    $attribute_groups->appends(request()->input())->links('panel.modules.global.paginator',['paginator'=>$attribute_groups])
    }}
</div>

@endif
@else

<br />
<span class="text-dark-75 d-block font-size-lg text-lg-center">
    No Attribute Group Added.</span>
<br />

@endif
