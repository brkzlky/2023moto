<!-- Table Begin -->
<div class="row  mt-5">

  <div class="col-xl-12">
    <!--begin::Advance Table Widget 1-->
    <div class="card card-custom gutter-b">
      <!--begin::Header-->
      <div class="card-header d-flex justify-content-end pt-5">

        <form action="" method="GET">
          <div class="d-flex align-items-center">
            <div class="mr-3">
              <input class="form-control" type="date" name="date" value="{{ !is_null($selected_date) ? date('Y-m-d',strtotime($selected_date)) : null }}">
            </div>
            <div class="">
              <button type="submit" class="btn btn-sm btn-primary">
                <i class="fa fa-search pr-0 mr-2"></i>Search
              </button>
            </div>
          </div>

        </form>
      </div>
      <!--end::Header-->
      <!--begin::Body-->
      <div class="card-body py-4">
        <!--begin::Table-->
        @if (is_null($rates))
        <div class="alert alert-danger text-center" role="alert">
          There is no currency info on this date.
        </div>
        @else
        <div class="table-responsive">
          <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
            <thead>
              <tr class="text-center">
                <th></th>
                @foreach ($currencies as $c)
                <th>{{ $c->name }}</th>
                @endforeach
              </tr>
            </thead>
            <tbody>
              <!--begin::Form-->
              @foreach ($currencies as $cr)
              <tr>
                <td>
                  <span class="text-dark-75 font-weight-bolder d-block font-size-lg">{{ $cr->name }}</span>
                </td>
                @for ($i = 0; $i < count($currencies); $i++) <td>
                  @if ($cr->name==$currencies[$i]->name)
                  <span class="text-dark-75 font-weight-bolder d-block font-size-lg text-center">1</span>
                  @else
                  <span class="text-dark-75 font-weight-bolder d-block font-size-lg text-center">{{
                    $rates[$cr->currency_guid][$currencies[$i]->currency_guid] }}</span>
                  @endif
                  </td>
                  @endfor
              </tr>
              @endforeach
              <!--end::Form-->
            </tbody>
          </table>

        </div>
        <!--end::Advance Table Widget 1-->
        @endif
        
    </div>
</div>
<!-- Table End -->
<!--Example delete modal-->

<!--Example add modal-->
<div class="modal fade" id="add_example_modal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" method="post" action="{{ route('admin.admin.add') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Admin Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" name="email" class="form-control form-control-lg" placeholder="Name" />
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control form-control-lg" placeholder="Name" />
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" />
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control form-control-lg"
                        placeholder="Password" />
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" class="form-control form-control-lg">
                        <option value="n">Normal Admin</option>
                        <option value="s">Super Admin</option>
                    </select>
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
<script>
  // Class definition

var KTBootstrapDatepicker = function () {

var arrows;
if (KTUtil.isRTL()) {
 arrows = {
  leftArrow: '<i class="la la-angle-right"></i>',
  rightArrow: '<i class="la la-angle-left"></i>'
 }
} else {
 arrows = {
  leftArrow: '<i class="la la-angle-left"></i>',
  rightArrow: '<i class="la la-angle-right"></i>'
 }
}

// Private functions
var demos = function () {
 // minimum setup
 $('#kt_datepicker_1').datepicker({
  rtl: KTUtil.isRTL(),
  todayHighlight: true,
  orientation: "bottom left",
  templates: arrows
 });

 // minimum setup for modal demo
 $('#kt_datepicker_1_modal').datepicker({
  rtl: KTUtil.isRTL(),
  todayHighlight: true,
  orientation: "bottom left",
  templates: arrows
 });

 // input group layout
 $('#kt_datepicker_2').datepicker({
  rtl: KTUtil.isRTL(),
  todayHighlight: true,
  orientation: "bottom left",
  templates: arrows
 });

 // input group layout for modal demo
 $('#kt_datepicker_2_modal').datepicker({
  rtl: KTUtil.isRTL(),
  todayHighlight: true,
  orientation: "bottom left",
  templates: arrows
 });

 // enable clear button
 $('#kt_datepicker_3, #kt_datepicker_3_validate').datepicker({
  rtl: KTUtil.isRTL(),
  todayBtn: "linked",
  clearBtn: true,
  todayHighlight: true,
  templates: arrows
 });

 // enable clear button for modal demo
 $('#kt_datepicker_3_modal').datepicker({
  rtl: KTUtil.isRTL(),
  todayBtn: "linked",
  clearBtn: true,
  todayHighlight: true,
  templates: arrows
 });

 // orientation
 $('#kt_datepicker_4_1').datepicker({
  rtl: KTUtil.isRTL(),
  orientation: "top left",
  todayHighlight: true,
  templates: arrows
 });

 $('#kt_datepicker_4_2').datepicker({
  rtl: KTUtil.isRTL(),
  orientation: "top right",
  todayHighlight: true,
  templates: arrows
 });

 $('#kt_datepicker_4_3').datepicker({
  rtl: KTUtil.isRTL(),
  orientation: "bottom left",
  todayHighlight: true,
  templates: arrows
 });

 $('#kt_datepicker_4_4').datepicker({
  rtl: KTUtil.isRTL(),
  orientation: "bottom right",
  todayHighlight: true,
  templates: arrows
 });

 // range picker
 $('#kt_datepicker_5').datepicker({
  rtl: KTUtil.isRTL(),
  todayHighlight: true,
  templates: arrows
 });

  // inline picker
 $('#kt_datepicker_6').datepicker({
  rtl: KTUtil.isRTL(),
  todayHighlight: true,
  templates: arrows
 });
}

return {
 // public functions
 init: function() {
  demos();
 }
};
}();



@endsection

