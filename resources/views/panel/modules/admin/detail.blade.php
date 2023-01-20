<div class="row mt-5">
  <div class="col-lg-12">
    <div class="card card-custom">
      <div class="card-header">
        <div class="card-toolbar">
          <ul class="nav nav-light-success nav-bold nav-pills">
            <li class="nav-item">
              <a class="nav-link active generalSettings" data-toggle="tab" href="#general_settings">Admin
                Detail</a>
            </li>
            <li class="nav-item">
              <a class="nav-link change_password" data-toggle="tab" href="#change_password">Password Change</a>
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
                  <form action="{{ route('admin.admin.detail_update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-5">
                      <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                          <label>Name</label>
                          <input type="text" class="form-control" name="name" value="{{ $admin->name }}" />
                        </div>
                      </div>
                      <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                          <label>Username</label>
                          <input type="text" class="form-control" name="username" value="{{ $admin->username }}" />
                        </div>
                      </div>
                    </div>
                    <div class="row mt-5">
                      <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                          <label>Email</label>
                          <input type="text" class="form-control" name="email" value="{{ $admin->email }}" />
                        </div>
                      </div>
                      @if (Auth::user()->type=='s')
                      <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                          <label>Type</label>
                          <select class="form-control" name="type">
                            <option {{ $admin->type == 's' ? 'selected':'' }} value="s">Super Admin</option>
                            <option {{ $admin->type == 'n' ? 'selected':'' }} value="n">Normal Admin</option>
                          </select>
                        </div>
                      </div>
                      @endif
                    </div>
                    <div class="card-footer d-flex justify-content-end ">
                      <input name="id" type="hidden" value="{{ $admin->id }}">
                      <button type="submit" class="btn btn-primary mr-2">Save</button>
                    </div>
                  </form>
                </div>
                <div class="tab-pane fade change_password" id="change_password" role="tabpanel"
                  aria-labelledby="change_password">
                  <form action="{{ route('admin.admin.update_password') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-5">
                      <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                          <label>Old Password</label>
                          <input type="password" class="form-control" name="old_password"/>
                        </div>
                      </div>
                      <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                          <label>New Password</label>
                          <input type="password" class="form-control" name="new_password"/>
                        </div>
                      </div>
                    </div>
                    <div class="row mt-5">
                      <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                          <label>New Password Again</label>
                          <input type="password" class="form-control" name="re_new_password" />
                        </div>
                      </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end ">
                      <input name="id" type="hidden" value="{{ $admin->id }}">
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
  @if(Session::has('successAdminUpdate'))
      toastr.success('Admin successfully updated.');
  @endif
  @if(Session::has('errorAdminUpdate'))
      toastr.error('There is an error occured while updating Admin please try again.');
  @endif
  
  @if(Session::has('adminPasswordError'))
      toastr.error('There is an error occured while updating Admin Password check fields again.');
  @endif
  
  @if(Session::has('adminPasswordSuccess'))
      toastr.success('Your password updated successfully.');
  @endif
</script>
@endsection