<form action="{{ route('admin.countries.update',$country->id) }}" method="POST">
    @csrf
    <div class="card-body py-4">
        <div class="row mt-5">
            <div class="col-lg-2 col-sm-2">
                <div class="form-group">
                    <label>Name</label>
                    <input disabled type="text" class="form-control" value="{{ $country->name }}" />
                </div>
            </div>
            <div class="col-lg-2 col-sm-2">
                <div class="form-group">
                    <label>Capital</label>
                    <input disabled type="text" class="form-control" value="{{ $country->capital }}" />
                </div>
            </div>
            <div class="col-lg-2 col-sm-2">
                <label>Currency</label>
                <div class="input-group">
                    <input disabled type="text" class="form-control" value="{{ $country->currency }}" />
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{ $country->currency_symbol }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-2">
                <div class="form-group">
                    <label>Region</label>
                    <input disabled type="text" class="form-control" value="{{ $country->region }}" />
                </div>
            </div>
            <div class="col-lg-2 col-sm-2">
                <div class="form-group">
                    <label>Phone Code</label>
                    <input name="phonecode" type="phone" class="form-control" value="{{ $country->phonecode }}" />
                </div>
            </div>
            <div class="col-lg-2 col-sm-2">
                <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" id="status" name="status">
                        <option {{ $country->status == 1 ? 'selected' : '' }} value="1">On</option>
                        <option {{ $country->status == 0 ? 'selected' : '' }} value="0">Off</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-end">
        <input name="country_guid" type="hidden" value="{{ $country->country_guid }}">
        <button type="submit" class="btn btn-primary mr-2">Save</button>
    </div>
</form>
