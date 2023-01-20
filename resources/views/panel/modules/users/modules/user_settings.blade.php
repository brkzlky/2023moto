<form action="{{ route('admin.users.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card-body py-4">
        <div class="row mt-5">
            @if ($user->type_guid == '5d840a0f-c539-4257-955d-a375215ea307')
            @if (file_exists('storage/images/users/logo/'.$user->logo))
            @if (!is_null($user->logo))
            <div class="{{ is_null($user->background) ? 'col-lg-12 col-sm-12' : 'col-lg-6 col-sm-6'}} ">
                <div class="form-group" style="text-align: -webkit-center;">
                    <label>Logo</label>
                    <img class="form-control" style="width: 200px;height:200px;object-fit: contain;"
                        src="{{ secure_asset('storage/images/users/logo/'.$user->logo) }}" alt="Pc Photo">
                </div>
            </div>
            @endif
            @endif

            @if (file_exists('storage/images/users/background/'.$user->background))
            @if (!is_null($user->background))
            <div class="{{ is_null($user->logo) ? 'col-lg-12 col-sm-12' : 'col-lg-6 col-sm-6'}}">
                <div class="form-group" style="text-align: -webkit-center;">
                    <label>Background</label>
                    <img class="form-control" style="width: 200px;height:200px;object-fit: contain;"
                        src="{{ secure_asset('storage/images/users/background/'.$user->background) }}" alt="Mobile Photo">
                </div>
            </div>
            @endif
            @endif
            @endif
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Name</label>
                    <input name="name" type="text" class="form-control" value="{{ $user->name }}" />
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Phone</label>
                    <input name="phone" type="text" class="form-control" value="{{ $user->phone }}" />
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Whatsapp</label>
                    <input name="whatsapp" type="text" class="form-control" value="{{ $user->whatsapp }}" />
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Email</label>
                    <input name="email" type="email" class="form-control" value="{{ $user->email }}" />
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Country</label>
                    <select class="form-control" id="country_guid" name="country_guid">
                        @foreach ($countries as $country)
                        <option {{ $country->country_guid == $user->country_guid ? 'selected' : '' }} value="{{
                            $country->country_guid }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" id="status" name="status">
                        <option {{ $user->status == 1 ? 'selected' : '' }} value="1">On</option>
                        <option {{ $user->status == 0 ? 'selected' : '' }} value="0">Off</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>User Type</label>
                    <select class="form-control" id="type_guid" name="type_guid">
                        @foreach ($user_types as $user_type)
                        <option {{ $user->type->type_guid == $user_type->type_guid ? 'selected' : '' }}
                            value="{{ $user_type->type_guid }}">
                            {{ $user_type->name_en }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            @if ($user->instagram_id != null)
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Instagram ID</label>
                    <input disabled type="text" class="form-control" value="{{ $user->instagram_id }}" />
                </div>
            </div>
            @endif
            @if ($user->tiktok_id != null)
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Tiktok ID</label>
                    <input disabled type="text" class="form-control" value="{{ $user->tiktok_id }}" />
                </div>
            </div>
            @endif
            @if ($user->google_id != null)
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Google ID</label>
                    <input disabled type="text" class="form-control" value="{{ $user->google_id }}" />
                </div>
            </div>
            @endif
            @if ($user->apple_id != null)
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Apple ID</label>
                    <input disabled type="text" class="form-control" value="{{ $user->apple_id }}" />
                </div>
            </div>
            @endif
            @if ($user->type_guid == 'c8423db6-300e-42fa-a103-c5b62e388f98')
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Gender</label>
                    <select class="form-control" id="gender" name="gender">
                        <option {{ $user->gender == 'm' ? 'selected' : '' }} value="m">m</option>
                        <option {{ $user->gender == 'f' ? 'selected' : '' }} value="f">f</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Birthday</label>
                    <input name="birthday" type="date" class="form-control"
                        value="{{ date('Y-m-d', strtotime($user->birthday)) }}">
                </div>
            </div>
            @endif
            @if ($user->type_guid == '5d840a0f-c539-4257-955d-a375215ea307')
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Logo</label>
                    <div class="custom-file">
                        <input name="logo" type="file" class="custom-file-input" id="customFile" multiple
                            accept="image/png, image/gif, image/jpeg" />
                        @if ($user->logo == null)
                        <label class="custom-file-label text-danger" for="customFile">There is no picture</label>
                        @else
                        <label class="custom-file-label" for="customFile">Choose Image</label>
                        @endif
                        <span class="form-text text-muted">Allowed file extensions: png, jpg, gif.
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-3">
                <div class="form-group">
                    <label>Backgorund</label>
                    <div class="custom-file">
                        <input name="background" type="file" class="custom-file-input" id="customFile" multiple
                            accept="image/png, image/gif, image/jpeg" />
                        @if ($user->background == null)
                        <label class="custom-file-label text-danger" for="customFile">There is no picture</label>
                        @else
                        <label class="custom-file-label" for="customFile">Choose Image</label>
                        @endif
                        <span class="form-text text-muted">Allowed file extensions: png, jpg, gif.
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-sm-12">
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="summernote">{{ $user->description }}</textarea>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="card-footer d-flex justify-content-end">
        <input name="user_guid" type="hidden" value="{{ $user->user_guid }}">
        <button type="submit" class="btn btn-primary mr-2">Save</button>
    </div>
</form>
