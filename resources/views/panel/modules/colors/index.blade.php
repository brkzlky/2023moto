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
                    <a class="nav-link active models" data-toggle="tab" href="#rates">Colors
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
                      <div class="tab-pane fade show active rates" id="rates" role="tabpanel" aria-labelledby="rates">
                        <div class="table-responsive">
                          @if (count($colors)>0)


                          <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                            <thead>
                              <tr class="text-left">
                                <th style="min-width: 140px">Id</th>
                                <th style="min-width: 140px">Name(EN)</th>
                                <th style="min-width: 150px">Name(AR)</th>
                                <th style="min-width: 150px">Status Type</th>
                                <th style="min-width: 150px">Detail/Delete</th>
                              </tr>
                            </thead>
                            <tbody>
                              <!--begin::Form-->
                              @foreach ($colors as $c)

                              <tr>
                                <td>
                                  <span class="text-dark-75 d-block font-size-lg">{{ $c->id }}</span>
                                </td>
                                <td>
                                  <span class="text-dark-75 d-block font-size-lg">{{ $c->name_en }}</span>
                                </td>
                                <td>
                                  <span class="text-dark-75 d-block font-size-lg">{{ $c->name_ar }}</span>

                                </td>
                                <td>
                                  @if($c->status == '1')
                                  <span class="label label-lg label-light-success label-inline">Active</span>
                                  @else
                                  <span class="label label-lg label-light-danger label-inline">Passive</span>
                                  @endif

                                </td>
                                <td>
                                  <span class="text-dark-75 d-block font-size-lg">
                                    <a href="{{ route('admin.colors.detail',$c->color_guid) }}" class="btn btn-icon btn-success">
                                      <i class="flaticon-eye"></i>
                                    </a>
                                    <button data-toggle="modal" data-target="#delete_{{ $c->color_guid }}" type="button"
                                      class="btn btn-icon btn-danger delete_btns">
                                      <i class="flaticon2-trash"></i>
                                    </button>
                                  </span>
                                </td>
                              </tr>
                              <div class="modal fade" id="delete_{{ $c->color_guid }}" data-backdrop="static" tabindex="-1" role="dialog"
                                aria-labelledby="staticBackdrop" aria-hidden="true">
                                <div class="modal-dialog modal-content" role="document">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">
                                      Color
                                      Delete
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <i aria-hidden="true" class="ki ki-close"></i>
                                    </button>
                                  </div>
                                  <div class="modal-body" style="text-align: center">
                                    <h4>Are you sure deleting
                                      <strong>#{{ $c->name_en }}</strong>
                                      setting ?
                                    </h4>
                                  </div>
                                  <form class="w-100" method="post" action="{{ route('admin.colors.delete') }}">
                                    @csrf
                                    <div class="modal-footer">
                                      <input type="hidden" name="color_guid" id="example_guide" value="{{ $c->color_guid }}">
                                      <button type="button" class="btn btn-light-danger font-weight-bold"
                                        data-dismiss="modal">Cancel</button>
                                      <button type="submit" class="btn btn-primary font-weight-bold">Confirm</button>
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
                            No Color Added.</span>
                          @endif

                          <div class="card-footer d-flex justify-content-end ">
                            <button type="submit" class="btn btn-primary mr-2" data-toggle="modal"
                              data-target="#add_example_modal">Add
                              Color</button>
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
    <form class="modal-content" method="post" action="{{ route('admin.colors.add') }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Color Add</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <i aria-hidden="true" class="ki ki-close"></i>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="form-group col-6">
            <label>Name(EN)</label>
            <input type="text" name="name_en" class="form-control form-control-lg" placeholder="Name(EN)" required />
            <small class="form-text text-muted">Required</small>
          </div>
          <div class="form-group col-6">
            <label>Name(AR)</label>
            <input type="text" name="name_ar" class="form-control form-control-lg" placeholder="Name(AR)" />
          </div>
        </div>
        <div class="row">
          <div class="form-group col-6">
            <label>Setting Type</label>
            <select name="status" class="form-control form-control-lg">
              <option value="1">Active
              </option>
              <option value="0">Passive
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
  @if(Session::has('errorColorAdd'))
  toastr.error('There is an error occurred while adding color please try again.');
@endif
@if(Session::has('successColorAdd'))
  toastr.success('Color added successfully.');
@endif
@if(Session::has('errorDeleteColor'))
  toastr.error('There is an error occurred while deleting color please try again.');
@endif
@if(Session::has('successDeleteColor'))
  toastr.success('Color deleted successfully.');
@endif
</script>
@endsection