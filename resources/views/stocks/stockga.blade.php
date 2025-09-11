@extends('layout.app')
@section('content')
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card-header">
                    <h4 class="card-title">Menu Stock GA</h4>
                </div>
                <div class="card-body">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-stock" id="btnstock">
                        Add Stock
                    </button>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-importstock" id="btnimportstock">
                        Import Stock
                    </button>
                    <button class="btn btn-yellow" data-bs-toggle="modal" data-bs-target="#modal-stock-edit" id="modaleditstock">
                        Edit Stock
                    </button>
                    <button type="submit" class="btn btn-danger" id="deletestock">Delete Stock</button>
                    <table id="stockTable" class="table card-table table-vcenter datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Code Barang</th>
                                <th>Nama Barang</th>
                                <th>Spek</th>
                                <th>Satuan</th>
                                <th>Stock Awal</th>
                                <th>MIN Stock</th>
                                <th>MAX Stock</th>
                                <th>Factory</th>
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
    <div class="modal modal-blur fade" id="modal-stock" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="formaddstock" action="" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">New Stock</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Code Barang</label>
                            <select name="code_barang" id="code_barang" class="form-control">
                                <option value="" disabled selected hidden>Pilih</option>
                                    @foreach($barang as $data)
                                        <option value="{{$data->code_barang}}">{{$data->code_barang}}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" name="nama_barang" id="nama_barang" readonly>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Spesifikasi</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="spek" class="form-control" id="spek" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Satuan</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="satuan" id="satuan" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Status Stock</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="status_stock" id="status_stock" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Stock</label>
                                    <div class="input-group input-group-flat">
                                        <input type="number" name="stock" id="stock" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">MIN Stock</label>
                                    <div class="input-group input-group-flat">
                                        <input type="number" name="min_stock" id="min_stock" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">STD Stock</label>
                                    <div class="input-group input-group-flat">
                                        <input type="number" name="std_stock" id="std_stock" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Factory</label>
                                    <div class="input-group input-group-flat">
                                        <input type="number" name="factory" id="factory" class="form-control" value="{{auth()->user()->factory}}" readonly>
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
                        <button type="submit" id="addstock" class="btn btn-primary ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Create new stock
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
    <div class="modal modal-blur fade" id="modal-importstock" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="formimportstock" action="" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">New Stock</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label">File Excel</label>
                                <input type="file" class="form-control" name="file" id="file">
                            </div>
                            <div class="mb-3">
                                <p>Template Excel For Import Data Stock</p>
                                <a href="{{url('../mart-service/public/import/ImportStock.xlsx')}}" class="btn btn-sm btn-success">Excel</a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="importstock" class="btn btn-green ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Import stock
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
                <form id="formeditstock" action="" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Update Stock</h5>
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
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Stock</label>
                                    <div class="input-group input-group-flat">
                                        <input type="number" name="stock" class="form-control" id="ustock">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">MIN Stock</label>
                                    <div class="input-group input-group-flat">
                                        <input type="number" name="min_stock" class="form-control" id="umin_stock">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">STD Stock</label>
                                    <div class="input-group input-group-flat">
                                        <input type="number" name="std_stock" class="form-control" id="ustd_stock">
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
                        <button type="submit" id="editstock" class="btn btn-yellow ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Update Stock
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
      var getStockGA = "{{route('stockga')}}";
      var createStockGA = "{{url('/stock')}}";
      var editStockGA = "{{url('/stock')}}";
      var deleteStockGA = "{{url('/stock')}}";
      var getApiEmployee = "{{url('/apidata')}}";
      var csrfToken = "{{ csrf_token() }}";
      var getBarangGA = "{{route('getbarang')}}";
      var importStock = "{{url('/stock/import')}}";
    </script>
    <script src="{{asset('js/stockga.js')}}"></script>
@endsection
