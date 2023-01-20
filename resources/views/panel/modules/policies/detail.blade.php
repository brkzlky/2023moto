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
                    <form action="{{ route('admin.policies.update') }}" method="POST" enctype="multipart/form-data">
                      @csrf
                      <div class="row mt-5">
                        <div class="col-lg-6 col-sm-6">
                          <div class="form-group">
                            <label>Title (EN)</label>
                            <input type="text" class="form-control" value="{{ $policy->title_en }}" />
                          </div>
                        </div>
                        <div class="col-lg-6 col-sm-6">
                          <div class="form-group">
                            <label>Title (AR)</label>
                            <input type="text" class="form-control" value="{{ $policy->title_ar }}" />
                          </div>
                        </div>
                      </div>
                      <div class="row mt-5">
                        <div class="col-lg-12 col-sm-12">
                          <div class="form-group">
                            <label>Policy Text</label>
                            <textarea name="text" class="summernote2">{{ $policy->text }}</textarea>
                          </div>
                        </div>
                      </div>
                      
                      <div class="card-footer d-flex justify-content-end ">
                        <input name="policy_guid" type="hidden" value="{{ $policy->policy_guid }}">
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
        toastr.success('Policy detail successfully updated.');
    @endif
    @if(Session::has('updateError'))
        toastr.error('There is an error occurred while updating policy. Please try again.');
    @endif
  </script>
  @endsection