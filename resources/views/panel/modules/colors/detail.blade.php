<div class="row mt-5">
  <div class="col-lg-12">
    <div class="card card-custom">
      <div class="card-header">
        <div class="card-toolbar">
          <ul class="nav nav-light-success nav-bold nav-pills">
            <li class="nav-item">
              <a class="nav-link active generalSettings" data-toggle="tab" href="#general_settings">Color
                Detail</a>
            </li>
          </ul>
        </div>
      </div>
      <div class="card-body py-0">
        <div class="row mt-5">
          <div class="col-lg-12">
            <div class="example-preview">

              <div class="tab-content mt-5" id="myTabContent">
                <div class="tab-pane fade show active generalSettings" id="general_settings" role="tabpanel"
                  aria-labelledby="general_settings">
                  <form action="{{ route('admin.colors.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-5">
                      <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                          <label>Title(EN)</label>
                          <input type="text" class="form-control" name="name_en" value="{{ $color->name_en }}" />
                        </div>
                      </div>
                      <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                          <label>Title(AR)</label>
                          <input type="text" class="form-control" name="name_ar" value="{{ $color->name_ar }}" />
                        </div>
                      </div>
                    </div>
                    <div class="row mt-5">
                      <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                          <label>Type</label>
                          <select class="form-control" name="status">
                            <option {{ $color->status=='1' ? 'selected':'' }} value="1">Active
                            </option>
                            <option {{ $color->status=='0' ? 'selected':'' }} value="0">Passive
                            </option>
                          </select>
                        </div>
                      </div>
                    </div>


                    <div class="card-footer d-flex justify-content-end ">
                      <input name="color_guid" type="hidden" value="{{ $color->color_guid }}">
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


<!--Model add modal-->
<div class="modal fade" id="add_example_modal" data-backdrop="static" tabindex="-1" role="dialog"
  aria-labelledby="staticBackdrop" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form class="modal-content" method="post" action="">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Model Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <i aria-hidden="true" class="ki ki-close"></i>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Model Name</label>
          <input type="text" name="name_en" class="form-control form-control-lg" placeholder="Model Name" />
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="brand_guid" value="">
        <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary font-weight-bold">Save</button>
      </div>
    </form>
  </div>
</div>
@section('js')
<script>
  @if(Session::has('colorDetailUpdateSuccess'))
      toastr.success('Color successfully updated.');
  @endif
  @if(Session::has('colorDetailUpdateError'))
      toastr.error('There is an error occurred while updating color please try again.');
  @endif
</script>
@endsection