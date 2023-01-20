<!--start::Statistics-->
<div class="row">
    <div class="col-xl-3">
        <!--begin::Stats Widget 25-->
        <div class="card card-custom mt-3">
            <!--begin::Body-->
            <div class="card-body">
                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 d-block">{{
                    $category->attr_groups_info_count }}</span>
                <span class="font-weight-bold text-muted font-size-sm"><a
                        href="{{ route('admin.attribute_groups.home') }}?name_en={{ $category->name_en }}"
                        target="_blank">Attribute Groups</a></span>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Stats Widget 25-->
    </div>
    @if ($category->children_count > 0)
    <div class="col-xl-3">
        <!--begin::Stats Widget 25-->
        <div class="card card-custom mt-3">
            <!--begin::Body-->
            <div class="card-body">
                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 d-block">{{
                    $category->children_count }}</span>
                <span class="font-weight-bold text-muted font-size-sm">Category Children</span>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Stats Widget 25-->
    </div>
    @endif

    <div class="col-xl-3">
        <!--begin::Stats Widget 25-->
        <div class="card card-custom mt-3">
            <!--begin::Body-->
            <div class="card-body">

                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 d-block">{{
                    $category->location_info_count }}</span>
                <span class="font-weight-bold text-muted font-size-sm"><a href="{{ route('admin.locations.home') }}"
                        target="_blank">Location</a></span>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Stats Widget 25-->
    </div>

    <div class="col-xl-3">
        <!--begin::Stats Widget 25-->
        <div class="card card-custom mt-3">
            <!--begin::Body-->
            <div class="card-body">

                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 d-block">{{
                    $category->listings_count }}</span>
                <span class="font-weight-bold text-muted font-size-sm"><a
                        href="{{ route('admin.listings.home') }}?category_guid={{ $category->category_guid }}"
                        target="_blank">Listings</a></span>
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
                            <a class="nav-link attribute_groups" data-toggle="tab" href="#attribute_groups">
                                <span class="nav-text">Related Attribute G.</span>
                            </a>
                        </li>
                        @if ($category->children_count > 0)
                        <li class="nav-item">
                            <a class="nav-link category_children" data-toggle="tab" href="#category_children">
                                <span class="nav-text">Category Children</span>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link locations" data-toggle="tab" href="#locations">
                                <span class="nav-text">Related Locations</span>
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

                                    @include('panel.modules.categories.modules.category_settings')

                                </div>
                                <div class="tab-pane fade locations" id="locations" role="tabpanel"
                                    aria-labelledby="locations">

                                    @include('panel.modules.categories.modules.category_locations')

                                </div>
                                <div class="tab-pane fade attribute_groups" id="attribute_groups" role="tabpanel"
                                    aria-labelledby="attribute_groups">

                                    @include('panel.modules.categories.modules.category_attribute_groups')

                                </div>
                                @if ($category->children_count > 0)
                                <div class="tab-pane fade category_children" id="category_children" role="tabpanel"
                                    aria-labelledby="category_children">

                                    @include('panel.modules.categories.modules.category_children')

                                </div>
                                @endif
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

    const categoryGuidBtns = document.getElementsByClassName('delete_btns');
    Array.from(categoryGuidBtns).forEach(btn => {
        btn.addEventListener('click',() => {
            const guid = btn.getAttribute('guid');
            const cname = btn.getAttribute('cname');
            const hiddenBtn = document.getElementById('delete_guide');
            const nameModalDelete = document.getElementById('name_modal_delete');
            nameModalDelete.innerHTML = `You are about to delete an attribute group. Are you sure you want to delete ${cname}?`;
            hiddenBtn.value = guid;
        });
    });

    @if(Session::has('errorValidate'))
        toastr.error('Please enter at least 2 letters.');
    @endif
    @if(Session::has('successUpdateParentCategory'))
        toastr.success('Parent Category successfully updated.');
    @endif
    @if(Session::has('successUpdateChildCategory'))
        toastr.success('Child Category successfully updated.');
    @endif
    @if(Session::has('successDeleteCategory'))
        toastr.success('Attribute group successfully deleted.');
    @endif

    @if(Session::has('successDeletedCategoryChildrenInside') && $category->children_count > 0)
        toastr.success('Child category successfully deleted.');
        const general_settings = document.getElementsByClassName('general_settings')[1];
        general_settings.classList.remove('show');
        general_settings.classList.remove('active');
        const category_children = document.getElementsByClassName('category_children')[1];
        category_children.classList.add('show');
        category_children.classList.add('active');
        const general_settingsTab = document.getElementsByClassName('general_settings')[0];
        general_settingsTab.classList.remove('active');
        const category_childrenTab = document.getElementsByClassName('category_children')[0];
        category_childrenTab.classList.add('active');
    @endif

    @if(isset(request()->children) || $search_children == 1)
        const general_settings = document.getElementsByClassName('general_settings')[1];
        general_settings.classList.remove('show');
        general_settings.classList.remove('active');
        const category_children = document.getElementsByClassName('category_children')[1];
        category_children.classList.add('show');
        category_children.classList.add('active');
        const general_settingsTab = document.getElementsByClassName('general_settings')[0];
        general_settingsTab.classList.remove('active');
        const category_childrenTab = document.getElementsByClassName('category_children')[0];
        category_childrenTab.classList.add('active');
    @endif

    @if(Session::has('successDeletedCategoryChildrenInside') && $category->children_count == 0)
        toastr.success('Child category successfully deleted.');
    @endif

    @if(isset(request()->locations) || $search_locations == 1)
        const general_settings = document.getElementsByClassName('general_settings')[1];
        general_settings.classList.remove('show');
        general_settings.classList.remove('active');
        const locations = document.getElementsByClassName('locations')[1];
        locations.classList.add('show');
        locations.classList.add('active');
        const general_settingsTab = document.getElementsByClassName('general_settings')[0];
        general_settingsTab.classList.remove('active');
        const locationsTab = document.getElementsByClassName('locations')[0];
        locationsTab.classList.add('active');
    @endif

    @if(isset(request()->attr_groups) || $search_attr_groups == 1)
        const general_settings = document.getElementsByClassName('general_settings')[1];
        general_settings.classList.remove('show');
        general_settings.classList.remove('active');
        const attribute_groups = document.getElementsByClassName('attribute_groups')[1];
        attribute_groups.classList.add('show');
        attribute_groups.classList.add('active');
        const general_settingsTab = document.getElementsByClassName('general_settings')[0];
        general_settingsTab.classList.remove('active');
        const attribute_groupsTab = document.getElementsByClassName('attribute_groups')[0];
        attribute_groupsTab.classList.add('active');
    @endif
</script>
@endsection
