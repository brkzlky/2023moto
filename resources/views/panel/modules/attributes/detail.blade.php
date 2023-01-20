<!--start::Statistics-->
<div class="row">
    <div class="col-xl-3">
        <!--begin::Stats Widget 25-->
        <div class="card card-custom mt-3">
            <!--begin::Body-->
            <div class="card-body">
                <span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 d-block">{{
                    $attribute->attributes_info_count
                    }}</span>
                <span class="font-weight-bold text-muted font-size-sm"><a
                        href="{{ route('admin.attribute_groups.home') }}" target="_blank">Attribute Groups</a></span>
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
                            <a class="nav-link active attribute" data-toggle="tab" href="#attribute">
                                <span class="nav-text">Attribute</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link attribute_group" data-toggle="tab" href="#attribute_group">
                                <span class="nav-text">Related Attribute G.</span>
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

                                <div class="tab-pane fade show active attribute" id="attribute" role="tabpanel"
                                    aria-labelledby="attribute">

                                    @include('panel.modules.attributes.modules.attribute_settings')


                                </div>
                                <div class="tab-pane fade attribute_group" id="attribute_group" role="tabpanel"
                                    aria-labelledby="attribute_group">

                                    @include('panel.modules.attributes.modules.attribute_attribute_groups')

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
    @if(isset(request()->attribute_group) || $search_on == 1)
        const attributeTab = document.getElementsByClassName('attribute')[0];
        attributeTab.classList.remove('active');
        const attribute = document.getElementsByClassName('attribute')[1];
        attribute.classList.remove('show');
        attribute.classList.remove('active');
        const attribute_groupTab = document.getElementsByClassName('attribute_group')[0];
        attribute_groupTab.classList.add('active');
        const attribute_group = document.getElementsByClassName('attribute_group')[1];
        attribute_group.classList.add('show');
        attribute_group.classList.add('active');
    @endif
</script>
@endsection
