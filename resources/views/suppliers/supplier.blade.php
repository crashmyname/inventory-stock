@extends('layout.app')
@section('content')
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card-header">
                    <h4 class="card-title">Menu Supplier</h4>
                </div>
                <div class="card-body">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-supplier" id="btnsupplier">
                        Add Supplier
                    </button>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-importsupplier" id="btnimportsupplier">
                        Import Supplier
                    </button>
                    <button class="btn btn-yellow" data-bs-toggle="modal" data-bs-target="#modal-supplier-edit" id="modaleditsupplier">
                        Edit Supplier
                    </button>
                    <button type="submit" class="btn btn-danger" id="deletesupplier">Delete Supplier</button>
                    <table id="supplierTable" class="table card-table table-vcenter datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Supplier</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-blur fade" id="modal-supplier" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="formaddsupplier" action="" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">New Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Supplier</label>
                            <input type="text" class="form-control" name="supplier_name" id="supplier_name">
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Keterangan</label>
                                    <div class="input-group input-group-flat">
                                        <textarea name="keterangan" id="keterangan" class="form-control" cols="30" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="addsupplier" class="btn btn-primary ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Create new Supplier
                        </button>
                        <button class="btn btn-primary ms-auto" style="display: none;" id="loading" disabled>
                            <div class="spinner-border me-2" role="status"></div>
                            <strong>Loading...</strong>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal modal-blur fade" id="modal-importsupplier" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="formimportsupplier" action="" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">New supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label">File Excel</label>
                                <input type="file" class="form-control" name="file" id="file" accept=".xls,.xlsx">
                            </div>
                            <div class="mb-3">
                                <p>Template Excel For Import Data supplier</p>
                                <a href="{{url('../mart-service/public/import/ImportSupplier.xlsx')}}" class="btn btn-sm btn-success">Excel</a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="importsupplier" class="btn btn-green ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Import supplier
                        </button>
                        <button class="btn btn-green ms-auto" style="display: none;" id="loadingimport" disabled>
                            <div class="spinner-border me-2" role="status"></div>
                            <strong>Loading...</strong>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal modal-blur fade" id="modalEdit" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="formeditsupplier" action="" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Update Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Supplier Name</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="supplier_name" class="form-control" id="usupplier_name">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Keterangan</label>
                                    <div class="input-group input-group-flat">
                                        <textarea name="keterangan" id="uketerangan" class="form-control" cols="30" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="editsupplier" class="btn btn-yellow ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Update Supplier
                        </button>
                        <button class="btn btn-yellow ms-auto" style="display: none;" id="loadingupdate" disabled>
                            <div class="spinner-border me-2" role="status"></div>
                            <strong>Loading...</strong>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
      var getSupplier = "{{route('supplier.index')}}";
      var createSupplier = "{{url('/supplier')}}";
      var editSupplier = "{{url('/supplier')}}";
      var deleteSupplier = "{{url('/supplier')}}";
      var getApiEmployee = "{{url('/apidata')}}";
      var csrfToken = "{{ csrf_token() }}";
      var importSupplier = "{{url('/supplier/import')}}";
    </script>
    <script src="{{asset('js/supplier.js')}}"></script>
@endsection
