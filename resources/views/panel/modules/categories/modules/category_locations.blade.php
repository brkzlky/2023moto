<div class="card-header p-0">
    <div class="row">
        <div class="col-lg-12 col-sm-12">
            <div class="card-title">
                <form action="" method="GET" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-4 col-sm-4">
                            <div class="input-icon">
                                <input name="name_en_location" type="text" class="form-control"
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
<div class="card-body p-0">
    @if (count($category_locations) > 0)
    <div class="table-responsive">
        <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
            <thead>

                <tr class="text-left">
                    <th style="max-width: 40px">Id</th>
                    <th style="min-width: 140px">Name(EN)</th>
                    <th style="min-width: 150px">Name(AR)</th>
                    <th style="min-width: 150px">Status</th>

                </tr>

            </thead>
            <tbody>
                @foreach ($category_locations as $location)
                <tr>
                    <td>
                        <span class="text-dark-75 d-block font-size-lg">{{
                            $location->id }}</span>
                    </td>
                    <td>
                        <span class="text-dark-75 d-block font-size-lg"><a
                                href="{{ route('admin.locations.detail',$location->location_guid) }}" target="_blank">{{
                                $location->name_en }}</a></span>
                    </td>
                    <td>
                        <span class="text-dark-75 d-block font-size-lg"><a
                                href="{{ route('admin.locations.detail',$location->location_guid) }}" target="_blank">{{
                                $location->name_ar }}</a></span>
                    </td>
                    <td>
                        @if($location->status == '1')
                        <span class="label label-lg label-light-success label-inline">Active</span>
                        @else
                        <span class="label label-lg label-light-danger label-inline">Passive</span>
                        @endif
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
    @else

    <br>
    <span class="text-dark-75 d-block font-size-lg text-lg-center">
        no location is attached to this category</span>
    @endif
    @if($category_locations->lastPage() > 1)
    <div class="card-footer d-flex justify-content-between">
        {{
        $category_locations->appends(request()->input())->links('panel.modules.global.paginator',['paginator'=>$category_locations])
        }}
    </div>
    @endif
</div>
