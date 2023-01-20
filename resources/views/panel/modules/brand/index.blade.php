<!-- Table Begin -->
<div class="row  mt-5">
    <div class="col-xl-12">
        <!--begin::Advance Table Widget 1-->
        <div class="card card-custom gutter-b">
            <!--begin::Header-->
            <div class="card-header">
                <h3 class="card-title">
                    <form action="" method="GET" enctype="multipart/form-data">
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
                </h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                        data-target="#add_example_modal">
                        <i class="fa fa-plus pr-0 mr-2"></i> Brand Add
                    </button>
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body py-4">
                <!--begin::Table-->
                <div class="table-responsive">
                    <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                        <thead>
                            @if (count($brands)>0)
                            <tr class="text-left">
                                <th style="min-width: 140px">Id</th>
                                <th style="min-width: 140px">Name(EN)</th>
                                <th style="min-width: 150px">Name(AR)</th>
                                <th style="min-width: 150px">Category</th>
                                <th style="min-width: 150px">Status</th>
                                <th style="min-width: 150px">Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--begin::Form-->

                            @foreach ($brands as $b)
                            <tr>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $b->id }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $b->name_en }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $b->name_ar==null?'Empty':$b->name_ar }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $b->category->name_en }}</span>
                                </td>
                                <td>
                                    @if($b->status == '1')
                                    <span class="label label-lg label-light-success label-inline">Active</span>
                                    @else
                                    <span class="label label-lg label-light-danger label-inline">Passive</span>
                                    @endif
                                </td>
                                <td>
                                    <input type="hidden" name="exapmle_guid" value="">
                                    <span class="text-dark-75 d-block font-size-lg">
                                        <a href="{{ route('admin.brands.detail',$b->brand_guid) }}"
                                            class="btn btn-icon btn-success">
                                            <i class="flaticon-eye"></i>
                                        </a>
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <br>
                            <span class="text-dark-75 d-block font-size-lg text-lg-center">
                                No Brands Added.</span>
                            @endif

                            <!--end::Form-->
                        </tbody>
                    </table>
                </div>
            </div>
            @if($brands->lastPage() > 1)
            <div class="card-footer">
                {{ $brands->appends(request()->input())->links('panel.modules.global.paginator',['paginator'=>$brands])
                }}
            </div>
            @endif
        </div>
        <!--end::Advance Table Widget 1-->
    </div>
</div>
<!-- Table End -->
<!--Example delete modal-->

<!--Example add modal-->
<div class="modal fade" id="add_example_modal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" method="post" action="{{ route('admin.brands.add') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Brand Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Brand Name (EN)</label>
                    <input type="text" name="name_en" class="form-control form-control-lg" placeholder="Brand Name" />
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select class="form-control" id="category_guid" name="category_guid">
                        @foreach ($categories as $category)
                        <option value="{{ $category->category_guid }}">{{ $category->name_en }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="font-weight-bolder text-center font-size-h5-sm">Be careful when creating a brand. You cannot delete brand or change the category!</label>
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
    @if(Session::has('successBrandDelete'))
        toastr.success('Brand successfully deleted.');
    @endif
    @if(Session::has('errorValidate'))
        toastr.success('Error occured while adding Brand.');
    @endif

</script>
@endsection
