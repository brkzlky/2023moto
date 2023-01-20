<!-- Table Begin -->
<div class="row  mt-5">
    <div class="col-xl-12">
        <!--begin::Advance Table Widget 1-->
        <div class="card card-custom gutter-b">
            <!--begin::Header-->
            <div class="card-header">
                <div class="card-title">
                    <form action="" method="GET" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-9 col-sm-9">
                                <div class="input-icon">
                                    <input name="search" type="text" class="form-control" placeholder="Search..." />
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
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body py-4">
                <!--begin::Table-->
                <div class="table-responsive">
                    @if (count($users_chat) > 0)
                    <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                        <thead>

                            <tr class="text-left">
                                <th style="max-width: 40px">Id</th>
                                <th style="min-width: 140px">Listing No</th>
                                <th style="min-width: 140px">Listing Name</th>
                                <th style="min-width: 140px">Name(EN)</th>
                                <th style="min-width: 140px">First Message</th>
                                <th style="min-width: 140px">Last Message</th>
                                <th style="min-width: 140px">Total Message</th>
                                <th style="min-width: 180px">Detail/Delete</th>
                            </tr>


                        </thead>
                        <tbody>
                            @foreach ($users_chat as $user_chat)
                            <tr>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $user_chat->id }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $user_chat->listing_info->listing_no }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        Helper::limit_string($user_chat->listing_info->name_en) }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg"><a
                                            href="{{ route('admin.users.detail',$user_chat->user_info_own->user_guid) }}"
                                            target="_blank">{{
                                            $user_chat->user_info_own->name }}</a></span>
                                    <span class="text-dark-75 d-block font-size-lg"><a
                                            href="{{ route('admin.users.detail',$user_chat->user_info_opposite->user_guid) }}"
                                            target="_blank">{{
                                            $user_chat->user_info_opposite->name }}</a></span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $user_chat->created_at->diffForHumans()
                                        }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        \Carbon\Carbon::parse($user_chat->last_message['send_time'])->diffForHumans()
                                        }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ count($user_chat->messages_info)
                                        }}</span>
                                </td>
                                <td>
                                    <button user_chat_guid="{{ $user_chat->user_chat_guid }}"
                                        user_opposite_guid="{{ $user_chat->user_info_own->user_guid }}"
                                        class="btn btn-icon btn-success show_user_chat_modal" id="kt_btn_1">
                                        <i class="flaticon-eye"></i>
                                    </button>
                                    <button guid="{{ $user_chat->user_chat_guid }}" data-toggle="modal"
                                        data-target="#delete_btns_chat" type="button"
                                        class="btn btn-icon btn-danger delete_btns_chat">
                                        <i class="flaticon2-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <br />
                    <span class="text-dark-75 font-weight-bolder d-block font-size-lg text-lg-center">
                        No User Messages.</span>
                    <br />
                    @endif

                </div>
            </div>
            @if($users_chat->lastPage() > 1)
            <div class="card-footer">
                {{
                $users_chat->appends(request()->input())->links('panel.modules.global.paginator',['paginator'=>$users_chat])
                }}
            </div>
            @endif
        </div>
        <!--end::Advance Table Widget 1-->
    </div>
</div>
<!-- Table End -->




<div class="modal fade" id="delete_btns_chat" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-content" role="document">
        <div class="modal-header">
            <h5 class="modal-title" id="locationModalLabel">Location Delete</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>

        <div class="modal-body" style="text-align: center">

            <div id="delete_chat_content">

            </div>

        </div>
        <form class="w-100" method="post" action="{{ route('admin.messages.delete') }}">
            @csrf
            <div class="modal-footer">
                <input type="hidden" name="user_chat_guid" id="delete_chat" value="" required />
                <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary font-weight-bold">Delete</button>
            </div>

        </form>


    </div>
</div>

@section('js')
<script type="module" src="{{ secure_asset('panel/assets/js/chats.js')}}"></script>
<script>
    const Routes = {
            chats : "{{ route('admin.users.chats') }}",
        }

    const categoryGuidBtnsChat = document.getElementsByClassName('delete_btns_chat');
    Array.from(categoryGuidBtnsChat).forEach(btn => {
        btn.addEventListener('click',() => {
            const guid = btn.getAttribute('guid');
            const hiddenBtn = document.getElementById('delete_chat');
            const nameModalDelete = document.getElementById('delete_chat_content');
            nameModalDelete.innerHTML = `You are about to delete an message. Are you sure you want to delete?`;
            hiddenBtn.value = guid;
        });
    });

    @if(Session::has('successDeleteMessages'))
        toastr.success('Message successfully deleted.');
    @endif

</script>
@endsection
