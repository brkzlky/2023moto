<!--start::Statistics-->
<div class="row">
    @if ($country->states_count > 0)
    <div class="col-xl-3">
        <!--begin::Stats Widget 25-->
        <div class="card card-custom mt-3">
            <!--begin::Body-->
            <div class="card-body">
                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 d-block">{{
                    $country->states_count
                    }}</span>
                <span class="font-weight-bold text-muted font-size-sm">States</span>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Stats Widget 25-->
    </div>
    @endif
    @if ($country->cities_count > 0)
    <div class="col-xl-3">
        <!--begin::Stats Widget 25-->
        <div class="card card-custom mt-3">
            <!--begin::Body-->
            <div class="card-body">
                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 d-block">{{
                    $country->cities_count
                    }}</span>
                <span class="font-weight-bold text-muted font-size-sm">Cities</span>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Stats Widget 25-->
    </div>
    @endif
</div>
<!--end::Statistics-->

<div class="row mt-5">
    <div class="col-lg-12">
        <div class="card card-custom">
            <div class="card-header">
                <div class="card-toolbar">
                    <ul class="nav nav-light-success nav-bold nav-pills">
                        <li class="nav-item">
                            <a class="nav-link active general_settings" data-toggle="tab" href="#general_settings">
                                <span class="nav-text">General Settings</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link states" data-toggle="tab" href="#states">
                                <span class="nav-text">States</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link cities" data-toggle="tab" href="#cities">
                                <span class="nav-text">Cities/Town</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card-body py-4">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="example-preview">
                            <div class="tab-content" id="myTabContent">

                                <div class="tab-pane fade show active general_settings" id="general_settings"
                                    role="tabpanel" aria-labelledby="general_settings">

                                    @include('panel.modules.countries.modules.countries_settings')

                                </div>

                                <div class="tab-pane fade states" id="states" role="tabpanel" aria-labelledby="states">

                                    @include('panel.modules.countries.modules.countries_states')

                                </div>

                                <div class="tab-pane fade cities" id="cities" role="tabpanel" aria-labelledby="cities">

                                    @include('panel.modules.countries.modules.countries_cities')

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
    @if(Session::has('errorValidate'))
        toastr.error('At least 2 numbers.');
    @endif

    @if(Session::has('successUpdateCountry'))
        toastr.success('Successfly updated country.');
    @endif

    @if(isset(request()->states) )
        const general_settings = document.getElementsByClassName('general_settings')[1];
        general_settings.classList.remove('show');
        general_settings.classList.remove('active');
        const states = document.getElementsByClassName('states')[1];
        states.classList.add('show');
        states.classList.add('active');
        const general_settingsTab = document.getElementsByClassName('general_settings')[0];
        general_settingsTab.classList.remove('active');
        const statesTab = document.getElementsByClassName('states')[0];
        statesTab.classList.add('active');
    @endif

    @if(isset(request()->cities)  )
        const general_settings = document.getElementsByClassName('general_settings')[1];
        general_settings.classList.remove('show');
        general_settings.classList.remove('active');
        const cities = document.getElementsByClassName('cities')[1];
        cities.classList.add('show');
        cities.classList.add('active');
        const general_settingsTab = document.getElementsByClassName('general_settings')[0];
        general_settingsTab.classList.remove('active');
        const citiesTab = document.getElementsByClassName('cities')[0];
        citiesTab.classList.add('active');
    @endif
</script>
@endsection
