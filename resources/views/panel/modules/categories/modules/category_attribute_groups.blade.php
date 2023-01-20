<div class="card-header p-0">
    <div class="row">
        <div class="col-lg-6 col-sm-6">
            <div class="card-title">
                <form action="" method="GET" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-9 col-sm-9">
                            <div class="input-icon">
                                <input name="name_en_attribute_group" type="text" class="form-control"
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
@if (count($category_attribute_groups) > 0)
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
                    @foreach ($category_attribute_groups as $attribute_group)
                    <tr>
                        <td>
                            {{ $attribute_group->id }}
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
</div>
<div class="card-footer d-flex justify-content-between">
    @if($category_attribute_groups->lastPage() > 1)
    {{
    $category_attribute_groups->appends(request()->input())->links('panel.modules.global.paginator',['paginator'=>$category_attribute_groups])
    }}
    @endif
</div>

@else
<br />
<span class="text-dark-75 d-block font-size-lg text-lg-center">
    No attribute group is attached to this category</span>
<br />
@endif
