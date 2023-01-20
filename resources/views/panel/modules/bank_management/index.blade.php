<!-- Table Begin -->

<div class="row  mt-5">
    <div class="col-xl-12">
        <!--begin::Advance Table Widget 1-->
        <div class="card card-custom gutter-b">
            <!--begin::Header-->
            <div class="card-header">
                <h3 class="card-title">
                    <form action="{{ route('admin.bank_management.home') }}" method="GET" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-9 col-sm-9">
                                <div class="input-icon">
                                    <input name="name" type="text" class="form-control" placeholder="Search..." />
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
                        <i class="fa fa-plus pr-0 mr-2"></i> Bank Add
                    </button>

                </div>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body py-4">
                <!--begin::Table-->
                <div class="table-responsive">
                    @if (count($banks)>0)
                    <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                        <thead>
                            <tr class="text-left">
                                <th style="min-width: 140px">Id</th>
                                <th style="min-width: 140px">Name</th>
                                <th style="min-width: 150px">Status</th>
                                <th class="d-flex justify-content-end" style="min-width: 180px">Detail/Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--begin::Form-->
                            @foreach ($banks as $b)
                            <tr>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $b->id }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $b->name }}</span>
                                </td>
                                <td>
                                    @if($b->status == '1')
                                    <span class="label label-lg label-light-success label-inline">Active</span>
                                    @else
                                    <span class="label label-lg label-light-danger label-inline">Passive</span>
                                    @endif
                                </td>

                                <td class="d-flex justify-content-end">
                                    <span class="text-dark-75 d-block font-size-lg">
                                        <a href="{{ route('admin.bank_management.detail',$b->slug) }}"
                                            class="btn btn-icon btn-success">
                                            <i class="flaticon-eye"></i>
                                        </a>
                                        <button data-toggle="modal" data-target="#delete_{{ $b->brand_guid }}"
                                            type="button" class="btn btn-icon btn-danger delete_btns">
                                            <i class="flaticon2-trash"></i>
                                        </button>
                                    </span>
                                </td>
                                <!--Bank delete modal-->
                                <div class="modal fade" id="delete_{{ $b->brand_guid }}" data-backdrop="static"
                                    tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                    <div class="modal-dialog modal-content" role="document">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Bank Delete</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <i aria-hidden="true" class="ki ki-close"></i>
                                            </button>
                                        </div>
                                        <div class="modal-body" style="text-align: center">
                                            <h4>Are you sure deleting <strong>{{ $b->name }}</strong> bank ?</h4>
                                        </div>
                                        <form class="w-100" method="post"
                                            action="{{ route('admin.bank_management.delete') }}">
                                            @csrf
                                            <div class="modal-footer">
                                                <input type="hidden" name="bank_guid" id="example_guide"
                                                    value="{{ $b->bank_guid }}" required />
                                                <button type="button" class="btn btn-light-danger font-weight-bold"
                                                    data-dismiss="modal">Cancel</button>
                                                <button type="submit"
                                                    class="btn btn-primary font-weight-bold">Delete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </tr>
                            @endforeach
                            <!--end::Form-->
                        </tbody>
                    </table>
                    @else
                    <br />
                    <span class="text-dark-75 d-block font-size-lg text-lg-center">
                        No Banks Added.</span>
                    <br />
                    @endif
                </div>
            </div>
            @if($banks->lastPage() > 1)
            <div class="card-footer">
                {{ $banks->appends(request()->input())->links('panel.modules.global.paginator',['paginator'=>$banks]) }}
            </div>
            @endif
        </div>
        <!--end::Advance Table Widget 1-->
    </div>
</div>

<!-- Table End -->





<!--Bank add modal-->


<div class="modal fade" id="add_example_modal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" method="post" action="{{ route('admin.bank_management.add') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Bank Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control form-control-lg" placeholder="Name" />
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
    @if(Session::has('successBankDelete'))
        toastr.success('Bank successfully deleted.');
    @endif
    @if(Session::has('errorValidate'))
        toastr.error('There is an error occured while adding bank.');
    @endif
</script>
@endsection
