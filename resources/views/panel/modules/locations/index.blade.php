<!-- Table Begin -->
<div class="row  mt-5">
    <div class="col-xl-12">
        <!--begin::Advance Table Widget 1-->
        <div class="card card-custom gutter-b">
            <!--begin::Header-->
            <div class="card-header">
                <div class="card-title">
                    <form action="{{ route('admin.locations.home') }}" method="GET" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-9 col-sm-9">
                                <div class="input-icon">
                                    <input name="name_en" type="text" class="form-control" placeholder="Search..." />
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

                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                        data-target="#add_locations_modal">
                        <i class="fa fa-plus pr-0 mr-2"></i> Location Add
                    </button>
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body py-4">
                <!--begin::Table-->
                <div class="table-responsive">
                    @if (count($locations) > 0)

                    <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                        <thead>

                            <tr class="text-left">
                                <th style="max-width: 100px">Id</th>
                                <th style="min-width: 150px">Name(EN)</th>
                                <th style="min-width: 150px">Name(AR)</th>
                                <th style="max-width: 100px">Category</th>
                                <th style="max-width: 100px">Listings</th>
                                {{-- <th style="max-width: 100px">Types</th> --}}
                                <th style="min-width: 100px">Status</th>
                                <th style="min-width: 180px">Detail/Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($locations as $location)

                            @csrf
                            <tr>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $location->id
                                        }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $location->name_en }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $location->name_ar }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $location->location_categories_count }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $location->listings_count }}
                                    </span>
                                </td>
                                {{-- <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $location->location_user_types_count }}</span>
                                </td> --}}
                                <td>
                                    @if($location->status == '1')
                                    <span class="label label-lg label-light-success label-inline">Active</span>
                                    @else
                                    <span class="label label-lg label-light-danger label-inline">Passive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.locations.detail',$location->location_guid) }}"
                                        class="btn btn-icon btn-success">
                                        <i class="flaticon-eye"></i>
                                    </a>
                                    <button guid="{{ $location->location_guid }}" cname="{{ $location->name_en }}"
                                        data-toggle="modal" data-target="#delete_locations_modal" type="button"
                                        class="btn btn-icon btn-danger delete_btns">
                                        <i class="flaticon2-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    @else

                    <br />
                    <span class="text-dark-75 d-block font-size-lg text-lg-center">
                        No Locations Added.</span>
                    <br />

                    @endif
                </div>
            </div>
            @if($locations->lastPage() > 1)
            <div class="card-footer">
                {{
                $locations->appends(request()->input())->links('panel.modules.global.paginator',['paginator'=>$locations])
                }}
            </div>
            @endif
        </div>
        <!--end::Advance Table Widget 1-->
    </div>
</div>
<!-- Table End -->

<!--Locations delete modal-->
<div class="modal fade" id="delete_locations_modal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-content" role="document">
        <div class="modal-header">
            <h5 class="modal-title" id="locationModalLabel">Location Delete</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>

        <div class="modal-body" style="text-align: center">

            <div id="name_modal_delete">

            </div>

        </div>
        <form class="w-100" method="post" action="{{ route('admin.locations.delete') }}">
            @csrf
            <div class="modal-footer">
                <input type="hidden" name="location_guid" id="delete_guide" value="" required />
                <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary font-weight-bold">Delete</button>
            </div>

        </form>


    </div>
</div>
<!--Locations add modal-->
<div class="modal fade" id="add_locations_modal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" method="POST" action="{{ route('admin.locations.add') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="locationModalLabel">Location Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-sm-12">
                        <div class="form-group">
                            <label>Alias(EN)<span class="text-danger">*</span></label>
                            <input type="text" name="name_en" class="form-control form-control-lg" required />
                        </div>
                    </div>

                    <div class="col-lg-12 col-sm-12">
                        <div class="form-group">
                            <label>Subdomain Name<span class="text-danger">*</span></label>
                            <input type="text" name="subdomain" class="form-control form-control-lg" required />
                        </div>
                    </div>

                    <div class="col-lg-12 col-sm-12">
                        <div class="form-group">
                            <label>Choose what you want to add</label>
                            <div class="radio-inline">
                                <label class="radio">
                                    <input id="country" value="country" type="radio" name="radios2" checked />
                                    <span></span>
                                    Country
                                </label>
                                <label class="radio">
                                    <input id="city" value="city" type="radio" name="radios2" />
                                    <span></span>
                                    City
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="select_country" class="col-lg-12 col-sm-12">
                        <div class="form-group">
                            <label>Country<span class="text-danger">*</span></label>
                            <select class="form-control" id="countries" name="country_guid">
                                @foreach ($countries as $country)
                                <option value="{{ $country->country_guid }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="select_city" class="col-lg-12 col-sm-12">
                        <div class="form-group">
                            <label>City</label>
                            <select disabled class="form-control" id="cities" name="city_guid">
                                <option>Please Select Country</option>
                            </select>
                        </div>
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
<script type="module" src="{{ secure_asset('panel/assets/js/countries.js')}}"></script>
<script>
    const Routes = {
        select_country : "{{ route('admin.locations.select_country') }}",
    }

    const categoryGuidBtns = document.getElementsByClassName('delete_btns');
    Array.from(categoryGuidBtns).forEach(btn => {
        btn.addEventListener('click',() => {
            const guid = btn.getAttribute('guid');
            const cname = btn.getAttribute('cname');
            const hiddenBtn = document.getElementById('delete_guide');
            const nameModalDelete = document.getElementById('name_modal_delete');
            nameModalDelete.innerHTML = `You are about to delete an location. Are you sure you want to delete ${cname}?`;
            hiddenBtn.value = guid;
        });
    });
    @if(Session::has('errorValidate'))
        toastr.error('Please enter at least 2 letters.');
    @endif
    @if(Session::has('successAddLocation'))
        toastr.success('Location successfully added.');
    @endif
    @if(Session::has('successDeleteLocations'))
        toastr.success('Location successfully deleted.');
    @endif
</script>
@endsection
