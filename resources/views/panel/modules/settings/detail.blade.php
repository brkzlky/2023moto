<div class="row mt-5">
  <div class="col-lg-12">
    <div class="card card-custom">
      <div class="card-header">
        <div class="card-toolbar">
          <ul class="nav nav-light-success nav-bold nav-pills">
            <li class="nav-item">
              <a class="nav-link active generalSettings" data-toggle="tab" href="#general_settings">Setting
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
                  <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mt-5">
                      <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                          <label>Title(EN)</label>
                          <input type="text" class="form-control" name="title_en" value="{{ $setting->title_en }}" />
                        </div>
                      </div>
                      <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                          <label>Title(AR)</label>
                          <input type="text" class="form-control" name="title_ar" value="{{ $setting->title_ar }}" />
                        </div>
                      </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-lg-6 col-sm-6">
                            <div class="form-group">
                              <label>Type</label>
                             <select class="form-control"  name="input_type" >
                              <option {{ $setting->input_type=='text' ? 'selected':'' }} value="text">Text
                              </option>
                              <option {{ $setting->input_type=='textarea' ? 'selected':'' }} value="textarea">TextArea
                              </option>
                              <option {{ $setting->input_type=='switch' ? 'selected':'' }} value="switch">Switch
                              </option>
                              <option {{ $setting->input_type=='editor' ? 'selected':'' }} value="editor">Editor
                              </option>
                             </select>
                            </div>
                        </div>

                      <div class="col-lg-6 col-sm-6">
                        <div class="form-group">
                          <label>Type</label>
                         <select class="form-control"  name="setting_type" >
                          <option {{ $setting->setting_type=='site' ? 'selected':'' }} value="site">Site
                          </option>
                          <option {{ $setting->setting_type=='email' ? 'selected':'' }} value="email">Email
                          </option>
                          <option {{ $setting->setting_type=='api' ? 'selected':'' }} value="api">API
                          </option>
                          <option {{ $setting->setting_type=='mobile' ? 'selected':'' }} value="mobile">Mobile
                          </option>
                         </select>
                        </div>
                      </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-lg-12 col-sm-12">
                            <div class="form-group">
                              <label>Setting Value</label>
                              @if($setting->input_type == 'text')
                              <input type="text" class="form-control" name="setting_value" value="{{ $setting->setting_value }}" />
                              @endif
                              @if($setting->input_type == 'textarea')
                              <textarea name="setting_value" class="form-control summernote2" rows="10">{!! $setting->setting_value !!}</textarea>
                              @endif
                            </div>
                          </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end ">
                      <input name="setting_guid" type="hidden" value="{{ $setting->setting_guid }}">
                      <button type="submit" class="btn btn-primary mr-2">Save</button>
                    </div>
                  </form>
                </div>
                <div class="tab-pane fade change_password" id="change_password" role="tabpanel"
                  aria-labelledby="change_password">
                  <form action="" method="POST" enctype="multipart/form-data">
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
                      <input name="setting_guid" type="hidden" value="{{ $setting->setting_guid }}">
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
    $('.summernote2').summernote({
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

  @if(Session::has('updateSuccess'))
      toastr.success('Setting successfully updated.');
  @endif
  @if(Session::has('updateError'))
      toastr.error('There is an error occurred while updating setting please try again.');
  @endif
</script>
@endsection
