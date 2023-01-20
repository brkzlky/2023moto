<div class="row">
    <div class="col-xl-3">
        <!--begin::Stats Widget 25-->
        <div class="card card-custom mt-3">
            <!--begin::Body-->
            <div class="card-body">

                <span class="card-title text-dark-75 font-size-h2 mb-0 d-block">{{ $listing_count }}</span>
                <span class="font-weight-bold text-muted font-size-sm"><a href="#" target="_blank">Number of listings
                        contains {{ $trim->name_en }}</a></span>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Stats Widget 25-->
    </div>
</div>
<div class="row mt-5">

    <div class="col-lg-12">

        <div class="card card-custom">
            <div class="card-header">
                <div class="card-toolbar">
                    <ul class="nav nav-light-success nav-bold nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active generalSettings" data-toggle="tab" href="#general_settings">Trim
                                Settings</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body py-4">
                <div class="row mt-5">
                    <div class="col-lg-12">
                        <div class="example-preview">

                            <div class="tab-content mt-5" id="myTabContent">
                                <div class="tab-pane fade show active generalSettings" id="general_settings"
                                    role="tabpanel" aria-labelledby="general_settings">
                                    <form action="{{ route('admin.brands.trim.update') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row mt-5">
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Trim Name (EN)</label>
                                                    <input type="text" class="form-control" name="name_en"
                                                        value="{{ $trim->name_en }}" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Trim Name(AR)</label>
                                                    <input type="text" class="form-control" name="name_ar"
                                                        value="{{ $trim->name_ar }}" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-5">
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Year</label>
                                                    <input type="text" class="form-control" name="year"
                                                        value="{{ $trim->year }}" required/>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select class="form-control" name="status">
                                                        <option {{ $trim->status=='1'?'selected':null }} value="1">1
                                                        </option>
                                                        <option {{ $trim->status=='0'?'selected':null }} value="0">0
                                                        </option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer d-flex justify-content-end ">
                                            <input name="trim_guid" type="hidden" value="{{ $trim->trim_guid }}">
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
    @if(Session::has('successTrimUpdate'))
        toastr.success('Trim successfully updated.');
    @endif
</script>
@endsection
