<div class="row mt-5">
    <div class="col-lg-12">
        <div class="card card-custom">
            <div class="row mt-5">
                <div class="col-lg-12">
                    <div class="card card-custom">
                        <div class="card-header">
                            <div class="card-toolbar">
                                <ul class="nav nav-light-success nav-bold nav-pills">
                                    <li class="nav-item">
                                        <a class="nav-link active models" data-toggle="tab" href="#rates">Settings
                                            List</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body py-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="example-preview">
                                        <div class="tab-content" id="myTabContent">
                                            <!--MODELS-->
                                            <div class="tab-pane fade show active rates" id="rates" role="tabpanel"
                                                aria-labelledby="rates">
                                                <div class="table-responsive">
                                                    @if (count($settings)>0)


                                                    <table class="table table-head-custom table-vertical-center"
                                                        id="kt_advance_table_widget_1">
                                                        <thead>
                                                            <tr class="text-left">
                                                                <th style="min-width: 140px">Id</th>
                                                                <th style="min-width: 140px">Title(EN)</th>
                                                                <th style="min-width: 150px">Value</th>
                                                                <th style="min-width: 150px">Setting Type</th>
                                                                <th style="min-width: 150px">Input Type</th>
                                                                <th style="min-width: 150px">Detail/Delete</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!--begin::Form-->
                                                            @foreach ($settings as $s)
                                                            <tr>
                                                                <td>
                                                                    <span class="text-dark-75 d-block font-size-lg">{{
                                                                        $s->id }}</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-75 d-block font-size-lg">{{
                                                                        $s->title_en }}</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-75 d-block font-size-lg">{!! Str::length($s->setting_value) > 60 ? Str::limit($s->setting_value, 60, '...') : $s->setting_value !!}</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-75 d-block font-size-lg">{{
                                                                        $s->setting_type }}</span>

                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-75 d-block font-size-lg">{{
                                                                        $s->input_type }}</span>

                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-75 d-block font-size-lg">
                                                                        <a href="{{ route('admin.settings.detail',$s->setting_guid) }}" class="btn btn-icon btn-success">
                                                                            <i class="flaticon-eye"></i>
                                                                        </a>
                                                                        <button data-toggle="modal"
                                                                            data-target="#delete_{{ $s->setting_guid }}"
                                                                            type="button"
                                                                            class="btn btn-icon btn-danger delete_btns">
                                                                            <i class="flaticon2-trash"></i>
                                                                        </button>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <div class="modal fade" id="delete_{{ $s->setting_guid }}"
                                                                data-backdrop="static" tabindex="-1" role="dialog"
                                                                aria-labelledby="staticBackdrop" aria-hidden="true">
                                                                <div class="modal-dialog modal-content" role="document">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            Setting
                                                                            Delete
                                                                        </h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <i aria-hidden="true"
                                                                                class="ki ki-close"></i>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body" style="text-align: center">
                                                                        <h4>Are you sure deleting
                                                                            <strong>#{{ $s->title_en }}</strong>
                                                                            setting ?
                                                                        </h4>
                                                                    </div>
                                                                    <form class="w-100" method="post"
                                                                        action="{{ route('admin.settings.delete') }}">
                                                                        @csrf
                                                                        <div class="modal-footer">
                                                                            <input type="hidden" name="setting_guid"
                                                                                id="example_guide"
                                                                                value="{{ $s->setting_guid }}">
                                                                            <button type="button"
                                                                                class="btn btn-light-danger font-weight-bold"
                                                                                data-dismiss="modal">Cancel</button>
                                                                            <button type="submit"
                                                                                class="btn btn-primary font-weight-bold">Confirm</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                            <br>
                                                            <!--end::Form-->
                                                            <br>
                                                        </tbody>
                                                    </table>
                                                    @else
                                                    <span class="text-dark-75 d-block font-size-lg text-lg-center">
                                                        No Site Setting Added.</span>
                                                    @endif
                                                    <div class="card-footer d-flex justify-content-end ">
                                                        <button type="submit" class="btn btn-primary mr-2"
                                                            data-toggle="modal" data-target="#add_example_modal">Add
                                                            Setting</button>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="tab-pane fade  generalSettings" id="general_settings"
                                                role="tabpanel" aria-labelledby="general_settings">
                                                <form action="" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row mt-5">
                                                        <div class="col-lg-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label>Bank Name </label>
                                                                <input type="text" class="form-control" name="name"
                                                                    value="" />
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label>Status</label>
                                                                <select class="form-control" name="status">
                                                                    <option value="1">Active
                                                                    </option>
                                                                    <option value="0">Passive
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-5">
                                                        <div class="col-lg-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label>Bank Logo </label><br>
                                                                <img id="blah2" style="height: 200px; width: 300px;"
                                                                    src="" alt="Logo" />
                                                                <div class="custom-file">
                                                                    <input name="logo" type="file"
                                                                        class="custom-file-input" id="imgInp2"
                                                                        accept="image/png, image/jpeg" />
                                                                    <label class="custom-file-label"
                                                                        for="customFile">Choose
                                                                        Image</label>
                                                                    <span class="form-text text-muted">Allowed file
                                                                        extensions: png,
                                                                        jpg.
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer d-flex justify-content-end ">
                                                        <input name="bank_guid" type="hidden" value="">
                                                        <a href="{{ url()->previous() }}"
                                                            class="btn btn-danger mr-2">Back</a>
                                                        <button type="submit" class="btn btn-primary mr-2">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--Model add modal-->
<div class="modal fade" id="add_example_modal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content" method="post" action="{{ route('admin.settings.add') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Setting Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-6">
                        <label>Setting Title(EN)</label>
                        <input type="text" name="title_en" class="form-control form-control-lg"
                            placeholder="Setting Title(EN)" />
                        <small class="form-text text-muted">Required</small>
                    </div>
                    <div class="form-group col-6">
                        <label>Setting Title(AR)</label>
                        <input type="text" name="title_ar" class="form-control form-control-lg"
                            placeholder="Setting Title(AR)" />
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label>Value</label>
                        <textarea type="text" name="setting_value" class="form-control form-control-lg"
                            placeholder="Value"></textarea>
                    </div>
                    <div class="form-group col-6">
                        <label>Setting Type</label>
                        <select name="setting_type" class="form-control form-control-lg">
                            <option value="site">Site
                            </option>
                            <option value="email">Email
                            </option>
                            <option value="api">API
                            </option>
                            <option value="mobile">Mobile
                            </option>
                            <select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label>Input Type</label>
                        <select name="input_type" class="form-control form-control-lg">
                            <option value="text">Text
                            </option>
                            <option value="textarea">TextArea
                            </option>
                            <option value="switch">Switch
                            </option>
                            <option value="editor">Editor
                            </option>
                            <select>
                    </div>
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
    function readURL2(input) {
if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
        $('#blah2').attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
}
}

$("#imgInp2").change(function(){
readURL2(this);
});
</script>
<script>
    @if(Session::has('errorSettingValidate'))
    toastr.error('Please check fields and try again.');
@endif

@if(Session::has('settingSuccess'))
    toastr.success('Setting Successfully Added.');
@endif
    @if(Session::has('settingError'))
    toastr.error('There is an error occurred while adding setting please try again.');
@endif
@if(Session::has('settingDeleteSuccess'))
    toastr.success('Setting successfully deleted.');
@endif
@if(Session::has('settingDeleteError'))
    toastr.error('There is an error occured while deleting setting please try again.');
@endif




</script>
@endsection
