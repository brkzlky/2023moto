<!-- Table Begin -->
<div class="row  mt-5">
    <div class="col-xl-12">
        <!--begin::Advance Table Widget 1-->
        <div class="card card-custom gutter-b">
            <!--begin::Header-->
            <form action="{{ route('admin.countries.home') }}" method="GET" enctype="multipart/form-data">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-3 col-sm-3">
                            <div class="input-icon">
                                <input name="search" type="text" class="form-control" placeholder="Search..." />
                                <span>
                                    <i class=" flaticon2-search-1 text-muted"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-2 mt-1">
                            <div class="d-flex align-items-center">
                                <label class="mr-3 mb-0 d-none d-md-block">Status:</label>
                                <select class="form-control" name="status">
                                    <option value="">All</option>
                                    <option {{ request()->status=='1' ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ request()->status=='0' ? 'selected' : '' }} value="0">Passive
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-3">
                            <div>
                                <button type="submit"
                                    class="btn btn-light-primary px-6 font-weight-bold">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body py-4">
                <!--begin::Table-->
                <div class="table-responsive">
                    @if (count($countries) > 0)

                    <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                        <thead>

                            <tr class="text-left">
                                <th style="max-width: 100px">Id</th>
                                <th style="min-width: 150px">Name</th>
                                <th style="min-width: 150px">Capital</th>
                                <th style="min-width: 100px">States</th>
                                <th style="min-width: 100px">Cities</th>
                                <th style="max-width: 100px">Status</th>
                                <th style="min-width: 180px">Detail/Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($countries as $country)

                            @csrf
                            <tr>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $country->id
                                        }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $country->name }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $country->capital }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $country->states_count }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $country->cities_count }}</span>
                                </td>
                                <td>
                                    @if($country->status == '1')
                                    <span class="label label-lg label-light-success label-inline">Active</span>
                                    @else
                                    <span class="label label-lg label-light-danger label-inline">Passive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.countries.detail',$country->country_guid) }}"
                                        class="btn btn-icon btn-success">
                                        <i class="flaticon-eye"></i>
                                    </a>
                                    @if ($country->status == 1)
                                    <a href="{{ route('admin.countries.status',$country->country_guid) }}"
                                        class="btn btn-icon btn-danger">
                                        <i class="flaticon2-cross"></i>
                                    </a>
                                    @else
                                    <a href="{{ route('admin.countries.status',$country->country_guid) }}"
                                        class="btn btn-icon btn-primary">
                                        <i class="flaticon2-checkmark"></i>
                                    </a>
                                    @endif

                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    @else

                    <br />
                    <span class="text-dark-75 d-block font-size-lg text-lg-center">
                        No Countries Found.</span>
                    <br />

                    @endif
                </div>
            </div>
            @if($countries->lastPage() > 1)
            <div class="card-footer">
                {{
                $countries->appends(request()->input())->links('panel.modules.global.paginator',['paginator'=>$countries])
                }}
            </div>
            @endif
        </div>
        <!--end::Advance Table Widget 1-->
    </div>
</div>
<!-- Table End -->


@section('js')
<script>
    @if(Session::has('successUpdateCountry'))
        toastr.success('Country Successfly Updated.');
    @endif
</script>
@endsection
