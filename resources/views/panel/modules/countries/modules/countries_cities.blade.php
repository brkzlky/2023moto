@if (count($cities) > 0)
<div class="card-body p-0">
    <div class="row">
        <div class="table-responsive">
            <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                <thead>

                    <tr class="text-left">
                        <th style="min-width: 140px">Id</th>
                        <th style="min-width: 140px">Name</th>
                        <th style="min-width: 140px">State Code</th>
                    </tr>

                </thead>
                <tbody>
                    @foreach ($cities as $city)
                    <tr>
                        <td>
                            <span class="text-dark-75 d-block font-size-lg">{{ $city->id }}</span>
                        </td>
                        <td>
                            <span class="text-dark-75 d-block font-size-lg">{{ $city->name }}</span>
                        </td>
                        <td>
                            <span class="text-dark-75 d-block font-size-lg">{{ $city->state_code }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between">
        @if($cities->lastPage() > 1)
        {{
        $cities->appends(request()->cities)->links('panel.modules.global.paginator',['paginator'=>$cities])
        }}
        @endif
    </div>
</div>

@else
<br />
<span class="text-dark-75 d-block font-size-lg text-lg-center">
    No city this country</span>
<br />
@endif
