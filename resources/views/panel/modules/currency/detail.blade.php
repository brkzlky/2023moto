<div class="row mt-5">
    <div class="col-lg-12">
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-toolbar">
                    <ul class="nav nav-light-success nav-bold nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active generalSettings" data-toggle="tab"
                                href="#general_settings">Currency
                                Detail</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link change_password" data-toggle="tab" href="#change_password">Exchange
                                Rates</a>
                        </li>
                    </ul>
                </div>
                <div class="card-toolbar">

                    <span class="badge badge-primary">Validity Date: {{ $validity_date }}</span>
                </div>
            </div>
            <div class="card-body py-0">
                <div class="row mt-5">
                    <div class="col-lg-12">
                        <div class="example-preview">
                            <div class="tab-content mt-5" id="myTabContent">
                                <div class="tab-pane fade show active generalSettings" id="general_settings"
                                    role="tabpanel" aria-labelledby="general_settings">
                                    <form action="{{ route('admin.currency.update') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row mt-5">
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input type="text" class="form-control" name="name"
                                                        value="{{ $currency->name }}" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Label</label>
                                                    <input type="text" class="form-control" name="label"
                                                        value="{{ $currency->label }}" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-5">
                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Symbol</label>
                                                    <input type="text" class="form-control" name="symbol"
                                                        value="{{ $currency->symbol }}" />
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-sm-6">
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select class="form-control" name="status">
                                                        <option {{ $currency->status=='1' ? 'selected':'' }}
                                                            value="1">Active</option>
                                                        <option {{ $currency->status=='0'?'selected':'' }}
                                                            value="0">Passive</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="card-footer d-flex justify-content-end ">
                                            <input name="currency_guid" type="hidden"
                                                value="{{ $currency->currency_guid }}">
                                            <button type="submit" class="btn btn-primary mr-2">Save</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade change_password" id="change_password" role="tabpanel"
                                    aria-labelledby="change_password">
                                    <div class="table-responsive">
                                        <table class="table table-head-custom table-vertical-center"
                                            id="kt_advance_table_widget_1">
                                            <thead>
                                                <tr class="text-left">
                                                    <th style="min-width: 140px">From</th>
                                                    <th style="min-width: 150px">To</th>
                                                    <th style="min-width: 150px">Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!--begin::Form-->
                                                @foreach ($currency->rates as $cr)
                                                <tr>

                                                    <td>
                                                        <span class="text-dark-75 d-block font-size-lg">{{
                                                            $cr->from_name->name }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="text-dark-75 d-block font-size-lg">{{
                                                            $cr->to_name->name }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="text-dark-75 d-block font-size-lg">{{ $cr->price
                                                            }}</span>
                                                    </td>

                                                </tr>
                                                @endforeach

                                                <!--end::Form-->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@section('js')
<script>
    @if(Session::has('successUpdateCurrency'))
      toastr.success('Currency successfully updated.');
  @endif
  @if(Session::has('errorUpdateCurrency'))
      toastr.error('There is an error occurred while updating currency please try again.');
  @endif
  @if(Session::has('errorCurrencyValidate'))
      toastr.error('There is an error occurred while updating currency please try again.');
  @endif

</script>
@endsection
