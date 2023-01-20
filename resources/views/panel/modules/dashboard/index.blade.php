<div class="row">
    <div class="col-lg-3">
        <!--begin::Stats Widget 25-->
        <div class="card card-custom bgi-no-repeat card-stretch gutter-b"
            style="background-position: right top; background-size: 30% auto; background-image: url({{ secure_asset('panel/assets/media/svg/shapes/abstract-1.svg') }})">
            <!--begin::Body-->
            <div class="card-body">
                <span class="svg-icon svg-icon-2x svg-icon-info">
                    <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Mail-opened.svg-->
                    <i style="color: #8950fc" class="icon-2x flaticon-map-location"></i>
                    <!--end::Svg Icon-->
                </span>
                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-6 d-block">{{
                    $locations_count }}</span>
                <span class="font-weight-bold text-muted font-size-sm">Locations</span>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Stats Widget 25-->
    </div>
    <div class="col-lg-3">
        <!--begin::Stats Widget 25-->
        <div class="card card-custom bgi-no-repeat card-stretch gutter-b"
            style="background-position: right top; background-size: 30% auto; background-image: url({{ secure_asset('panel/assets/media/svg/shapes/abstract-1.svg') }})">
            <!--begin::Body-->
            <div class="card-body">
                <span class="svg-icon svg-icon-2x svg-icon-info">
                    <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Mail-opened.svg-->
                    <i style="color: #8950fc" class="icon-2x flaticon-layers"></i>

                    <!--end::Svg Icon-->
                </span>
                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-6 d-block">{{
                    $categories_count }}</span>
                <span class="font-weight-bold text-muted font-size-sm">Categories</span>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Stats Widget 25-->
    </div>
    <div class="col-lg-3">
        <!--begin::Stats Widget 25-->
        <div class="card card-custom bgi-no-repeat card-stretch gutter-b"
            style="background-position: right top; background-size: 30% auto; background-image: url({{ secure_asset('panel/assets/media/svg/shapes/abstract-1.svg') }})">
            <!--begin::Body-->
            <div class="card-body">
                <span class="svg-icon svg-icon-2x svg-icon-info">
                    <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Mail-opened.svg-->
                    <i style="color: #8950fc" class="icon-2x flaticon-users"></i>
                    <!--end::Svg Icon-->
                </span>
                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-6 d-block">{{ $users_count
                    }}</span>
                <span class="font-weight-bold text-muted font-size-sm">Users</span>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Stats Widget 25-->
    </div>
    <div class="col-lg-3">
        <!--begin::Stats Widget 25-->
        <div class="card card-custom bgi-no-repeat card-stretch gutter-b"
            style="background-position: right top; background-size: 30% auto; background-image: url({{ secure_asset('panel/assets/media/svg/shapes/abstract-1.svg') }})">
            <!--begin::Body-->
            <div class="card-body">
                <span class="svg-icon svg-icon-2x svg-icon-info">
                    <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Mail-opened.svg-->
                    <i style="color: #8950fc" class="icon-2x flaticon-interface-11"></i>
                    <!--end::Svg Icon-->
                </span>
                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-6 d-block">{{
                    $listings_count }}</span>
                <span class="font-weight-bold text-muted font-size-sm">Listings</span>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Stats Widget 25-->
    </div>
