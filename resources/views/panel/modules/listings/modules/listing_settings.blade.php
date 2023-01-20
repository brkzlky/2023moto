<form action="{{ route('admin.listings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card-body py-4">
        <div class="row mt-5">
            <div class="col-lg-12 col-sm-12">
                <div class="form-group">
                    <h3><a style="color: black" href="{{ route('admin.users.detail',$listing->user->user_guid) }}"
                            target="_blank">{{
                            $listing->user->name
                            }}</a></h3>
                </div>
            </div>
            <div class=" col-lg-6 col-sm-6">
                <div class="form-group">
                    <label>Name(EN)</label>
                    <input name="name_en" type="text" class="form-control" value="{{ $listing->name_en }}" />
                </div>
            </div>
            <div class="col-lg-6 col-sm-6">
                <div class="form-group">
                    <label>Name(AR)</label>
                    <input name="name_ar" type="text" class="form-control" value="{{ $listing->name_ar }}" />
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Expire</label>
                    <input disabled type="date" class="form-control"
                        value="{{ date('Y-m-d', strtotime($listing->expire_at)) }}">
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <label>Price</label>
                <div class="input-group">
                    <input name="price" type="text" class="form-control" value="{{ $listing->price }}" />
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{ $listing->currency->symbol }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" id="status" name="status">
                        <option {{ $listing->status == 1 ? 'selected' : '' }} value="1">On</option>
                        <option {{ $listing->status == 0 ? 'selected' : '' }} value="0">Off</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Category</label>
                    <select class="form-control" id="category_guid" name="category_guid">
                        @foreach ($categories as $category)
                        @if ($category->related_to == null)
                        <option {{ $category->category_guid == $listing->category_guid ? 'selected' : '' }}
                            value="{{
                            $category->category_guid }}">{{ $category->name_en }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Subcategory</label>
                    <select class="form-control" id="subcategory_guid" name="subcategory_guid">
                        <option value="">Please Select Subcategory</option>
                        @foreach ($categories as $category)
                        @if ($category->related_to != null && $category->related_to == $listing->category_guid )
                        <option {{ $category->category_guid == $listing->subcategory_guid ? 'selected' : '' }}
                            value="{{
                            $category->category_guid }}">{{ $category->name_en }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Location</label>
                    <select class="form-control" id="status" name="location_guid">
                        @foreach ($locations as $location)
                        <option {{ $location->location_guid == $listing->location_guid ? 'selected' : '' }}
                            value="{{
                            $location->location_guid }}">{{ $location->name_en }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Viewed</label>
                    <input disabled type="text" class="form-control" value="{{ $listing->viewed }}" />
                </div>
            </div>

            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Mileage</label>
                    <input name="mileage" type="text" class="form-control" value="{{ $listing->mileage }}" />
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Color</label>
                    <select class="form-control" id="color_guid" name="color_guid">
                        @foreach ($colors as $color)
                        <option {{ $listing->color->color_guid == $color->color_guid ? 'selected' : '' }} value="{{
                            $color->color_guid }}">{{ $color->name_en }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Fuel Type</label>
                    <select class="form-control" id="fuel_type" name="fuel_type">
                        <option {{ $listing->fuel_type == 'gasoline' ? 'selected' : '' }} value="gasoline">Gasoline
                        </option>
                        <option {{ $listing->fuel_type == 'diesel' ? 'selected' : '' }} value="diesel">Diesel
                        </option>
                        <option {{ $listing->fuel_type == 'lpg' ? 'selected' : '' }} value="lpg">Lpg
                        </option>
                        <option {{ $listing->fuel_type == 'electric' ? 'selected' : '' }} value="electric">Electric
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Situation</label>
                    <select class="form-control" id="situation" name="situation">
                        <option {{ $listing->situation == 'used' ? 'selected' : '' }} value="used">Used
                        </option>
                        <option {{ $listing->situation == 'new' ? 'selected' : '' }} value="new">New
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Listing Type</label>
                    <select class="form-control" id="listing_type" name="listing_type">
                        <option {{ $listing->listing_type == 'sell' ? 'selected' : '' }} value="sell">Sell
                        </option>
                        <option {{ $listing->listing_type == 'rent' ? 'selected' : '' }} value="rent">Rent
                        </option>
                    </select>
                </div>
            </div>
            @if (isset($listing->brand))
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Brand<span class="text-danger">*</span></label>
                    <select class="form-control" id="brand" name="brand_guid">
                        @foreach ($brands as $brand)
                        <option {{ $listing->brand->brand_guid == $brand->brand_guid ? 'selected' : '' }} value="{{
                            $brand->brand_guid }}">{{ $brand->name_en }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @if (isset($listing->model))
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label id="model_spinner">Model<span class="text-danger">*</span></label>
                    <select disabled class="form-control" id="model" name="model_guid">
                        @foreach ($models as $model)
                        <option {{ $listing->model->model_guid == $model->model_guid ? 'selected' : '' }} value="{{
                            $model->model_guid }}">{{ $model->name_en }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Year<span class="text-danger">*</span></label>
                    <select disabled class="form-control" id="year" name="year">
                        @for ($i = $now->format('Y'); $i >= 1990; $i--) <option {{ $listing->year == $i ? 'selected' :
                            '' }} value="{{ $i }}">{{ $i }}
                        </option>
                        @endfor
                    </select>
                </div>
            </div>
            @if (isset($listing->trim))
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label id="trim_spinner">Trim</label>
                    <select disabled class="form-control" id="trim" name="trim_guid">
                        @foreach ($trims as $trim)
                        <option {{ $listing->trim->trim_guid == $trim->trim_guid ? 'selected' : '' }} value="{{
                            $trim->trim_guid }}">{{ $trim->name_en }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @else
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label id="trim_spinner">Trim</label>
                    <select disabled class="form-control" id="trim" name="trim_guid">
                        <option value="">No trim entered</option>
                    </select>
                </div>
            </div>
            @endif
            @endif
            @endif
            <div class="col-lg-12 col-sm-12">
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="summernote2">{{ $listing->description }}</textarea>
                </div>
            </div>
            <div class="col-lg-12 col-sm-12">
                <div class="form-group">
                    <label>Map</label>
                    <div id="gmap" style="height:330px;" class="gmap"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-end">
        <input name="listing_guid" type="hidden" value="{{ $listing->listing_guid }}">
        <input  id="latitude" name="latitude" type="hidden" class="form-control" value="{{ $listing->latitude }}" />
        <input  id="longitude" name="longitude" type="hidden" class="form-control" value="{{ $listing->longitude }}" />
        <button type="submit" class="btn btn-primary mr-2">Save</button>
    </div>
</form>
