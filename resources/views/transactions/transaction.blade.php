@extends('layout.app')
@section('content')
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card-header">
                    <h4 class="card-title">Menu Transaction</h4>
                </div>
                <div class="card-body">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-transaction" id="btntransaction">
                        Add transaction
                    </button>
                    <button class="btn btn-cyan" data-bs-toggle="modal" data-bs-target="#modal-barcodetransaction" id="btnbarcodetransaction">
                        Barcode transaction
                    </button>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-scantransaction" id="btntscanransaction">
                        Scan transaction
                    </button>
                    <button class="btn btn-yellow" data-bs-toggle="modal" data-bs-target="#modal-transaction-edit" id="modaledittransaction">
                        Edit transaction
                    </button>
                    <button type="submit" class="btn btn-danger" id="deletetransaction">Delete transaction</button>
                    <hr class="mb-1 mt-2">
                    <form action="" method="" id="formhistory" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <div class="input-group input-group-flat">
                                        <input type="date" name="start_date" class="form-control" id="hidden_start_date">
                                        <input type="hidden" name="start_date" class="form-control" id="start_date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <div class="input-group input-group-flat">
                                        <input type="date" name="end_date" class="form-control" id="hidden_end_date">
                                        <input type="hidden" name="end_date" class="form-control" id="end_date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <button class="btn btn-primary" type="submit" id="search">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                                        Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <table id="transactionTable" class="table card-table table-vcenter datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>NIK</th>
                                <th>PIC</th>
                                <th>Section</th>
                                @if(auth()->user()->role == 'Admin LA' || auth()->user()->role == 'Administrator')
                                <th>No Lane</th>
                                @endif
                                @if(auth()->user()->role == 'Admin MDF' || auth()->user()->role == 'Administrator')
                                <th>Supplier</th>
                                @endif
                                <th>Quantity</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Factory</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>NIK</th>
                                <th>PIC</th>
                                <th>Section</th>
                                @if(auth()->user()->role == 'Admin LA' || auth()->user()->role == 'Administrator')
                                <th>No Lane</th>
                                @endif
                                @if(auth()->user()->role == 'Admin MDF' || auth()->user()->role == 'Administrator')
                                <th>Supplier</th>
                                @endif
                                <th>Quantity</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Factory</th>
                                <th>Keterangan</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-blur fade" id="modal-transaction" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="formaddtransaction" action="" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">New transaction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Code Barang</label>
                                    <select name="code_barang" id="code_barang" class="form-control select2" style="width: 100%;">
                                        <option value="" disabled selected hidden>Pilih</option>
                                            @foreach($barang as $data)
                                                <option value="{{$data->code_barang}}">{{$data->code_barang}} {{$data->nama_barang}}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Barang</label>
                                    <input type="text" class="form-control" name="nama_barang" id="nama_barang" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Spesifikasi</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="spek" class="form-control" id="spek" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Satuan</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="satuan" id="satuan" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Status Stock</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="status_stock" id="status_stock" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Factory</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="factory" id="factory" class="form-control" readonly value="{{auth()->user()->factory}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">NIK</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="nik" id="nik" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="nama" id="nama" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Section</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="section" id="section" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            @if(auth()->user()->role == 'Admin LA' || auth()->user()->role == 'Admin PP1' || auth()->user()->role == 'Admin PP2' || auth()->user()->role == 'Administrator')
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">No Lane</label>
                                    <div class="input-group input-group-flat">
                                        <select name="no_lane" id="no_lane" class="form-control select2" style="width: 100%;">
                                            <option value="" disabled selected hidden>Pilih</option>
                                                @foreach($lane as $data)
                                                    <option value="{{$data->no_lane}}">{{$data->no_lane}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @if(auth()->user()->role == 'Admin MDF' || auth()->user()->role == 'Administrator' || auth()->user()->role == 'Admin GA')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">ACTUAL STOCK</label>
                                    <div class="input-group input-group-flat">
                                        <input type="number" name="stock" id="stock" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">MIN STOCK</label>
                                    <div class="input-group input-group-flat">
                                        <input type="number" name="min_stock" id="min_stock" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">STD STOCK</label>
                                    <div class="input-group input-group-flat">
                                        <input type="number" name="std_stock" id="std_stock" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Quantity</label>
                                    <div class="input-group input-group-flat">
                                        <input type="number" name="qty" id="qty" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal</label>
                                    <div class="input-group input-group-flat">
                                        <input type="date" name="hidden_date" id="hidden_date" class="form-control">
                                        <input type="hidden" name="tanggal" id="tanggal">
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
                        <button type="submit" id="addtransaction" class="btn btn-primary ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Create new transaction
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
    <div class="modal modal-blur fade" id="modal-barcodetransaction" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="formaddbarcodetransaction" action="" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">New transaction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Code Barang</label>
                                    {{-- <select name="code_barang" id="bcode_barang" class="form-control select2" style="width: 100%;" autofocus>
                                        <option value="" disabled selected hidden>Pilih</option>
                                            @foreach($barang as $data)
                                                <option value="{{$data->code_barang}}">{{$data->code_barang}} {{$data->nama_barang}}</option>
                                            @endforeach
                                    </select> --}}
                                    <input type="text" name="code_barang" id="bcode_barang" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Barang</label>
                                    <input type="text" class="form-control" name="nama_barang" id="bnama_barang" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Spesifikasi</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="spek" class="form-control" id="bspek" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Satuan</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="satuan" id="bsatuan" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Status Stock</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="status_stock" id="bstatus_stock" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Factory</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="factory" id="bfactory" class="form-control" readonly value="{{auth()->user()->factory}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">NIK</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="nik" id="bnik" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="nama" id="bnama" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Section</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="section" id="bsection" class="form-control">
                                    </div>
                                </div>
                            </div>
                            @if(auth()->user()->role == 'Admin LA' || auth()->user()->role == 'Admin PP1' || auth()->user()->role == 'Admin PP2' || auth()->user()->role == 'Administrator')
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">No Lane</label>
                                    <div class="input-group input-group-flat">
                                        <select name="no_lane" id="bno_lane" class="form-control select2" style="width: 100%;">
                                            <option value="" disabled selected hidden>Pilih</option>
                                                @foreach($lane as $data)
                                                    <option value="{{$data->no_lane}}">{{$data->no_lane}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @if(auth()->user()->role == 'Admin MDF' || auth()->user()->role == 'Administrator' || auth()->user()->role == 'Admin GA')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">ACTUAL STOCK</label>
                                    <div class="input-group input-group-flat">
                                        <input type="number" name="stock" id="bstock" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">MIN STOCK</label>
                                    <div class="input-group input-group-flat">
                                        <input type="number" name="min_stock" id="bmin_stock" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">STD STOCK</label>
                                    <div class="input-group input-group-flat">
                                        <input type="number" name="std_stock" id="bstd_stock" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Quantity</label>
                                    <div class="input-group input-group-flat">
                                        <input type="number" name="qty" id="bqty" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal</label>
                                    <div class="input-group input-group-flat">
                                        <input type="date" name="hidden_date" id="bhidden_date" class="form-control">
                                        <input type="hidden" name="tanggal" id="btanggal">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Keterangan</label>
                                    <div class="input-group input-group-flat">
                                        <textarea name="keterangan" id="bketerangan" class="form-control" cols="30" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="addbarcodetransaction" class="btn btn-primary ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Create new transaction
                        </button>
                        <button class="btn btn-primary ms-auto" style="display: none;" id="bloading" disabled>
                            <div class="spinner-border me-2" role="status"></div>
                            <strong>Loading...</strong>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal modal-blur fade" id="modal-scantransaction" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="formaddscantransaction" action="" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">New transaction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="scanner-container">
                            <center>
                            <video id="video" width="55%" style="border-radius: 5%"></video>
                            {{-- <canvas id="canvas"></canvas> --}}
                            </center>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Code Barang</label>
                                    <input type="text" name="code_barang" id="scode_barang" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Barang</label>
                                    <input type="text" class="form-control" name="nama_barang" id="snama_barang" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Spesifikasi</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="spek" class="form-control" id="sspek" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Satuan</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="satuan" id="ssatuan" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Status Stock</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="status_stock" id="sstatus_stock" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Factory</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="factory" id="sfactory" class="form-control" readonly value="{{auth()->user()->factory}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">NIK</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="nik" id="snik" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="nama" id="snama" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Section</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="section" id="ssection" class="form-control">
                                    </div>
                                </div>
                            </div>
                            @if(auth()->user()->role == 'Admin LA' || auth()->user()->role == 'Admin PP1' || auth()->user()->role == 'Admin PP2' || auth()->user()->role == 'Administrator')
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">No Lane</label>
                                    <div class="input-group input-group-flat">
                                        <select name="no_lane" id="sno_lane" class="form-control select2" style="width: 100%;">
                                            <option value="" disabled selected hidden>Pilih</option>
                                                @foreach($lane as $data)
                                                    <option value="{{$data->no_lane}}">{{$data->no_lane}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @if(auth()->user()->role == 'Admin MDF' || auth()->user()->role == 'Administrator' || auth()->user()->role == 'Admin GA')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">ACTUAL STOCK</label>
                                    <div class="input-group input-group-flat">
                                        <input type="number" name="stock" id="sstock" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">MIN STOCK</label>
                                    <div class="input-group input-group-flat">
                                        <input type="number" name="min_stock" id="smin_stock" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">STD STOCK</label>
                                    <div class="input-group input-group-flat">
                                        <input type="number" name="std_stock" id="sstd_stock" class="form-control" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Quantity</label>
                                    <div class="input-group input-group-flat">
                                        <input type="number" name="qty" id="sqty" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal</label>
                                    <div class="input-group input-group-flat">
                                        <input type="date" name="hidden_date" id="shidden_date" class="form-control">
                                        <input type="hidden" name="tanggal" id="stanggal">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Keterangan</label>
                                    <div class="input-group input-group-flat">
                                        <textarea name="keterangan" id="sketerangan" class="form-control" cols="30" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="resetscantransaction" class="btn btn-secondarys">Reset</button>
                        <button type="submit" id="addstransaction" class="btn btn-primary ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Create new transaction
                        </button>
                        <button class="btn btn-primary ms-auto" style="display: none;" id="sloading" disabled>
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
                <form id="formedittransaction" action="" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Update transaction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Code Barang</label>
                                    <input type="text" name="code_barang" id="ucode_barang" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Barang</label>
                                    <input type="text" class="form-control" name="nama_barang" id="unama_barang" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Spesifikasi</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="spek" class="form-control" id="uspek" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Satuan</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="satuan" id="usatuan" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Status Stock</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="status_stock" id="ustatus_stock" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Factory</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="factory" id="ufactory" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">NIK</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="nik" id="unik" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="nama" id="unama" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Section</label>
                                    <div class="input-group input-group-flat">
                                        <input type="text" name="section" id="usection" class="form-control">
                                    </div>
                                </div>
                            </div>
                            @if(auth()->user()->role == 'Admin LA' || auth()->user()->role == 'Admin PP1' || auth()->user()->role == 'Admin PP2' || auth()->user()->role == 'Administrator')
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">No Lane</label>
                                    <div class="input-group input-group-flat">
                                        <select name="no_lane" id="uno_lane" class="form-control">
                                            <option value="" disabled selected hidden>Pilih</option>
                                                @foreach($lane as $data)
                                                    <option value="{{$data->no_lane}}">{{$data->no_lane}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(auth()->user()->role == 'Admin MDF' || auth()->user()->role == 'Administrator')
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Supplier</label>
                                    <div class="input-group input-group-flat">
                                        <select name="supplier_name" id="usupplier_name" class="form-control">
                                            <option value="" disabled selected hidden>Pilih</option>
                                                @foreach($supplier as $data)
                                                    <option value="{{$data->supplier_name}}">{{$data->supplier_name}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Quantity</label>
                                    <div class="input-group input-group-flat">
                                        <input type="number" name="qty" id="uqty" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal</label>
                                    <div class="input-group input-group-flat">
                                        <input type="date" name="tanggal" id="utanggal" class="form-control">
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
                        <button type="submit" id="edittransaction" class="btn btn-yellow ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Update transaction
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
    <script src="{{asset('js/rxingbarcode.min.js')}}"></script>
    <script>
      var getTransaction = "{{route('transaction.data')}}";
      var createTransaction = "{{url('/transaction')}}";
      var editTransaction = "{{url('/transaction')}}";
      var deleteTransaction = "{{url('/transaction')}}";
      var getApiEmployee = "{{url('/apidata')}}";
      var csrfToken = "{{ csrf_token() }}";
      var getBarangs = "{{route('getbarang')}}";
      var userRole = "{{auth()->user()->role}}";
      const codeReader = new ZXing.BrowserMultiFormatReader();
      const videoElement = document.getElementById('video');
    </script>
    <script src="{{asset('js/transaction.js')}}"></script>
    {{-- @if(auth()->user()->role == 'Admin MDF' || auth()->user()->role == 'Administrator')
    <script src="{{asset('js/transaction.js')}}"></script>
    @endif --}}
@endsection
