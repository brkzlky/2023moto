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
                                        <a class="nav-link active generalSettings" data-toggle="tab"
                                            href="#general_settings">Bank
                                            Settings</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link models" data-toggle="tab" href="#rates">Rates</a>
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
                                                <form action="{{ route('admin.bank_management.detail.update') }}"
                                                    method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row mt-5">
                                                        <div class="col-lg-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label>Bank Name </label>
                                                                <input type="text" class="form-control" name="name"
                                                                    value="{{ $bank->name }}" />
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 col-sm-6">
                                                            <div class="form-group">
                                                                <label>Status</label>
                                                                <select class="form-control" name="status">
                                                                    <option {{ $bank->status=='1'?'selected':'' }}
                                                                        value="1">Active
                                                                    </option>
                                                                    <option {{ $bank->status=='0'?'selected':'' }}
                                                                        value="0">Passive
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
                                                                    src="{{ secure_asset('/bank_logos/'.$bank->logo) }}"
                                                                    alt="Logo" />
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
                                                        <input name="bank_guid" type="hidden"
                                                            value="{{ $bank->bank_guid }}">
                                                       
                                                        <button type="submit" class="btn btn-primary mr-2">Save</button>
                                                    </div>
                                                </form>
                                            </div>
                                            <!--MODELS-->
                                            <div class="tab-pane fade rates" id="rates" role="tabpanel"
                                                aria-labelledby="rates">
                                                <div class="table-responsive">
                                                    @if(count($rates)>0)
                                                    <table class="table table-head-custom table-vertical-center"
                                                        id="kt_advance_table_widget_1">
                                                        <thead>
                                                            <tr class="text-left">
                                                                <th style="min-width: 140px">Id</th>
                                                                <th style="min-width: 140px">Rate(%)</th>
                                                                <th style="min-width: 150px">Period</th>
                                                                <th style="min-width: 150px">Period Type</th>
                                                                <th style="min-width: 150px">Status</th>
                                                                <th style="min-width: 150px">Delete</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!--begin::Form-->
                                                            @foreach ($rates as $r)
                                                            <tr>
                                                                <td>
                                                                    <span class="text-dark-75 d-block font-size-lg">{{
                                                                        $r->id }}</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-75 d-block font-size-lg">{{
                                                                        $r->rate }}%</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-75 d-block font-size-lg">{{
                                                                        $r->period }}</span>
                                                                </td>
                                                                <td>
                                                                    @if ($r->period_type=='d')
                                                                    <span
                                                                        class="text-dark-75 d-block font-size-lg">Day</span>
                                                                    @elseif ($r->period_type=='w')
                                                                    <span
                                                                        class="text-dark-75 d-block font-size-lg">Week</span>
                                                                    @elseif ($r->period_type=='m')
                                                                    <span
                                                                        class="text-dark-75 d-block font-size-lg">Month</span>
                                                                    @else
                                                                    <span
                                                                        class="text-dark-75 d-block font-size-lg">Year</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if ($r->status=='1')
                                                                    <span
                                                                        class="label label-lg label-light-success label-inline">Active</span>
                                                                    @else

                                                                    <span
                                                                        class="label label-lg label-light-danger label-inline">Passive</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-75 d-block font-size-lg">
                                                                        <button data-toggle="modal"
                                                                            data-target="#delete_" type="button"
                                                                            class="btn btn-icon btn-danger delete_btns">
                                                                            <i class="flaticon2-trash"></i>
                                                                        </button>

                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            <div class="modal fade" id="delete_" data-backdrop="static"
                                                                tabindex="-1" role="dialog"
                                                                aria-labelledby="staticBackdrop" aria-hidden="true">
                                                                <div class="modal-dialog modal-content" role="document">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">
                                                                            Rate
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
                                                                            <strong>#{{ $r->id }}</strong>
                                                                            bank rate ?
                                                                        </h4>
                                                                    </div>
                                                                    <form class="w-100" method="post"
                                                                        action="{{ route('admin.bank_rate.delete') }}">
                                                                        @csrf
                                                                        <div class="modal-footer">
                                                                            <input type="hidden" name="slug"
                                                                                value="{{ $bank->slug }}">
                                                                            <input type="hidden" name="rate_guid"
                                                                                id="example_guide"
                                                                                value="{{ $r->rate_guid }}">
                                                                            <button type="button"
                                                                                class="btn btn-light-danger font-weight-bold"
                                                                                data-dismiss="modal">Cancel</button>
                                                                            <button type="submit"
                                                                                class="btn btn-primary font-weight-bold">Save</button>
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
                                                        No Bank Rate Added.</span>
                                                    @endif
                                                    <div class="card-footer d-flex justify-content-end ">
                                                        <input name="brand" type="hidden" value="">
                                                       
                                                        <button type="submit" class="btn btn-primary mr-2"
                                                            data-toggle="modal" data-target="#add_example_modal">Add
                                                            Rate</button>
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
    </div>
</div>


<!--Model add modal-->
<div class="modal fade" id="add_example_modal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content" method="post" action="{{ route('admin.bank_rate.add') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Model Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-6">
                        <label>Rate</label>
                        <input type="text" name="rate" class="form-control form-control-lg" placeholder="Rate" />
                    </div>
                    <div class="form-group col-6">
                        <label>Period</label>
                        <input type="text" name="period" class="form-control form-control-lg" placeholder="Period" />
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-6">
                        <label>Period Type</label>
                        <select class="form-control form-control-lg" name="period_type">
                            <option value="d">Day</option>
                            <option value="w">Week</option>
                            <option value="m">Month</option>
                            <option value="y">Year</option>

                        </select>
                    </div>
                    <div class="form-group col-6">
                        <label>Status</label>
                        <select class="form-control form-control-lg" name="status">
                            <option value="1">Active</option>
                            <option value="0">Passive</option>

                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="bank_guid" value="{{ $bank->bank_guid }}">
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
    @if(Session::has('successBankUpdate'))
      toastr.success('Bank successfully updated.');
  @endif
  @if(Session::has('errorBankUpdate'))
      toastr.error('There is an error occured while updating please try again.');
  @endif
  @if(Session::has('errorRateAdd'))
      toastr.error('There is an error occured while updating please try again.');
  @endif


  @if(Session::has('rateSuccess'))
      toastr.success('Bank Rate successfully added.');
  @endif
  @if(Session::has('rateError'))
      toastr.error('There is an error occured while adding Bank Rate please try again.');
  @endif
  @if(Session::has('rateDelete'))
      toastr.success('Bank Rate successfully deleted.');
  @endif
  @if(Session::has('rateDeleteError'))
      toastr.error('There is an error occured while deleting Bank Rate please try again.');
  @endif

</script>
@endsection
