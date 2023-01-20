<!-- Table Begin -->
<div class="row mt-5">
    <div class="col-lg-12">
        <!--begin::Advance Table Widget 1-->
        <div class="card card-custom gutter-b">

            <div class="card-header">
                <div class="card-title">
                    <form action="{{ route('admin.attributes.home') }}" method="GET" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-9 col-sm-9">
                                <div class="input-icon">
                                    <input name="name_en" type="text" class="form-control" placeholder="Search..." />
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

                <div class="card-toolbar">
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                        data-target="#add_attribute_modal">
                        <i class="fa fa-plus pr-0 mr-2"></i> Attribute Add
                    </button>
                    <button type="button" class="btn btn-sm btn-primary ml-4" data-toggle="modal"
                        data-target="#add_multiple_attribute_modal">
                        <i class="fa fa-plus pr-0 mr-2"></i>Multiple Attribute Add
                    </button>
                </div>
            </div>

            <!--begin::Body-->
            <div class="card-body py-4">
                <!--begin::Table-->
                <div class="table-responsive">

                    @if (count($attributes) > 0)
                    <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                        <thead>

                            <tr class="text-left">
                                <th style="max-width: 100px">Id</th>
                                <th style="min-width: 150px">Name(EN)</th>
                                <th style="min-width: 150px">Name(AR)</th>
                                <th style="min-width: 100px">Attribute G.</th>
                                <th style="min-width: 180px">Detail/Delete</th>
                            </tr>

                        </thead>
                        <tbody>

                            @foreach ($attributes as $attribute)
                            <tr>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{ $attribute->id
                                        }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $attribute->name_en }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $attribute->name_ar }}</span>
                                </td>
                                <td>
                                    <span class="text-dark-75 d-block font-size-lg">{{
                                        $attribute->attributes_info_count }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.attributes.detail',$attribute->attribute_guid) }}"
                                        class="btn btn-icon btn-success">
                                        <i class="flaticon-eye"></i>
                                    </a>
                                    <button guid="{{ $attribute->attribute_guid }}" cname="{{ $attribute->name_en }}"
                                        data-toggle="modal" data-target="#delete_attribute_modal" type="button"
                                        class="btn btn-icon btn-danger delete_btns">
                                        <i class="flaticon2-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    @else

                    <br />
                    <span class="text-dark-75 d-block font-size-lg text-lg-center">
                        No Attributes Added.</span>
                    <br />

                    @endif
                </div>
            </div>

            @if($attributes->lastPage() > 1)
            <div class="card-footer">
                {{
                $attributes->appends(request()->input())->links('panel.modules.global.paginator',['paginator'=>$attributes])
                }}
            </div>
            @endif

        </div>
        <!--end::Advance Table Widget 1-->
    </div>
</div>
<!-- Table End -->

<!--attribute delete modal-->
<div class="modal fade" id="delete_attribute_modal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-content" role="document">
        <div class="modal-header">
            <h5 class="modal-title" id="attributeModalLabel">Attribute Delete</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i aria-hidden="true" class="ki ki-close"></i>
            </button>
        </div>

        <div class="modal-body" style="text-align: center">

            <div id="name_modal_delete">

            </div>

        </div>

        <form class="w-100" method="post" action="{{ route('admin.attributes.delete') }}">
            @csrf
            <div class="modal-footer">
                <input type="hidden" name="attribute_guid" id="delete_guide" value="" required />
                <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary font-weight-bold">Delete</button>
            </div>

        </form>


    </div>
</div>

<!--attribute add modal-->
<div class="modal fade" id="add_attribute_modal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" method="POST" action="{{ route('admin.attributes.add') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="attributeModalLabel">Attribute Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>



            <div class="modal-body">
                <div class="form-group">
                    <label>Attribute Name</label>
                    <input type="text" name="name_en" class="form-control form-control-lg" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary font-weight-bold">Save</button>
            </div>
        </form>
    </div>
</div>
<!--attribute multiple add modal-->
<div class="modal fade" id="add_multiple_attribute_modal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" method="POST" enctype="multipart/form-data"
            action="{{ route('admin.attributes.multiple_add') }}">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="attributeModalLabel">Multiple Attribute Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="custom-file">
                        <input name="file" type="file" class="custom-file-input" id="imgInp2" />
                        <label class="custom-file-label" for="customFile">Choose
                            File</label>
                        <span class="form-text text-muted">Allowed file extensions: xlsx</span>
                    </div>
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
    const categoryGuidBtns = document.getElementsByClassName('delete_btns');
    Array.from(categoryGuidBtns).forEach(btn => {
        btn.addEventListener('click',() => {
            const guid = btn.getAttribute('guid');
            const cname = btn.getAttribute('cname');
            const hiddenBtn = document.getElementById('delete_guide');
            const nameModalDelete = document.getElementById('name_modal_delete');
            nameModalDelete.innerHTML = `You are about to delete an attribute. Are you sure you want to delete ${cname}?`;
            hiddenBtn.value = guid;
        });
    });
    @if(Session::has('errorValidate'))
        toastr.error('Please enter at least 2 letters.');
    @endif
    @if(Session::has('successAddAttribute'))
        toastr.success('Attribute successfully added.');
    @endif
    @if(Session::has('successDeleteAttribute'))
        toastr.success('Attribute successfully deleted.');
    @endif
    @if(Session::has('errorAddMultipleAttribute'))
        toastr.error('The attribute you want to add is already added please check your file and try again.');
    @endif
    @if(Session::has('errorTableMultipleAdd'))
        toastr.error('This file is not allowed to database table please fix your file.');
    @endif
    @if(Session::has('succcesMultipleAdd'))
        toastr.success('Attributes added successfully from file.');
    @endif

</script>
@endsection
