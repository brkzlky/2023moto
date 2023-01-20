<div class="row mt-5">
    <div class="col-lg-12">
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-toolbar">
                    <ul class="nav nav-light-success nav-bold nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active models" data-toggle="tab" href="#rates">Advertise List</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card-body py-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="example-preview">
                            @if (count($advertisements)>0)
                            <table class="table table-head-custom table-vertical-center"
                                id="kt_advance_table_widget_1">
                                <thead>
                                    <tr class="text-left">
                                        <th style="min-width: 140px">#</th>
                                        <th style="min-width: 140px">Image</th>
                                        <th style="min-width: 150px">Link</th>
                                        <th style="min-width: 150px">Page</th>
                                        <th style="min-width: 150px">Detail/Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!--begin::Form-->
                                    @foreach ($advertisements as $adv)
                                    <tr>
                                        <td>
                                            <span class="text-dark-75 d-block font-size-lg">
                                                {{$loop->iteration }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-dark-75 d-block font-size-lg">
                                                <img src="{{ asset('storage/adv/'.$adv->image) }}" style="width: 220px; object-fit: contain" />
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-dark-75 d-block font-size-lg">
                                                {{ !is_null($adv->link) ? $adv->link : 'No Link' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-dark-75 d-block font-size-lg">
                                                {{$adv->page_url }}
                                            </span>

                                        </td>
                                        <td>
                                            <span class="text-dark-75 d-block font-size-lg">
                                                <a href="{{ route('admin.advm.detail',$adv->adv_guid) }}" class="btn btn-icon btn-success">
                                                    <i class="flaticon-eye"></i>
                                                </a>
                                                <button data-toggle="modal"
                                                    data-target="#delete_{{ $adv->adv_guid }}"
                                                    type="button"
                                                    class="btn btn-icon btn-danger delete_btns">
                                                    <i class="flaticon2-trash"></i>
                                                </button>
                                            </span>
                                        </td>
                                    </tr>
                                    <div class="modal fade" id="delete_{{ $adv->adv_guid }}"
                                        data-backdrop="static" tabindex="-1" role="dialog"
                                        aria-labelledby="staticBackdrop" aria-hidden="true">
                                        <div class="modal-dialog modal-content" role="document">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">
                                                    Delete Advertise
                                                </h5>
                                                <button type="button" class="close"
                                                    data-dismiss="modal" aria-label="Close">
                                                    <i aria-hidden="true"
                                                        class="ki ki-close"></i>
                                                </button>
                                            </div>
                                            <div class="modal-body" style="text-align: center">
                                                <h4>You are deleting the adv. Do you want to continue?
                                                </h4>
                                            </div>
                                            <form class="w-100" method="post"
                                                action="{{ route('admin.advm.delete') }}">
                                                @csrf
                                                <div class="modal-footer">
                                                    <input type="hidden" name="adv_guid"
                                                        id="example_guide"
                                                        value="{{ $adv->adv_guid }}">
                                                    <button type="button"
                                                        class="btn btn-light-danger font-weight-bold"
                                                        data-dismiss="modal">Cancel</button>
                                                    <button type="submit"
                                                        class="btn btn-primary font-weight-bold">Confirm</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    @endforeach
                                    <br>
                                    <br>
                                </tbody>
                            </table>
                            @else
                            <div style="min-height: 240px">
                                <span class="text-dark-75 d-block font-size-lg text-lg-center">
                                    No Adv Added.
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end ">
                <button type="submit" class="btn btn-primary mr-2"
                    data-toggle="modal" data-target="#add_adv">Add
                    Setting</button>
            </div>
        </div>
    </div>
</div>


<!--Model add modal-->
<div class="modal fade" id="add_adv" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form class="modal-content" method="post" action="{{ route('admin.advm.add') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Advertise</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-12">
                        <label>Adv Image</label>
                        <div style="height: 200px; width: 300px; margin-bottom: 10px">
                            <img id="advImg" class="w-100" src="{{ secure_asset('panel/assets/media/adv-sample.jpg') }}" alt="Logo" />
                        </div>
                        <div class="custom-file">
                            <input name="image" type="file" class="custom-file-input" id="imgInp2" accept="image/png, image/jpeg" required />
                            <label class="custom-file-label" for="customFile">Choose Image</label>
                            <span class="form-text text-muted">Allowed file extensions: png, jpg.</span>
                        </div>
                        <small class="form-text text-muted">Required</small>
                    </div>
                    <div class="form-group col-6">
                        <label>Link</label>
                        <input type="text" name="link" class="form-control form-control-lg" placeholder="Adv Link" />
                    </div>
                    <div class="form-group col-6">
                        <label>Page URL</label>
                        <input type="text" name="page_url" class="form-control form-control-lg" placeholder="selection" required />
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
@if(Session::has('success'))
    toastr.success('Advertise successfully added.');
@endif
@if(Session::has('error'))
    toastr.error('There is an error occurred during operation. Please try again.');
@endif
@if(Session::has('advDeleteSuccess'))
    toastr.success('Advertise successfully deleted.');
@endif
@if(Session::has('advDeleteError'))
    toastr.error('There is an error occured while deleting adv. Please try again.');
@endif




</script>
@endsection
