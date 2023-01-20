<div class="row mt-5">
    <div class="col-lg-12">
        <div class="card card-custom">
            <div class="row mt-5">
                <div class="col-lg-12">
                    <div class="card card-custom">
                        <div class="card-header">
                            <div class="card-toolbar">
                                <ul class="nav nav-light-success nav-bold nav-pills">
                                    <li class="nav-item">
                                        <a class="nav-link active models" data-toggle="tab" href="#rates">Policies
                                            List</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body py-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="example-preview">
                                        <div class="tab-content" id="myTabContent">
                                            <!--MODELS-->
                                            <div class="tab-pane fade show active rates" id="rates" role="tabpanel"
                                                aria-labelledby="rates">
                                                <div class="table-responsive">
                                                    <table class="table table-head-custom table-vertical-center"
                                                        id="kt_advance_table_widget_1">
                                                        <thead>
                                                            <tr class="text-left">
                                                                <th style="min-width: 140px">Id</th>
                                                                <th style="min-width: 140px">Title(EN)</th>
                                                                <th style="min-width: 150px">Detail/Delete</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!--begin::Form-->
                                                            @foreach ($policies as $p)
                                                            <tr>
                                                                <td>
                                                                    <span class="text-dark-75 d-block font-size-lg">{{ $p->id }}</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-75 d-block font-size-lg">{{ $p->title_en }}</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-75 d-block font-size-lg">
                                                                        <a href="{{ route('admin.policies.detail',$p->policy_guid) }}" class="btn btn-icon btn-success">
                                                                            <i class="flaticon-eye"></i>
                                                                        </a>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                            <br>
                                                            <!--end::Form-->
                                                            <br>
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
        </div>
    </div>
</div>
