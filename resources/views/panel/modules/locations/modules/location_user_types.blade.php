<div class="card-header p-0">
    <div class="row">
        <div class="col-lg-6 col-sm-6">
            <div class="card-title">
                <form action="{{ route('admin.locations.user_types.add') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">

                        <div class="col-lg-9 col-xl-9">
                            <select style="width: 100%" class="form-control kt_select2_4" id="kt_select2_4"
                                name="types_guid[]" multiple="multiple">
                                @foreach ($user_types as $user_type)

                                @if ($user_type->status == 1)
                                <option value="{{ $user_type->type_guid }}">
                                    {{ $user_type->name_en }}
                                </option>
                                @endif

                                @endforeach
                            </select>
                            <input name="location_guid" type="hidden" value="{{ $location->location_guid }}">
                        </div>
                        <div class="col-lg-3 col-xl-3">
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary px-6 font-weight-bold">Add</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-6 col-sm-6">
            <div class="card-title">
                <form action="" method="GET" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-9 col-sm-9">
                            <div class="input-icon">
                                <input name="name_en_user_types" type="text" class="form-control"
                                    placeholder="Search..." />
                                <span>
                                    <i class=" flaticon2-search-1 text-muted"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-3">
                            <div>
                                <button type="submit"
                                    class="btn btn-light-primary px-6 font-weight-bold">Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@if (count($location_user_types) > 0)
<form action="{{ route('admin.locations.user_types.delete') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card-body p-0">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                    <thead>

                        <tr class="text-left">
                            <th style="max-width: 40px">Id</th>
                            <th style="min-width: 140px">Name(EN)</th>
                            <th style="min-width: 150px">Name(AR)</th>

                        </tr>

                    </thead>
                    <tbody>
                        @foreach ($location_user_types as $user_type)
                        <tr>
                            <td>
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-danger">
                                        <input value="{{ $user_type->type_guid }}" type="checkbox"
                                            name="types_guid[]" />
                                        <span></span>

                                    </label>
                                </div>
                            </td>
                            <td>
                                <span class="text-dark-75 d-block font-size-lg"><a
                                        href="{{ route('admin.user_types.detail',$user_type->type_guid) }}"
                                        target="_blank">{{ $user_type->name_en }}</a></span>
                            </td>
                            <td>
                                <span class="text-dark-75 d-block font-size-lg"><a
                                        href="{{ route('admin.user_types.detail',$user_type->type_guid) }}"
                                        target="_blank">{{ $user_type->name_ar }}</a></span>
                            </td>
                        </tr>
                        @endforeach
                        <input name="location_guid" type="hidden" value="{{ $location->location_guid }}">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between">
        <div>
            <button type="submit" class="btn btn-danger">Delete</button>
        </div>
        @if($location_user_types->lastPage() > 1)
        {{
        $location_user_types->appends(request()->input())->links('panel.modules.global.paginator',['paginator'=>$location_user_types])
        }}
        @endif
    </div>
</form>

@else
<br />
<span class="text-dark-75 d-block font-size-lg text-lg-center">
    No User Type is attached to this location</span>
<br />
@endif
