<!-- Table Begin -->
<div class="row mt-5">
    <div class="col-xl-12">
        <!--begin::Advance Table Widget 1-->
        <div class="card card-custom gutter-b">
            <!--begin::Header-->
            <form action="{{ route('admin.listings.home') }}" method="GET" enctype="multipart/form-data">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-2 col-sm-2 mt-1">
                            <div class="input-icon">
                                <input name="search" type="text" class="form-control" placeholder="Search..." />
                                <span>
                                    <i class="flaticon2-search-1 text-muted"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-2 mt-1">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Entries:</span>
                                </div>
                                <span class="form-control text-dark-75 d-block font-size-lg text-center">{{
                                    $listing_count }}</span>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-2 mt-1">
                            <div class="d-flex align-items-center">
                                <label class="mr-3 mb-0 d-none d-md-block">Category:</label>
                                <select class="form-control" name="category_guid">
                                    <option value="">All</option>
                                    @foreach ($categories as $category)
                                    <option {{ $category->category_guid == request()->category_guid ? 'selected' : '' }}
                                        value="{{ $category->category_guid }}">{{ $category->name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-2 mt-1">
                            <div class="d-flex align-items-center">
                                <label class="mr-3 mb-0 d-none d-md-block">Locations:</label>
                                <select class="form-control" name="location_guid">
                                    <option value="">All</option>
                                    @foreach ($locations as $location)
                                    <option {{ $location->location_guid == request()->location_guid ? 'selected' : '' }}
                                        value="{{
                                        $location->location_guid }}">{{ $location->name_en}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-2 mt-1">
                            <div class="d-flex align-items-center">
                                <label class="mr-3 mb-0 d-none d-md-block">Status:</label>
                                <select class="form-control" name="status">
                                    <option value="">All</option>
                                    <option {{ request()->status=='1' ? 'selected' : '' }} value="1">Active</option>
                                    <option {{ request()->status=='0' ? 'selected' : '' }} value="0">Passive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-2 mt-1">
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
                    @if (count($listings) > 0)

                    <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                        <thead>

                            <tr class="text-left">
                                <th style="min-width: 120px">Listing No</th>
                                <th style="min-width: 150px">Name(EN)</th>
                                <th style="max-width: 100px">Category</th>
                                <th style="max-width: 100px">Location</th>
                                <th style="max-width: 100px">User</th>
                                <th style="max-width: 100px">Year</th>
                                <th style="min-width: 100px">Expire</th>
                                <th style="min-width: 100px">Status</th>
                                <th style="min-width: 180px">Detail/Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($listings as $listing)

                            @csrf
                            <tr>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $listing->listing_no
                                        }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        Helper::limit_string($listing->name_en) }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg"><a
                                            href="{{ route('admin.categories.detail',$listing->category->category_guid) }}"
                                            target="_blank">{{
                                            $listing->category->name_en }}</a></span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg"><a
                                            href="{{ route('admin.locations.detail',$listing->location->location_guid) }}"
                                            target="_blank">{{
                                            $listing->location->name_en }}</a>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg"><a
                                            href="{{ route('admin.users.detail',$listing->user->user_guid) }}"
                                            target="_blank">{{
                                            $listing->user->name }}</a>
                                    </span>
                                </td>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $listing->year }}</span>
                                </td>

                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        \Carbon\Carbon::parse($listing->expire_at)->diffForHumans() }}
                                    </span>
                                </td>
                                <td>
                                    @if($listing->status == '1')
                                    <span class="label label-lg label-light-success label-inline">Active</span>
                                    @else
                                    <span class="label label-lg label-light-danger label-inline">Passive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.listings.detail',$listing->listing_guid) }}"
                                        class="btn btn-icon btn-success">
                                        <i class="flaticon-eye"></i>
                                    </a>
                                    <button guid="{{ $listing->listing_guid }}" cname="{{ $listing->name_en }}"
                                        data-toggle="modal" data-target="#delete_listings_modal" type="button"
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
                        No listings Added.</span>
                    <br />

                    @endif
                </div>
            </div>
            @if($listings->lastPage() > 1)
            <div class="card-footer">
                {{
                $listings->appends(request()->input())->links('panel.modules.global.paginator',['paginator'=>$listings])
                }}
            </div>
            @endif
        </div>
        <!--end::Advance Table Widget 1-->
    </div>
</div>
<!-- Table End -->

<!--listings delete modal-->
<div class="modal fade" id="delete_listings_modal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-content" role="document">
        <div class="modal-header">
            <h5 class="modal-title" id="listingModalLabel">listing Delete</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>

        <div class="modal-body" style="text-align: center">

            <div id="name_modal_delete">

            </div>

        </div>
        <form class="w-100" method="post" action="{{ route('admin.listings.delete') }}">
            @csrf
            <div class="modal-footer">
                <input type="hidden" name="listing_guid" id="delete_guide" value="" required />
                <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary font-weight-bold">Delete</button>
            </div>

        </form>


    </div>
</div>

@section('js')
<script>
    const categoryGuidBtns = document.getElementsByClassName('delete_btns');
    Array.from(categoryGuidBtns).forEach(btn => {
        btn.addEventListener('click',() => {
            const guid = btn.getAttribute('guid');
            const cname = btn.getAttribute('cname');
            const hiddenBtn = document.getElementById('delete_guide');
            const nameModalDelete = document.getElementById('name_modal_delete');
            nameModalDelete.innerHTML = `You are about to delete an listing. Are you sure you want to delete ${cname}?`;
            hiddenBtn.value = guid;
        });
    });

    @if(Session::has('errorValidate'))
        toastr.error('Please enter at least 2 letters.');
    @endif

    @if(Session::has('successAddlisting'))
        toastr.success('listing successfully added.');
    @endif

    @if(Session::has('successDeleteListings'))
        toastr.success('listing successfully deleted.');
    @endif
</script>
@endsection
