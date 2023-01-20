@if (count($images) > 0)

<div class="card-body p-0">
    <!--begin::Table-->
    <div class="table-responsive">

        <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
            <thead>
                <tr class="text-left">
                    <th style="min-width: 150px">Image</th>
                    <th style="min-width: 150px">Name</th>
                    <th style="min-width: 180px">Delete</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($images as $image)
                @php
                    $imagepath = secure_asset('storage/listings/'.$listing->listing_no.'/'.$image->name);
                @endphp
                <tr>
                    <td>
                        @if (file_exists('storage/listings/'.$listing->listing_no.'/'.$image->name))
                        <img style="width: 200px;height:200px;object-fit: contain;" src="{{ $imagepath }}">
                        @endif
                    </td>
                    <td>
                        <span class="text-dark-75 d-block font-size-lg">{{ $image->name }}</span>
                    </td>
                    <td>
                        <button guid="{{ $image->image_guid }}" cname="{{ $image->name }}" data-toggle="modal"
                            data-target="#delete_listings_modal" type="button"
                            class="btn btn-icon btn-danger delete_btns">
                            <i class="flaticon2-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>

    </div>
</div>

@if($images->lastPage() > 1)
<div class="card-footer d-flex justify-content-end">
    {{
    $images->appends(request()->input())->links('panel.modules.global.paginator',['paginator'=>$images])
    }}
</div>

@endif
@else

<br />
<span class="text-dark-75 d-block font-size-lg text-lg-center">
    No Image Added.</span>
<br />

@endif

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
        <form class="w-100" method="post" action="{{ route('admin.listings.image_delete') }}">
            @csrf
            <div class="modal-footer">
                <input type="hidden" name="image_guid" id="delete_guide" value="" required />
                <input type="hidden" name="listing_guid" id="delete_guide" value="{{ $listing->listing_guid }}"
                    required />
                <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary font-weight-bold">Delete</button>
            </div>

        </form>


    </div>
</div>
