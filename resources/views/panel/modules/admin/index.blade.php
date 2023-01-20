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
                @if (Auth::user()->type=='s')
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                        data-target="#add_example_modal">
                        <i class="fa fa-plus pr-0 mr-2"></i> Admin Add
                    </button>
                </div>
                @endif

            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body py-4">
                <!--begin::Table-->
                <div class="table-responsive">
                    @if (count($admins)>0)

                    <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                        <thead>

                            <tr class="text-left">
                                <th style="min-width: 140px">Id</th>
                                <th style="min-width: 140px">Name</th>
                                <th style="min-width: 150px">Username</th>
                                <th style="min-width: 150px">Type</th>
                                <th style="min-width: 150px">Detail @if(Auth::user()->type=='s')

                                    /Delete @endif</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--begin::Form-->
                            @foreach ($admins as $a)

                            <tr>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $a->id }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $a->name }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $a->username }}</span>
                                </td>
                                <td>
                                    @if($a->type == 's')
                                    <span class="label label-lg label-light-success label-inline">Super Admin</span>
                                    @else
                                    <span class="label label-lg label-light-info label-inline">Normal</span>
                                    @endif
                                </td>
                                <td>
                                    <input type="hidden" name="exapmle_guid" value="">
                                    <span class="text-dark-75 d-block font-size-lg">
                                        <a href="{{ route('admin.admin.detail',$a->username) }}"
                                            class="btn btn-icon btn-success">
                                            <i class="flaticon-eye"></i>
                                        </a>
                                        @if (Auth::user()->type=='s' && Auth::user()->id != $a->id)
                                        <button data-toggle="modal" data-target="#delete_{{ $a->id }}" type="button"
                                            class="btn btn-icon btn-danger delete_btns">
                                            <i class="flaticon2-trash"></i>
                                        </button>
                                        @endif


                                    </span>
                                </td>
                            </tr>
                            <div class="modal fade" id="delete_{{ $a->id }}" data-backdrop="static" tabindex="-1"
                                role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                <div class="modal-dialog modal-content" role="document">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Admin Delete</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <i aria-hidden="true" class="ki ki-close"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="text-align: center">
                                        <h4>Are you sure deleting <strong>{{ $a->name }}</strong> admin ?</h4>
                                    </div>
                                    <form class="w-100" method="post" action="{{ route('admin.admin.delete') }}">
                                        @csrf
                                        <div class="modal-footer">
                                            <input type="hidden" name="id" value="{{ $a->id }}" />
                                            <button type="button" class="btn btn-light-danger font-weight-bold"
                                                data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary font-weight-bold">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <br>
                            <span class="text-dark-75 d-block font-size-lg text-lg-center">
                                No Admins Added.</span>
                            @endif

                            <!--end::Form-->
                        </tbody>
                    </table>
                </div>
            </div>
            @if($admins->lastPage() > 1)
            <div class="card-footer">
                {{ $admins->appends(request()->input())->links('panel.modules.global.paginator',['paginator'=>$admins])
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
        <form class="modal-content" method="post" action="{{ route('admin.admin.add') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Admin Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" name="email" class="form-control form-control-lg" placeholder="Name" />
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control form-control-lg" placeholder="Name" />
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" />
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control form-control-lg"
                        placeholder="Password" />
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" class="form-control form-control-lg">
                        <option value="n">Normal Admin</option>
                        <option value="s">Super Admin</option>
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
    @if(Session::has('successAdminAdd'))
        toastr.success('Admin successfully created.');
    @endif
    @if(Session::has('errorAdminAdd'))
        toastr.error('There is an error occured while creating Admin. Please try again.');
    @endif
    @if(Session::has('errorValidate'))
        toastr.error('Please fill inputs correctly.');
    @endif
    @if(Session::has('successDeleteAdmin'))
        toastr.success('Admin deleted successfully.');
    @endif
    @if(Session::has('errorDeleteAdmin'))
        toastr.error('There is an error occured while deleting Admin. Please try again.');
    @endif
</script>
@endsection
