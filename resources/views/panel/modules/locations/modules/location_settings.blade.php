<form action="{{ route('admin.locations.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card-body py-4">
        <div class="row mt-5">

            @if (file_exists('storage/images/locations/pc_photo/'.$location->pc_photo))
            @if (!is_null($location->pc_photo))
            <div class="{{ is_null($location->mobile_photo) ? 'col-lg-12 col-sm-12' : 'col-lg-6 col-sm-6'}} ">
                <div class="form-group" style="text-align: -webkit-center;">
                    <label>Pc Photo</label>
                    <img class="form-control" style="width: 200px;height:200px;object-fit: contain;"
                        src="{{ secure_asset('storage/images/locations/pc_photo/'.$location->pc_photo) }}" alt="Pc Photo">
                </div>
            </div>
            @endif
            @endif

            @if (file_exists('storage/images/locations/mobile_photo/'.$location->mobile_photo))
            @if (!is_null($location->mobile_photo))
            <div class="{{ is_null($location->pc_photo) ? 'col-lg-12 col-sm-12' : 'col-lg-6 col-sm-6'}}">
                <div class="form-group" style="text-align: -webkit-center;">
                    <label>Mobile Photo</label>
                    <img class="form-control" style="width: 200px;height:200px;object-fit: contain;"
                        src="{{ secure_asset('storage/images/locations/mobile_photo/'.$location->mobile_photo) }}"
                        alt="Mobile Photo">
                </div>
            </div>
            @endif
            @endif

            <div class="col-lg-4 col-sm-4">
                <div class="form-group">
                    <label>Alias(EN)</label>
                    <input name="name_en" type="text" class="form-control" value="{{ $location->name_en }}" />
                </div>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class="form-group">
                    <label>Alias(AR)</label>
                    <input name="name_ar" type="text" class="form-control" value="{{ $location->name_ar }}" />
                </div>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" id="status" name="status">
                        <option {{ $location->status == 1 ? 'selected' : '' }} value="1">On</option>
                        <option {{ $location->status == 0 ? 'selected' : '' }} value="0">Off</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class="form-group">
                    <label>Location Pc Image</label>
                    <div class="custom-file">
                        <input name="pc_photo" type="file" class="custom-file-input" id="customFile" multiple
                            accept="image/png, image/gif, image/jpeg" />
                        <label class="custom-file-label" for="customFile">Choose Image</label>
                        <span class="form-text text-muted">Allowed file extensions: png, jpg, gif.
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class="form-group">
                    <label>Location Mobil Image</label>
                    <div class="custom-file">
                        <input name="mobile_photo" type="file" class="custom-file-input" id="customFile" multiple
                            accept="image/png, image/gif, image/jpeg" />
                        <label class="custom-file-label" for="customFile">Choose Image</label>
                        <span class="form-text text-muted">Allowed file extensions: png, jpg, gif.
                        </span>

                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class="form-group">
                    <label>Queue</label>
                    <select class="form-control" id="queue" name="queue">
                        @for ($i = 1; $i <= $locations_count; $i++) <option {{ $location->queue == $i ? 'selected' : ''
                            }} value="{{ $i }}">{{ $i }}</option>
                            @endfor

                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Choose what you want to update</label>
                    <div class="radio-inline">
                        <label class="radio">
                            <input id="country" value="country" type="radio" name="radios2" {{ $location->city_guid ==
                            null ?
                            'checked' : '' }}/>
                            <span></span>
                            Country
                        </label>
                        <label class="radio">
                            <input id="city" value="city" type="radio" name="radios2" {{ $location->city_guid != null ?
                            'checked' : '' }}/>
                            <span></span>
                            City
                        </label>
                    </div>
                </div>
            </div>
            <div id="select_country" class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Country<span class="text-danger">*</span></label>
                    <select class="form-control" id="countries" name="country_guid">
                        @foreach ($countries as $country)
                        <option {{ $country->country_guid == $location->country_guid ? 'selected' : '' }} value="{{
                            $country->country_guid }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div id="select_city" class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>City</label>
                    <select {{ $location->city_guid == null ? 'disabled' : '' }} class="form-control" id="cities"
                        name="city_guid">
                        <option value="">if you will add a city click on city</option>
                        @if ($location->city_guid != null)
                        @foreach ($cities as $city)
                        <option {{ $city->city_guid == $location->city_guid ? 'selected' : '' }} value="{{
                            $city->city_guid }}">{{ $city->name }}</option>
                        @endforeach
                        @endif

                    </select>
                </div>
            </div>
            <div id="currency" class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Currency</label>
                    <select class="form-control" id="currency" name="currency_guid">
                        @foreach ($currencies as $currency)
                        <option {{ $currency->currency_guid == $location->currency_guid ? 'selected' : '' }}
                            value="{{ $currency->currency_guid }}">{{ $currency->label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-end">
        <input name="location_guid" type="hidden" value="{{ $location->location_guid }}">
        <button type="submit" class="btn btn-primary mr-2">Save</button>
    </div>
</form>
