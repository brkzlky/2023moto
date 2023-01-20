<div class="card-header p-0">
    <div class="row">
        <div class="col-lg-6 col-sm-6">
            <div class="card-title">
                <form action="" method="GET" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-9 col-sm-9">
                            <div class="input-icon">
                                <input name="name_en_children" type="text" class="form-control"
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
<form action="{{ route('admin.categories.delete_inside') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card-body p-0">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                    <thead>
                        <tr class="text-left">
                            <th style="max-width: 100px">Id</th>
                            <th style="min-width: 150px">Name(EN)</th>
                            <th style="min-width: 150px">Name(AR)</th>
                            <th style="max-width: 100px">Attribute G.</th>
                            <th style="max-width: 100px">Location</th>
                            <th style="max-width: 100px">Listings</th>
                            <th style="min-width: 100px">Status</th>
                            <th style="min-width: 180px">Detail/Delete</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($category_children as $child)

                        <tr>
                            <td>
                                <span class="text-dark-75 d-block font-size-lg">{{ $child->id
                                    }}</span>
                            </td>
                            <td>
                                <span class="text-dark-75 d-block font-size-lg">{{
                                    $child->name_en }}</span>
                            </td>
                            <td>
                                <span class="text-dark-75 d-block font-size-lg">{{
                                    $child->name_ar }}</span>
                            </td>
                            <td>
                                <span class="text-dark-75 d-block font-size-lg">{{
                                    $child->attr_groups_info()->count() }}</span>
                            </td>
                            <td>
                                <span class="text-dark-75 d-block font-size-lg">{{
                                    $child->location_info()->count() }}</span>
                            </td>
                            <td>
                                <span class="text-dark-75 d-block font-size-lg">{{
                                    $child->listings()->count() }}</span>
                            </td>
                            <td>
                                @if($child->status == '1')
                                <span class="label label-lg label-light-success label-inline">Active</span>
                                @else
                                <span class="label label-lg label-light-danger label-inline">Passive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.categories.detail',$child->category_guid) }}"
                                    class="btn btn-icon btn-success">
                                    <i class="flaticon-eye"></i>
                                </a>
                                <button guid="{{ $child->category_guid }}" cname="{{ $child->name_en }}"
                                    data-toggle="modal" data-target="#delete_categories_modal" type="button"
                                    class="btn btn-icon btn-danger delete_btns">
                                    <i class="flaticon2-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if($category_children->lastPage() > 1)
    <div class="card-footer d-flex justify-content-between">
        {{
        $category_children->appends(request()->input())->links('panel.modules.global.paginator',['paginator'=>$category_children])
        }}
    </div>
    @endif
</form>

<!--category delete modal-->
<div class="modal fade" id="delete_categories_modal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-content" role="document">
        <div class="modal-header">
            <h5 class="modal-title" id="categoryModalLabel">Category Delete</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>

        <div class="modal-body" style="text-align: center">

            <div id="name_modal_delete">

            </div>

        </div>
        <form class="w-100" method="post" action="{{ route('admin.categories.delete_inside') }}">
            @csrf
            <div class="modal-footer">
                <input type="hidden" name="child_guid" id="delete_guide" value="" required />
                <input type="hidden" name="category_guid" value="{{ $category->category_guid }}" required />

                <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary font-weight-bold">Delete</button>
            </div>
        </form>
    </div>
</div>
