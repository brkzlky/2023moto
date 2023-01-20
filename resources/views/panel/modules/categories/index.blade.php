<!-- Table Begin -->
<div class="row  mt-5">
    <div class="col-xl-12">
        <!--begin::Advance Table Widget 1-->
        <div class="card card-custom gutter-b">
            <!--begin::Header-->
            <div class="card-header">
                <div class="card-title">
                    <form action="{{ route('admin.categories.home') }}" method="GET" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-10 col-sm-10">
                                <div class="input-icon">
                                    <input name="name_en" type="text" class="form-control" placeholder="Search..." />
                                    <span>
                                        <i class=" flaticon2-search-1 text-muted"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-2">
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
                        data-target="#add_categories_modal">
                        <i class="fa fa-plus pr-0 mr-2"></i> Categories Add
                    </button>
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body py-4">
                <!--begin::Table-->
                <div class="table-responsive">
                    @if (count($categories) > 0)

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
                            @foreach ($categories as $category)
                            <tr>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $category->id
                                        }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $category->name_en }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $category->name_ar }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $category->attr_groups_info_count }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $category->location_info_count }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $category->listings_count }}</span>
                                </td>
                                <td>
                                    @if($category->status == '1')
                                    <span class="label label-lg label-light-success label-inline">Active</span>
                                    @else
                                    <span class="label label-lg label-light-danger label-inline">Passive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.categories.detail',$category->category_guid) }}"
                                        class="btn btn-icon btn-success">
                                        <i class="flaticon-eye"></i>
                                    </a>
                                    <button count="{{ $category->children_count }}"
                                        guid="{{ $category->category_guid }}" cname="{{ $category->name_en }}"
                                        data-toggle="modal" data-target="#delete_categories_modal" type="button"
                                        class="btn btn-icon btn-danger delete_btns">
                                        <i class="flaticon2-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @foreach ($category->children as $children)
                            <tr>

                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $children->id
                                        }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $children->name_en }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $children->name_ar }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $children->attr_groups_info()->count() }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $children->location_info()->count() }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $children->listings()->count() }}</span>
                                </td>
                                <td>
                                    @if($children->status == '1')
                                    <span class="label label-lg label-light-success label-inline">Active</span>
                                    @else
                                    <span class="label label-lg label-light-danger label-inline">Passive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.categories.detail',$children->category_guid) }}"
                                        class="btn btn-icon btn-success">
                                        <i class="flaticon-eye"></i>
                                    </a>
                                    <button guid="{{ $children->category_guid }}" cname="{{ $children->name_en }}"
                                        data-toggle="modal" data-target="#delete_categories_modal" type="button"
                                        class="btn btn-icon btn-danger delete_btns">
                                        <i class="flaticon2-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
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
            @if($categories->lastPage() > 1)
            <div class="card-footer">
                {{
                $categories->appends(request()->input())->links('panel.modules.global.paginator',['paginator'=>$categories])
                }}
            </div>
            @endif
        </div>
        <!--end::Advance Table Widget 1-->
    </div>
</div>
<!-- Table End -->

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
        <form class="w-100" method="post" action="{{ route('admin.categories.delete') }}">

            <div class="modal-body" style="text-align: center">

                <div id="name_modal_delete">

                </div>
                <div style="display: none" id="children" class="checkbox-inline">
                    <br />
                    <br />
                    <br />
                    <label class="checkbox checkbox-danger">
                        <input value="true" type="checkbox" name="delete_children" />
                        <span></span>
                        <strong>delete childcategories of this category?</strong>
                    </label>
                </div>
            </div>
            @csrf
            <div class="modal-footer">
                <input type="hidden" name="category_guid" id="delete_guide" value="" required />
                <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary font-weight-bold">Delete</button>
            </div>
        </form>
    </div>
</div>


<!--category add modal-->
<div class="modal fade" id="add_categories_modal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" method="POST" action="{{ route('admin.categories.add') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Category Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Category Name</label>
                    <input type="text" name="name_en" class="form-control form-control-lg" />
                </div>
                <div class="form-group">
                    <label>Parent</label>
                    <select class="form-control" id="related_to" name="related_to">
                        <option value="" selected>
                            Parent Category
                        </option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->category_guid }}">
                            {{ $category->related_to == null ? $category->name_en : '' }}
                        </option>
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
    const children = document.getElementById('children');
    Array.from(categoryGuidBtns).forEach(btn => {
        btn.addEventListener('click',() => {
            const guid = btn.getAttribute('guid');
            const cname = btn.getAttribute('cname');
            const count = btn.getAttribute('count');
            if (count > 0 && count != null) {
                children.style.display = 'block';
            }else {
                children.style.display = 'none';
            }
            const hiddenBtn = document.getElementById('delete_guide');
            const nameModalDelete = document.getElementById('name_modal_delete');
            nameModalDelete.innerHTML = `You are about to delete an categories. Are you sure you want to delete ${cname}?`;
            hiddenBtn.value = guid;
        });
    });
    @if(Session::has('errorValidate'))
        toastr.error('Please enter at least 2 letters.');
    @endif
    @if(Session::has('successAddParentCategory'))
        toastr.success('Parent Category successfully added.');
    @endif

    @if(Session::has('successAddChildCategory'))
        toastr.success('Child Category successfully added.');
    @endif
    @if(Session::has('successDeleteCategory'))
        toastr.success('Attribute group successfully deleted.');
    @endif
    @if(Session::has('successDeletedCategoryChildren'))
        toastr.success('Category and children successfully deleted.');
    @endif
    @if(Session::has('successDeletedCategory'))
        toastr.success('Category successfully deleted.');
    @endif
</script>
@endsection
