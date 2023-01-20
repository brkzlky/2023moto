@extends('site.layout.master')

@section('main_content')
@if($errors->any())
    @php
        $msg = [];  
        foreach($errors->all() as $err) {
            $msg[] = $err;
        }
        $msg = implode(", ",$msg);
    @endphp
    <div class="alert alert-warning">
        {{ $msg }}
    </div>
@endif
@if(Session::has('success'))
<div class="alert alert-success">
    {{ Session::get('success') }}
</div>
@endif
@if(Session::has('error'))
<div class="alert alert-danger">
    {{ Session::get('error') }}
</div>
@endif
<section class="c-section c-section--sm-bottom">
    <div class="container">
        <div class="row">
            <!-- page title -->
            <div class="col-12">
                <div class="c-title c-title--mb-normal">
                    <h3 class="c-title__heading c-title__heading--regular">{{ __('module/label.profile_settings') }}</h3>
                </div>
            </div>
            <!-- tabs -->
            <div class="col-12">
                <ul class="nav nav-pills c-light-tab__pills" id="pills-tab">
                    <li class="nav-item c-light-tab__item">
                        <button class="nav-link c-light-tab__link {{ !Session::has('ptab') || (Session::has('ptab') && Session::get('ptab') == 'info') ? 'active' : null }}" id="pills-info-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-info" type="button" aria-controls="pills-info"
                            aria-selected="true">Personal Info</button>
                    </li>
                    <li class="nav-item c-light-tab__item d-none" role="presentation">
                        <button class="nav-link c-light-tab__link" id="pills-privacy-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-privacy" type="button" aria-controls="pills-privacy"
                            aria-selected="false">Privacy & Sharing</button>
                    </li>
                    <li class="nav-item c-light-tab__item d-none" role="presentation">
                        <button class="nav-link c-light-tab__link" id="pills-login-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-login" type="button" aria-controls="pills-login"
                            aria-selected="false">Login</button>
                    </li>
                    <li class="nav-item c-light-tab__item d-none" role="presentation">
                        <button class="nav-link c-light-tab__link" id="pills-security-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-security" type="button" aria-controls="pills-security"
                            aria-selected="false">Security</button>
                    </li>
                    <li class="nav-item c-light-tab__item {{ Session::has('ptab') && Session::get('ptab') == 'profile' ? 'active' : null }}" role="presentation">
                        <button class="nav-link c-light-tab__link" id="pills-password-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-password" type="button" aria-controls="pills-password"
                            aria-selected="false">Password</button>
                    </li>
                    <li class="nav-item c-light-tab__item d-none" role="presentation">
                        <button class="nav-link c-light-tab__link" id="pills-delete-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-delete" type="button" aria-controls="pills-delete"
                            aria-selected="false">Delete Account</button>
                    </li>
                </ul>
                <div class="tab-content c-light-tab__content" id="pills-tabContent">
                    <div class="tab-pane c-light-tab__pane fade {{ !Session::has('ptab') || (Session::has('ptab') && Session::get('ptab') == 'info') ? 'show active' : null }}" id="pills-info" aria-labelledby="pills-info-tab">
                        @include('user.module.profile.info')
                    </div>
                    <div class="tab-pane c-light-tab__pane fade" id="pills-privacy" aria-labelledby="pills-privacy-tab">
                        Privacy & Sharing tab here
                    </div>
                    <div class="tab-pane c-light-tab__pane fade" id="pills-login" aria-labelledby="pills-login-tab">
                        Login tab here
                    </div>
                    <div class="tab-pane c-light-tab__pane fade" id="pills-security" aria-labelledby="pills-security-tab">
                        Security tab here
                    </div>
                    <div class="tab-pane c-light-tab__pane fade {{ Session::has('ptab') && Session::get('ptab') == 'profile' ? 'show active' : null }}" id="pills-password" aria-labelledby="pills-password-tab">
                        @include('user.module.profile.password')
                    </div>
                    <div class="tab-pane c-light-tab__pane fade" id="pills-delete" aria-labelledby="pills-delete-tab">
                        Delete account here
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
    <script>
        $('#infoSaveBtn').on('click', function() {
            var childrens = $('#infoForm').children().find('.c-input');
            if(childrens.length > 0) {
                for (let i = 0; i < childrens.length; i++) {
                    childrens[i].disabled = false;
                }
            }

            setTimeout(() => {
                $('#infoForm').submit();
            }, 200);
        });

        $('.summernote-en').summernote({
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

        $('.summernote-ar').summernote({
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
    </script>
@endsection