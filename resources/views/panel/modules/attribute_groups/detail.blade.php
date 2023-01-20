<!--start::Statistics-->
<div class="row">
    <div class="col-xl-3">
        <!--begin::Stats Widget 25-->
        <div class="card card-custom mt-3">
            <!--begin::Body-->
            <div class="card-body">

                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 d-block">{{
                    $attribute_group->attribute_info_count
                    }}</span>
                <span class="font-weight-bold text-muted font-size-sm"><a href="{{ route('admin.attributes.home') }}"
                        target="_blank">Attributes</a></span>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Stats Widget 25-->
    </div>
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
                            <a class="nav-link attributes_settings" data-toggle="tab" href="#attributes_settings">
                                <span class="nav-text">Attributes Settings</span>
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

                                    @include('panel.modules.attribute_groups.modules.attribute_group_settings')

                                </div>

                                <div class="tab-pane fade attributes_settings" id="attributes_settings" role="tabpanel"
                                    aria-labelledby="attributes_settings">


                                    @include('panel.modules.attribute_groups.modules.attribute_group_attibutes')

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
    $('#kt_select2_3').select2({
        placeholder: "Select a state"
    });

    @if(Session::has('successUpdateAttributeGroup'))
        toastr.success('Attribute group successfully updated.');
    @endif

    @if(Session::has('successDeletedAttribute'))
        toastr.success('Attributes successfully deleted.');
        const general_settings = document.getElementsByClassName('general_settings')[1];
        general_settings.classList.remove('show');
        general_settings.classList.remove('active');
        const attributes_settings = document.getElementsByClassName('attributes_settings')[1];
        attributes_settings.classList.add('show');
        attributes_settings.classList.add('active');
        const general_settingsTab = document.getElementsByClassName('general_settings')[0];
        general_settingsTab.classList.remove('active');
        const attributes_settingsTab = document.getElementsByClassName('attributes_settings')[0];
        attributes_settingsTab.classList.add('active');
    @endif

    @if(Session::has('successAddedAttribute'))
        toastr.success('Attributes successfully added.');
        const general_settingsTab = document.getElementsByClassName('general_settings')[0];
        general_settingsTab.classList.remove('active');
        const general_settings = document.getElementsByClassName('general_settings')[1];
        general_settings.classList.remove('show');
        general_settings.classList.remove('active');
        const attributes_settingsTab = document.getElementsByClassName('attributes_settings')[0];
        attributes_settingsTab.classList.add('active');
        const attributes_settings = document.getElementsByClassName('attributes_settings')[1];
        attributes_settings.classList.add('show');
        attributes_settings.classList.add('active');
    @endif

    @if(isset(request()->attribute) || $search_attribute == 1)
        const general_settingsTab = document.getElementsByClassName('general_settings')[0];
        general_settingsTab.classList.remove('active');
        const general_settings = document.getElementsByClassName('general_settings')[1];
        general_settings.classList.remove('show');
        general_settings.classList.remove('active');
        const attributes_settingsTab = document.getElementsByClassName('attributes_settings')[0];
        attributes_settingsTab.classList.add('active');
        const attributes_settings = document.getElementsByClassName('attributes_settings')[1];
        attributes_settings.classList.add('show');
        attributes_settings.classList.add('active');
    @endif

</script>
@endsection
