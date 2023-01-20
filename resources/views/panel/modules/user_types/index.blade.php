<!-- Table Begin -->

<div class="row  mt-5">
    <div class="col-xl-12">
        <!--begin::Advance Table Widget 1-->
        <div class="card card-custom gutter-b">
            <!--begin::Header-->
            <div class="card-header">
                <h3 class="card-title">
                    User Types List
                </h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                        data-target="#add_user_types_add">
                        <i class="fa fa-plus pr-0 mr-2"></i> User Types Add
                    </button>

                </div>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body py-4">
                <!--begin::Table-->
                <div class="table-responsive">
                    @if (count($user_types) > 0)

                    <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                        <thead>

                            <tr class="text-left">
                                <th style="min-width: 140px">Id</th>
                                <th style="min-width: 140px">Name(EN)</th>
                                <th style="min-width: 150px">Name(AR)</th>
                                <th style="min-width: 180px">Detail/Delete</th>
                            </tr>


                        </thead>
                        <tbody>

                            @foreach ($user_types as $user_type)


                            <tr>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $user_type->id }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $user_type->name_en }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $user_type->name_ar }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.user_types.detail',$user_type->type_guid) }}"
                                        class="btn btn-icon btn-success">
                                        <i class="flaticon-eye"></i>
                                    </a>
                                    @if ($user_type->id != 1 && $user_type->id != 2)

                                    <button guid="{{ $user_type->type_guid }}" cname="{{ $user_type->name_en }}"
                                        data-toggle="modal" data-target="#delete_user_types_modal" type="button"
                                        class="btn btn-icon btn-danger delete_btns">
                                        <i class="flaticon2-trash"></i>
                                    </button>
                                    @endif

                                </td>

                            </tr>

                            @endforeach

                        </tbody>
                    </table>
                    @else

                    <br />
                    <span class="text-dark-75 d-block font-size-lg text-lg-center">
                        No user types Added.</span>
                    <br />


                    @endif
                </div>
            </div>

            @if($user_types->lastPage() > 1)
            <div class="card-footer">
                {{
                $user_types->appends(request()->input())->links('panel.modules.global.paginator',['paginator'=>$user_types])
                }}
            </div>
            @endif
        </div>
        <!--end::Advance Table Widget 1-->
    </div>
</div>

<!-- Table End -->




<!--User Types delete modal-->
<div class="modal fade" id="delete_user_types_modal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-content" role="document">
        <div class="modal-header">
            <h5 class="modal-title" id="user_TypesModalLabel">User Type Delete</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>

        <div class="modal-body" style="text-align: center">

            <div id="name_modal_delete">

            </div>

        </div>

        <form class="w-100" method="post" action="{{ route('admin.user_types.delete') }}">
            @csrf
            <div class="modal-footer">
                <input type="hidden" name="type_guid" id="delete_guide" value="" required />
                <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary font-weight-bold">Delete</button>
            </div>

        </form>


    </div>
</div>
<!--User Types add modal-->


<div class="modal fade" id="add_user_types_add" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" method="POST" action="{{ route('admin.user_types.add') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="userTypesModalLabel">User Type Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>



            <div class="modal-body">
                <div class="form-group">
                    <label>User Type Name</label>
                    <input type="text" name="name_en" class="form-control form-control-lg" />
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
            nameModalDelete.innerHTML = `You are about to delete an attribute. Are you sure you want to delete ${cname}?`;
            hiddenBtn.value = guid;
        });
    });
    @if(Session::has('errorValidate'))
        toastr.error('Please enter at least 2 letters.');
    @endif
    @if(Session::has('successAddUserTypes'))
        toastr.success('User Type successfully added.');
    @endif
    @if(Session::has('successDeleteUserTypes'))
        toastr.success('User Type successfully deleted.');
    @endif
</script>
@endsection
