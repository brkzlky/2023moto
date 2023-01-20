<!-- Table Begin -->
<div class="row  mt-5">
    <div class="col-xl-12">
        <!--begin::Advance Table Widget 1-->
        <div class="card card-custom gutter-b">
            <!--begin::Header-->
            <div class="card-header">

            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body py-4">
                <!--begin::Table-->
                <div class="table-responsive">


                    <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                        <thead>

                            <tr class="text-left">
                                <th style="min-width: 140px">Id</th>
                                <th style="min-width: 140px">Name</th>
                                <th style="min-width: 150px">Label</th>
                                <th style="min-width: 150px">Symbol</th>
                                <th style="min-width: 150px">Status</th>
                                <th style="min-width: 150px">Detail/Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--begin::Form-->
                            @foreach ($currencies as $c)
                            <tr>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $c->id }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $c->name }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $c->label }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $c->symbol }}</span>
                                </td>
                                <td>
                                    @if ($c->status=='1')
                                    <span class="label label-lg label-light-success label-inline">Active</span>
                                    @else
                                    <span class="label label-lg label-light-danger label-inline">Passive</span>
                                    @endif
                                </td>
                                <td>
                                    <input type="hidden" name="exapmle_guid" value="">
                                    <span class="text-dark-75 d-block font-size-lg">
                                        <a href="{{ route('admin.currency.detail',$c->currency_guid) }}"
                                            class="btn btn-icon btn-success">
                                            <i class="flaticon-eye"></i>
                                        </a>
                                        <button data-toggle="modal" data-target="#delete_" type="button"
                                            class="btn btn-icon btn-danger delete_btns">
                                            <i class="flaticon2-trash"></i>
                                        </button>
                                    </span>
                                </td>
                            </tr>
                            <div class="modal fade" id="delete_" data-backdrop="static" tabindex="-1" role="dialog"
                                aria-labelledby="staticBackdrop" aria-hidden="true">
                                <div class="modal-dialog modal-content" role="document">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Admin Delete</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <i aria-hidden="true" class="ki ki-close"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="text-align: center">
                                        <h4>Are you sure deleting <strong></strong> admin ?</h4>
                                    </div>
                                    <form class="w-100" method="post" action="">
                                        @csrf
                                        <div class="modal-footer">
                                            <input type="hidden" name="id" value="" />
                                            <button type="button" class="btn btn-light-danger font-weight-bold"
                                                data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary font-weight-bold">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endforeach

                            <!--end::Form-->
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- @if($admins->lastPage() > 1)
            <div class="card-footer">
                {{ $admins->appends(request()->input())->links('panel.modules.global.paginator',['paginator'=>$admins])
                }}
            </div>
            @endif --}}
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

@endsection
