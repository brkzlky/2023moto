<!-- List Begin -->

<div class="row mt-5">

    <div class="col-lg-12">

        <div class="card card-custom">
            <div class="card-header">
                <h3 class="card-title">
                    {{ $user_type->name_en }} Detail
                </h3>
            </div>
            <!--begin::Form-->
            <form action="{{ route('admin.user_types.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body py-4">
                    <div class="row mt-5">
                        <div class="col-lg-6 col-sm-6">
                            <div class="form-group">
                                <label>User type Name(EN)</label>
                                <input name="name_en" type="text" class="form-control"
                                    value="{{ $user_type->name_en }}" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6">
                            <div class="form-group">
                                <label>User type Name(AR)</label>
                                <input name="name_ar" type="text" class="form-control"
                                    value="{{ $user_type->name_ar }}" />
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-4">
                            <div class="form-group">
                                <label>Free Listing</label>
                                <input name="free_listing" type="text" class="form-control"
                                    value="{{ $user_type->free_listing }}" />
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-4">
                            <div class="form-group">
                                <label>Free Listing Period</label>
                                <select class="form-control" id="free_listing_period" name="free_listing_period">
                                    <option {{ $user_type->free_listing_period == 'h' ? 'selected' : '' }} value="h">
                                        Hour</option>
                                    <option {{ $user_type->free_listing_period == 'd' ? 'selected' : '' }} value="d">Day
                                    </option>
                                    <option {{ $user_type->free_listing_period == 'w' ? 'selected' : '' }} value="w">
                                        Week</option>
                                    <option {{ $user_type->free_listing_period == 'm' ? 'selected' : '' }} value="m">
                                        Month</option>
                                    <option {{ $user_type->free_listing_period == 'y' ? 'selected' : '' }} value="y">
                                        Year</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-4">
                            <div class="form-group">
                                <label>Statuss</label>
                                <select class="form-control" id="status" name="status">
                                    <option {{ $user_type->status  == 1 ? 'selected' : '' }} value="1">On</option>
                                    <option {{ $user_type->status == 0 ? 'selected' : '' }} value="0">Off</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <input name="type_guid" type="hidden" value="{{ $user_type->type_guid }}">
                    <button type="submit" class="btn btn-primary mr-2">Save</button>
                </div>
            </form>
            <!--end::Form-->
        </div>
    </div>
</div>

<!-- List End -->

@section('js')
<script>
    @if(Session::has('errorValidate'))
        toastr.error('Please enter at least 2 letters.');
    @endif
    @if(Session::has('successUpdateUserTypes'))
        toastr.success('User type successfully updated.');
    @endif
</script>
@endsection
