<form action="{{ route('admin.categories.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card-body py-4">
        <div class="row mt-5">
            @if (file_exists('storage/images/categories/'.$category->image))
            @if (!is_null($category->image))
            <div class="col-lg-12 col-sm-12">
                <div class="form-group" style="text-align: -webkit-center;">
                    <label>Image</label>
                    <img class="form-control" style="width: 200px;height:200px;object-fit: contain;"
                        src="{{ secure_asset('storage/images/categories/'.$category->image) }}" alt="Category Image">

                </div>
            </div>
            @endif
            @endif

            <div class="col-lg-4 col-sm-4">
                <div class="form-group">
                    <label>Category Name(EN)</label>
                    <input name="name_en" type="text" class="form-control" value="{{ $category->name_en }}" />
                </div>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class="form-group">
                    <label>Category Name(AR)</label>
                    <input name="name_ar" type="text" class="form-control" value="{{ $category->name_ar }}" />
                </div>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class="form-group">
                    <label>Image</label>
                    <div class="custom-file">
                        <input name="image" type="file" class="custom-file-input" id="customFile" multiple
                            accept="image/png, image/gif, image/jpeg" />
                        <label class="custom-file-label" for="customFile">Choose Image</label>
                        <span class="form-text text-muted">Allowed file extensions: png, jpg, gif.
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" id="status" name="status">
                        <option {{ $category->status  == 1 ? 'selected' : '' }} value="1">On</option>
                        <option {{ $category->status == 0 ? 'selected' : '' }} value="0">Off</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class="form-group">
                    <label>Parent</label>
                    <select class="form-control" id="related_to" name="related_to">
                        <option {{ $category->related_to == null ? 'selected' : '' }} value="" selected>
                            Parent Category
                        </option>
                        @foreach ($categories as $category_parent)

                        @if ($category_parent->category_guid != $category->category_guid && $category_parent->status ==
                        1)
                        <option
                            {{ $category->related_to != null &&  $category->related_to == $category_parent->category_guid ? 'selected' : '' }}
                            value="{{ $category_parent->category_guid }}">
                            {{ $category_parent->name_en }}
                        </option>
                        @endif

                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class="form-group">
                    <label>Queue</label>
                    <select class="form-control" id="queue" name="queue">
                        @for ($i = 1; $i <= $count_parent; $i++) <option
                            {{ $category->queue  == $count_parent ? 'selected' : '' }} value="{{ $i }}">{{ $i }}
                            </option>
                            @endfor
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-end">
        <input name="category_guid" type="hidden" value="{{ $category->category_guid }}">
        <button type="submit" class="btn btn-primary mr-2">Save</button>
    </div>
</form>
