<div class="row mt-5">
    <div class="col-lg-12">
        <form method="post" action="{{ route('admin.advm.update') }}" class="card card-custom" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="adv_guid" value="{{ $adv->adv_guid }}">
            <div class="card-header">
                <div class="card-toolbar">
                    <ul class="nav nav-light-success nav-bold nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active models" data-toggle="tab" href="#rates">Advertise Detail</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body py-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="example-preview">
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>Adv Image</label>
                                    <div style="height: 200px; width: 300px; margin-bottom: 10px">
                                        <img id="advImg" class="w-100" src="{{ secure_asset('storage/adv/'.$adv->image) }}" alt="Logo" />
                                    </div>
                                    <div class="custom-file">
                                        <input name="image" type="file" class="custom-file-input" id="imgInp2" accept="image/png, image/jpeg" />
                                        <label class="custom-file-label" for="customFile">Choose Image</label>
                                        <span class="form-text text-muted">Allowed file extensions: png, jpg.</span>
                                    </div>
                                    <small class="form-text text-muted">Required</small>
                                </div>
                                <div class="form-group col-6">
                                    <label>Link</label>
                                    <input type="text" name="link" class="form-control form-control-lg" value="{{ $adv->link }}" placeholder="Adv Link" />
                                </div>
                                <div class="form-group col-6">
                                    <label>Page URL</label>
                                    <input type="text" name="page_url" class="form-control form-control-lg" value="{{ $adv->page_url }}" placeholder="selection" required />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end ">
                <button type="button" class="btn btn-light-danger font-weight-bold mr-3" data-dismiss="modal">Cancel</button>
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
          $('#advImg').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
  }
}

$("#imgInp2").change(function(){
  readURL2(this);
});
</script>
<script>
    @if(Session::has('advUpdateSuccess'))
        toastr.success('Advertise successfully updated.');
    @endif
    @if(Session::has('advUpdateError'))
        toastr.error('There is an error occured while updating. Please try again.');
    @endif
</script>
@endsection
