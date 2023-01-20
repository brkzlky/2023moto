<!--start::Statistics-->
<div class="row">
    <div class="col-xl-3">
        <!--begin::Stats Widget 25-->
        <div class="card card-custom mt-3">
            <!--begin::Body-->
            <div class="card-body">

                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 d-block">{{
                    $user->listings_count }}</span>
                <span class="font-weight-bold text-muted font-size-sm">Listings</span>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Stats Widget 25-->
    </div>
    <div class="col-xl-3">
        <!--begin::Stats Widget 25-->
        <div class="card card-custom mt-3">
            <!--begin::Body-->
            <div class="card-body">

                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 d-block">{{
                    $user->favorite_count }}</span>
                <span class="font-weight-bold text-muted font-size-sm">Favorite</span>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Stats Widget 25-->
    </div>
</div>

<!--end::Statistics-->
<div class="row mt-5">
    <div class="col-lg-12">

        <div class="card card-custom">
            <div class="card-header">
                <div class="card-toolbar">
                    <ul class="nav nav-light-success nav-bold nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active general_settings" data-toggle="tab" href="#general_settings">
                                <span class="nav-text">General Settings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link listings" data-toggle="tab" href="#listings">
                                <span class="nav-text">Listings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link messages" data-toggle="tab" href="#messages">
                                <span class="nav-text">Messages</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link user_block" data-toggle="tab" href="#user_block">
                                <span class="nav-text">User Block</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card-body py-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="example-preview">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active general_settings" id="general_settings"
                                    role="tabpanel" aria-labelledby="general_settings">

                                    @include('panel.modules.users.modules.user_settings')

                                </div>
                                <div class="tab-pane fade listings" id="listings" role="tabpanel"
                                    aria-labelledby="listings">


                                    @include('panel.modules.users.modules.user_listings')

                                </div>
                                <div class="tab-pane fade messages" id="messages" role="tabpanel"
                                    aria-labelledby="messages">

                                    @include('panel.modules.users.modules.user_messages')

                                </div>
                                <div class="tab-pane fade user_block" id="user_block" role="tabpanel"
                                    aria-labelledby="user_block">

                                    @include('panel.modules.users.modules.user_block')

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@section('js')
<script type="module" src="{{ secure_asset('panel/assets/js/chats.js')}}"></script>
<script>
    $('.summernote').summernote({
        toolbar: [
            ['cleaner', ['cleaner']],
            ['style', ['style', 'bold', 'italic', 'underline']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['table', ['table']],
        ],
        fontNames: ['Gilroy-Light', 'Gilroy-Regular', 'Gilroy-Medium'],
        fontNamesIgnoreCheck: ['Gilroy-Light', 'Gilroy-Regular', 'Gilroy-Medium'],
        cleaner: {
            action: 'paste',
        },
        height: 300,
        resize: false,
        lang: 'en-US',
    });
    const Routes = {
            chats : "{{ route('admin.users.chats') }}",
        }

    const categoryGuidBtns = document.getElementsByClassName('delete_btns');
    Array.from(categoryGuidBtns).forEach(btn => {
        btn.addEventListener('click',() => {
            const guid = btn.getAttribute('guid');
            const cname = btn.getAttribute('cname');
            const hiddenBtn = document.getElementById('delete_guide');
            const nameModalDelete = document.getElementById('name_modal_delete');
            nameModalDelete.innerHTML = `You are about to delete an listing. Are you sure you want to delete ${cname}?`;
            hiddenBtn.value = guid;
        });
    });

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

    @if(Session::has('errorValidate'))
        toastr.error('Please enter at least 2 letters.');
    @endif

    @if(Session::has('successUpdateUser'))
        toastr.success('User successfully updated.');
    @endif

    @if(Session::has('successDeletedUser'))
        toastr.success('User successfully deleted.');
    @endif

    @if(Session::has('successDeleteUserListing'))
        toastr.success('Listing successfully deleted.');
    @endif

    @if(isset(request()->users_chat) || $user_chat_search == 1)
        const general_settings = document.getElementsByClassName('general_settings')[1];
        general_settings.classList.remove('show');
        general_settings.classList.remove('active');
        const messages = document.getElementsByClassName('messages')[1];
        messages.classList.add('show');
        messages.classList.add('active');
        const general_settingsTab = document.getElementsByClassName('general_settings')[0];
        general_settingsTab.classList.remove('active');
        const messagesTab = document.getElementsByClassName('messages')[0];
        messagesTab.classList.add('active');
    @endif

    @if(Session::has('successUnblockUser'))
        toastr.success('Successfully unblock user');
        const general_settings = document.getElementsByClassName('general_settings')[1];
        general_settings.classList.remove('show');
        general_settings.classList.remove('active');
        const user_block = document.getElementsByClassName('user_block')[1];
        user_block.classList.add('show');
        user_block.classList.add('active');
        const general_settingsTab = document.getElementsByClassName('general_settings')[0];
        general_settingsTab.classList.remove('active');
        const user_blockTab = document.getElementsByClassName('user_block')[0];
        user_blockTab.classList.add('active');
    @endif

    @if(isset(request()->listings) || $user_listings_search == 1)
        const general_settings = document.getElementsByClassName('general_settings')[1];
        general_settings.classList.remove('show');
        general_settings.classList.remove('active');
        const listings = document.getElementsByClassName('listings')[1];
        listings.classList.add('show');
        listings.classList.add('active');
        const general_settingsTab = document.getElementsByClassName('general_settings')[0];
        general_settingsTab.classList.remove('active');
        const listingsTab = document.getElementsByClassName('listings')[0];
        listingsTab.classList.add('active');
    @endif

</script>
@endsection
