<!-- Table Begin -->
<div class="row  mt-5">
    <div class="col-xl-12">
        <!--begin::Advance Table Widget 1-->
        <div class="card card-custom gutter-b">
            <!--begin::Header-->
            <form action="{{ route('admin.users.home') }}" method="GET" enctype="multipart/form-data">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-3 col-sm-3">
                            <div class="input-icon">
                                <input name="search" type="text" class="form-control" placeholder="Search..." />
                                <input name="type_guid" type="hidden" value="{{ $type_guid[0] }}" />
                                <span>
                                    <i class=" flaticon2-search-1 text-muted"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-2 mt-1">
                            <div class="d-flex align-items-center">
                                <label class="mr-3 mb-0 d-none d-md-block">Status:</label>
                                <select class="form-control" name="status">
                                    <option value="">All</option>
                                    <option {{ request()->status=='2' ? 'selected' : '' }} value="2">Approve</option>
                                    <option {{ request()->status=='1' ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ request()->status=='0' ? 'selected' : '' }} value="0">Passive
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-3">
                            <div>
                                <button type="submit"
                                    class="btn btn-light-primary px-6 font-weight-bold">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body py-4">
                <!--begin::Table-->
                <div class="table-responsive">
                    @if (count($users) > 0)

                    <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                        <thead>

                            <tr class="text-left">
                                <th style="max-width: 100px;">Id</th>
                                <th style="min-width: 140px">Name</th>
                                <th style="min-width: 150px">Phone</th>
                                <th style="min-width: 150px">Email</th>
                                <th style="max-width: 100px">Listings</th>
                                <th style="max-width: 100px">Messages</th>
                                <th style="max-width: 100px">Favorite</th>
                                <th style="max-width: 100px">Status</th>
                                <th style="min-width: 180px">Detail/Delete</th>
                            </tr>


                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $user->id
                                        }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $user->name
                                        }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $user->phone
                                        }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $user->email
                                        }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $user->listings_count
                                        }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $user->user_chat_count
                                        }}</span>
                                </td>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $user->favorite_count
                                        }}</span>
                                </td>
                                <td>
                                    @if($user->status == '1')
                                    <span class="label label-lg label-light-success label-inline">Active</span>
                                    @elseif($user->status == '2')
                                    <span class="label label-lg label-light-warning label-inline">Approve</span>
                                    @else
                                    <span class="label label-lg label-light-danger label-inline">Passive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.detail',$user->user_guid) }}"
                                        class="btn btn-icon btn-success">
                                        <i class="flaticon-eye"></i>
                                    </a>
                                    <button guid="{{ $user->user_guid }}" cname="{{ $user->name }}" data-toggle="modal"
                                        data-target="#delete_users_modal" type="button"
                                        class="btn btn-icon btn-danger delete_btns">
                                        <i class="flaticon2-trash"></i>
                                    </button>
                                    @if ($user->status == 0 || $user->status == 2)
                                    <a href="{{ route('admin.users.status',$user->user_guid) }}"
                                        class="btn btn-icon btn-light-dark mr-2">
                                        <i class="
                                flaticon2-check-mark"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    @else

                    <br />
                    <span class="text-dark-75 d-block font-size-lg text-lg-center">
                        No Users Added.</span>
                    <br />

                    @endif
                </div>
            </div>
            @if($users->lastPage() > 1)
            <div class="card-footer">
                {{ $users->appends(request()->input())->links('panel.modules.global.paginator',['paginator'=>$users]) }}
            </div>
            @endif
        </div>
        <!--end::Advance Table Widget 1-->
    </div>
</div>
<!-- Table End -->

<!--users delete modal-->
<div class="modal fade" id="delete_users_modal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-content" role="document">
        <div class="modal-header">
            <h5 class="modal-title" id="userModalLabel">Users Delete</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>

        <div class="modal-body" style="text-align: center">

            <div id="name_modal_delete">

            </div>

        </div>
        <form class="w-100" method="post" action="{{ route('admin.users.delete') }}">
            @csrf
            <div class="modal-footer">
                <input type="hidden" name="user_guid" id="delete_guide" value="" required />
                <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary font-weight-bold">Delete</button>
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
            nameModalDelete.innerHTML = `You are about to delete an user. Are you sure you want to delete ${cname}?`;
            hiddenBtn.value = guid;
        });
    });
    @if(Session::has('errorValidate'))
        toastr.error('Please enter at least 2 letters.');
    @endif
    @if(Session::has('successUserStatus'))
        toastr.success('User successfully updated');
    @endif
    @if(Session::has('successAddUser'))
        toastr.success('User successfully added.');
    @endif
    @if(Session::has('successDeleteUsers'))
        toastr.success('User successfully deleted.');
    @endif
</script>
@endsection
