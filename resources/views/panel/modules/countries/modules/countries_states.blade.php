@if (count($states) > 0)
<div class="card-body p-0">
    <div class="row">
        <div class="table-responsive">
            <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                <thead>

                    <tr class="text-left">
                        <th style="min-width: 140px">Id</th>
                        <th style="min-width: 140px">Name</th>
                        <th style="min-width: 140px">Cities</th>
                    </tr>

                </thead>
                <tbody>
                    @foreach ($states as $state)
                    <tr>
                        <td>
                            <span class="text-dark-75 d-block font-size-lg">{{ $state->id }}</span>
                        </td>
                        <td>
                            <span class="text-dark-75 d-block font-size-lg">{{ $state->name }}</span>
                        </td>
                        <td>
                            <span class="text-dark-75 d-block font-size-lg">{{ $state->cities_count }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between">
        @if($states->lastPage() > 1)
        {{
        $states->appends(request()->states)->links('panel.modules.global.paginator',['paginator'=>$states])
        }}
        @endif
    </div>
</div>

@else
<br />
<span class="text-dark-75 d-block font-size-lg text-lg-center">
    No satate this country</span>
<br />
@endif
