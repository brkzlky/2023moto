<!-- Table Begin -->
<div class="row  mt-5">
    <div class="col-xl-12">
        <!--begin::Advance Table Widget 1-->
        <div class="card card-custom gutter-b">
            <div class="card-header">
                <div class="card-title">
                    <form action="{{ route('admin.attribute_groups.home') }}" method="GET"
                        enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-9 col-sm-9">
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
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                        data-target="#add_attribute_group_modal">
                        <i class="fa fa-plus pr-0 mr-2"></i> Attribute Group Add
                    </button>
                </div>
            </div>
            <!--begin::Body-->
            <div class="card-body py-4">
                <!--begin::Table-->
                <div class="table-responsive">
                    @if (count($attribute_groups) > 0)

                    <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                        <thead>

                            <tr class="text-left">
                                <th style="max-width: 100px">Id</th>
                                <th style="min-width: 150px">Name(EN)</th>
                                <th style="min-width: 150px">Name(AR)</th>
                                <th style="max-width: 100px">Category Name</th>
                                <th style="max-width: 100px">Attributes</th>
                                <th style="min-width: 100px">Status</th>
                                <th style="min-width: 180px">Detail/Delete</th>
                            </tr>


                        </thead>
                        <tbody>
                            @foreach ($attribute_groups as $attribute_group)
                            @csrf
                            <tr>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $attribute_group->id }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $attribute_group->name_en }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $attribute_group->name_ar }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $attribute_group->category_info->name_en }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $attribute_group->attribute_info_count }}</span>
                                </td>
                                <td>
                                    @if($attribute_group->status == '1')
                                    <span class="label label-lg label-light-success label-inline">Active</span>
                                    @else
                                    <span class="label label-lg label-light-danger label-inline">Passive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.attribute_groups.detail',$attribute_group->ag_guid) }}"
                                        class="btn btn-icon btn-success">
                                        <i class="flaticon-eye"></i>
                                    </a>
                                    <button guid="{{ $attribute_group->ag_guid }}"
                                        cname="{{ $attribute_group->name_en }}" data-toggle="modal"
                                        data-target="#delete_attribute_groups_modal" type="button"
                                        class="btn btn-icon btn-danger delete_btns">
                                        <i class="flaticon2-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    @else

                    <br />
                    <span class="text-dark-75 d-block font-size-lg text-lg-center">
                        No Attribute Groups Added.</span>
                    <br />

                    @endif
                </div>
            </div>
            @if($attribute_groups->lastPage() > 1)
            <div class="card-footer">
                {{
                $attribute_groups->appends(request()->input())->links('panel.modules.global.paginator',['paginator'=>$attribute_groups])
                }}
            </div>
            @endif
        </div>
        <!--end::Advance Table Widget 1-->
    </div>
</div>
<!-- Table End -->

<!--Attribute group delete modal-->
<div class="modal fade" id="delete_attribute_groups_modal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-content" role="document">
        <div class="modal-header">
            <h5 class="modal-title" id="attributeGroupsModalLabel">Attribute Group Delete</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>

        <div class="modal-body" style="text-align: center">

            <div id="name_modal_delete">

            </div>

        </div>
        <form class="w-100" method="post" action="{{ route('admin.attribute_groups.delete') }}">
            @csrf
            <div class="modal-footer">
                <input type="hidden" name="ag_guid" id="delete_guide" value="" required />
                <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary font-weight-bold">Delete</button>
            </div>

        </form>


    </div>
</div>
<!--Attribute group add modal-->
<div class="modal fade" id="add_attribute_group_modal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" method="POST" action="{{ route('admin.attribute_groups.add') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="attributeGroupsModalLabel">Attribute Group Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Attribute Group Name</label>
                    <input type="text" name="name_en" class="form-control form-control-lg" />
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select class="form-control" id="category_guid" name="category_guid">
                        @foreach ($categories as $category)
                        @if ($category->related_to == null)
                        <option value="{{ $category->category_guid }}">{{ $category->name_en }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary font-weight-bold">Save</button>
            </div>
        </form>
    </div>
</div>

@section('js')
<script>
    const categoryGuidBtns = document.getElementsByClassName('delete_btns');
    Array.from(categoryGuidBtns).forEach(btn => {
        btn.addEventListener('click',() => {
            const guid = btn.getAttribute('guid');
            const cname = btn.getAttribute('cname');
            const hiddenBtn = document.getElementById('delete_guide');
            const nameModalDelete = document.getElementById('name_modal_delete');
            nameModalDelete.innerHTML = `You are about to delete an attribute group. Are you sure you want to delete ${cname}?`;
            hiddenBtn.value = guid;
        });
    });
    @if(Session::has('errorValidate'))
        toastr.error('Please enter at least 2 letters.');
    @endif
    @if(Session::has('successAddAttributeGroup'))
        toastr.success('Attribute group successfully added.');
    @endif
    @if(Session::has('successDeleteAttributeGroup'))
        toastr.success('Attribute group successfully deleted.');
    @endif
</script>
@endsection
