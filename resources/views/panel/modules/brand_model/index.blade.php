<div class="row">
    <div class="col-xl-3">
        <!--begin::Stats Widget 25-->
        <div class="card card-custom mt-3">
            <!--begin::Body-->
            <div class="card-body">

                <span class="card-title text-dark-75 font-size-h2 mb-0 d-block">{{ $listing_count }}</span>
                <span class="font-weight-bold text-muted font-size-sm"><a href="#" target="_blank">Number of listings
                        contains {{ $model->name_en }}</a></span>
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
                            <a class="nav-link active generalSettings" data-toggle="tab" href="#general_settings">Model
                                Settings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link years" data-toggle="tab" href="#years">Trims</a>
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
                                    <form action="{{ route('admin.brands.model.detail.update') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row mt-5">
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Model Name (EN)</label>
                                                    <input type="text" class="form-control" name="name_en"
                                                        value="{{ $model->name_en }}" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Brand Name(AR)</label>
                                                    <input type="text" class="form-control" name="name_ar"
                                                        value="{{ $model->name_ar }}" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-5">
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select class="form-control" name="status">
                                                        <option {{ $model->status=='1' ? 'selected':null }} value="1">1
                                                        </option>
                                                        <option {{ $model->status=='0' ? 'selected':null }} value="0">0
                                                        </option>
                                                    </select>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer d-flex justify-content-end ">
                                            <input name="model_guid" type="hidden" value="{{ $model->model_guid }}">
                                            <button type="submit" class="btn btn-primary mr-2">Save</button>
                                        </div>
                                    </form>
                                </div>
                                <!--years-->
                                <div class="tab-pane fade years" id="years" role="tabpanel" aria-labelledby="years">
                                    <div class="table-responsive">
                                        <table class="table table-head-custom table-vertical-center"
                                            id="kt_advance_table_widget_1">
                                            <thead>
                                                @if (count($trims)>0)

                                                <tr class="text-left">
                                                    <th style="min-width: 140px">Id</th>
                                                    <th style="min-width: 140px">Name(EN)</th>
                                                    <th style="min-width: 140px">Name(AR)</th>
                                                    <th style="min-width: 140px">Year</th>
                                                    <th style="min-width: 140px">Status</th>
                                                    <th style="min-width: 150px">Detail</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!--begin::Form-->

                                                @foreach ($trims as $t)
                                                <tr>
                                                    <td>
                                                        <span class="text-dark-75 d-block font-size-lg">{{
                                                            $t->id }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="text-dark-75 d-block font-size-lg">{{
                                                            $t->name_en }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="text-dark-75 d-block font-size-lg">{{
                                                            $t->name_ar }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="text-dark-75 d-block font-size-lg">{{
                                                            $t->year }}</span>
                                                    </td>
                                                    <td>
                                                        @if($t->status == '1')
                                                        <span
                                                            class="label label-lg label-light-success label-inline">Active</span>
                                                        @else
                                                        <span
                                                            class="label label-lg label-light-danger label-inline">Passive</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="exapmle_guid" value="">
                                                        <span class="text-dark-75 d-block font-size-lg">
                                                            <a href="{{ route('admin.brands.trim.detail',$t->slug) }}"
                                                                class="btn btn-icon btn-success">
                                                                <i class="flaticon-eye"></i>
                                                            </a>
                                                        </span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @else
                                                <br>
                                                <span class="text-dark-75 d-block font-size-lg text-lg-center">
                                                    No Trims Added.</span>

                                                @endif


                                                <!--end::Form-->
                                            </tbody>
                                        </table>
                                        @if($trims->lastPage() > 1)
                                            {{ $trims->appends(request()->input())->links('panel.modules.global.paginator',['paginator'=>$trims])}}
                                        @endif
                                        <div class="card-footer d-flex justify-content-end ">
                                            <input name="brand" type="hidden" value="">
                                            <button type="submit" class="btn btn-primary mr-2" data-toggle="modal"
                                                data-target="#add_example_modal">Add Trim</button>
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
    <div class="modal-dialog" role="document">
        <form class="modal-content" method="post" action="{{ route('admin.brands.trim.add') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Trim Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Trim Name(EN)</label>
                    <input type="text" name="name_en" class="form-control form-control-lg" placeholder="Name(EN)" />
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="model_guid" value="{{ $model->model_guid }}">
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
    @if(Session::has('successModelUpdate'))
        toastr.success('Model successfully updated.');
    @endif
    @if(Session::has('successTrimAdd'))
        toastr.success('Model successfully added.');
    @endif
    @if(Session::has('successTrimDelete'))
        toastr.success('Model successfully deleted.');
    @endif
</script>
@endsection
