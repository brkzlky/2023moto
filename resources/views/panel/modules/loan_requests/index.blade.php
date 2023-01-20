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
                    <a class="nav-link active models" data-toggle="tab" href="#rates">Loan Requests
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
                          @if (count($loan_requests)>0)


                          <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                            <thead>
                              <tr class="text-left">
                                <th style="min-width: 140px">Id</th>
                                <th style="min-width: 140px">Full Name</th>
                                <th style="min-width: 150px">Identity</th>
                                <th style="min-width: 150px">E-Mail</th>
                                <th style="min-width: 150px">Phone</th>
                                <th style="min-width: 150px">Loan</th>
                                <th style="min-width: 150px">Detail/Delete</th>
                              </tr>
                            </thead>
                            <tbody>
                              <!--begin::Form-->
                              @foreach ($loan_requests as $l)
                              <tr>
                                <td>
                                  <span class="text-dark-75 d-block font-size-lg">{{ $l->id }}</span>
                                </td>
                                <td>
                                  <span class="text-dark-75 d-block font-size-lg">{{ $l->fullname }}</span>
                                </td>
                                <td>
                                  <span class="text-dark-75 d-block font-size-lg">{{ $l->identity }}</span>
                                </td>
                                <td>
                                  <span class="text-dark-75 d-block font-size-lg">{{ $l->email }}</span>
                                </td>
                                <td>
                                  <span class="text-dark-75 d-block font-size-lg">{{ $l->phone }}</span>
                                </td>
                                <td>
                                  <span class="text-dark-75 d-block font-size-lg">{{ $l->loan }}</span>
                                </td>
                                <td>
                                  <span class="text-dark-75 d-block font-size-lg">
                                    <a href="{{ route('admin.loan.detail',$l->loan_request_guid) }}"
                                      class="btn btn-icon btn-success">
                                      <i class="flaticon-eye"></i>
                                    </a>
                                    <button data-toggle="modal" data-target="#delete_{{ $l->loan_request_guid }}"
                                      type="button" class="btn btn-icon btn-danger delete_btns">
                                      <i class="flaticon2-trash"></i>
                                    </button>
                                  </span>
                                </td>
                              </tr>
                              <div class="modal fade" id="delete_{{ $l->loan_request_guid }}" data-backdrop="static"
                                tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                                <div class="modal-dialog modal-content" role="document">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">
                                      Loan Request
                                      Delete
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <i aria-hidden="true" class="ki ki-close"></i>
                                    </button>
                                  </div>
                                  <div class="modal-body" style="text-align: center">
                                    <h4>Are you sure deleting
                                      <strong>#{{ $l->id }}</strong>
                                      loan request ?
                                    </h4>
                                  </div>
                                  <form class="w-100" method="post" action="{{ route('admin.loan.delete') }}">
                                    @csrf
                                    <div class="modal-footer">
                                      <input type="hidden" name="loan_request_guid" id="example_guide"
                                        value="{{ $l->loan_request_guid }}">
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
                            No Loan Requests to Show.</span>
                          @endif
                        </div>

                      </div>
                      <div class="tab-pane fade  generalSettings" id="general_settings" role="tabpanel"
                        aria-labelledby="general_settings">
                        <form action="{{ route('admin.example_excel') }}" method="POST" enctype="multipart/form-data">
                          @csrf
                          <div class="row mt-5">
                            <div class="col-lg-6 col-sm-6">
                              <div class="form-group">
                                <label>Bank Logo </label><br>
                                <img id="blah2" style="height: 200px; width: 300px;" src="" alt="Logo" />
                                <div class="custom-file">
                                  <input name="file" type="file" class="custom-file-input" id="imgInp2" />
                                  <label class="custom-file-label" for="customFile">Choose
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
                            <a href="{{ url()->previous() }}" class="btn btn-danger mr-2">Back</a>
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
            <input type="text" name="title_en" class="form-control form-control-lg" placeholder="Setting Title(EN)" />
            <small class="form-text text-muted">Required</small>
          </div>
          <div class="form-group col-6">
            <label>Setting Title(AR)</label>
            <input type="text" name="title_ar" class="form-control form-control-lg" placeholder="Setting Title(AR)" />
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
  @if(Session::has('loanDeleteSuccess'))
  toastr.success('Loan successfully deleted.');
@endif
@if(Session::has('loanDeleteError'))
  toastr.error('There is an error occured while deleting loan request please try again.');
@endif




</script>
@endsection