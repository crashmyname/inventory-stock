@extends('layout.app')
@section('content')
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card-header">
                    <h4 class="card-title">Menu Barang</h4>
                </div>
                <div class="card-body">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-barang" id="btnbarang">
                        Add Barang
                    </button>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-importbarang" id="btnimportbarang">
                        Import Barang
                    </button>
                    <button class="btn btn-yellow" data-bs-toggle="modal" data-bs-target="#modal-barang-edit" id="modaleditbarang">
                        Edit Barang
                    </button>
                    <button type="submit" class="btn btn-danger" id="deletebarang">Delete Barang</button>
                    <hr>
                    <table id="barangTable" class="table card-table table-vcenter datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Code Barang</th>
                                <th>Nama Barang</th>
                                <th>Spek</th>
                                <th>Satuan</th>
                                <th>Barcode</th>
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
    <div class="modal modal-blur fade" id="modal-barang" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="formaddbarang" action="" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">New Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Code Barang</label>
                            <input type="text" class="form-control" name="code_barang" id="codebarang" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" name="nama_barang" id="namabarang">
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Spesifikasi</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="spek" class="form-control" id="spek">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Satuan</label>
                                    <div class="input-group input-group-flat">
                                        <select name="satuan" id="satuan" class="form-control">
                                            <option value="" hidden selected disabled> Pilih </option>
                                            <option value="PCS">PCS</option>
                                            <option value="BOX">BOX</option>
                                            <option value="KG">KG</option>
                                            <option value="M">M</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
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
                        <button type="submit" id="addbarang" class="btn btn-primary ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Create new barang
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
    <div class="modal modal-blur fade" id="modal-importbarang" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="formimportbarang" action="" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">New barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label">File Excel</label>
                                <input type="file" class="form-control" name="file" id="file" accept=".xls,.xlsx">
                            </div>
                            <div class="mb-3">
                                <p>Template Excel For Import Data barang</p>
                                <a href="{{url('../mart-service/public/import/ImportBarang.xlsx')}}" class="btn btn-sm btn-success">Excel</a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="importbarang" class="btn btn-green ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Import barang
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
                <form id="formeditbarang" action="" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Update Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Code Barang</label>
                            <input type="text" class="form-control" name="code_barang" id="ucodebarang" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" name="nama_barang" id="unamabarang">
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Spesifikasi</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="spek" class="form-control" id="uspek">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Satuan</label>
                                    <div class="input-group input-group-flat">
                                        <select name="satuan" id="usatuan" class="form-control">
                                            <option value="PCS">PCS</option>
                                            <option value="BOX">BOX</option>
                                            <option value="KG">KG</option>
                                            <option value="M">M</option>
                                        </select>
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
                        <button type="submit" id="editbarang" class="btn btn-yellow ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Update Barang
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
      var getBarang = "{{route('barang.index')}}";
      var createBarang = "{{url('/barang')}}";
      var editBarang = "{{url('/barang')}}";
      var deleteBarang = "{{url('/barang')}}";
      var getApiEmployee = "{{url('/apidata')}}";
      var csrfToken = "{{ csrf_token() }}";
      var importBarang = "{{url('/barang/import')}}";
      var generateCodeBarang = "{{route('barang.generatecode')}}";
      var pathBarcode = "{{url('/barcode/')}}";
    </script>
    <script src="{{asset('js/barang.js')}}"></script>
@endsection
