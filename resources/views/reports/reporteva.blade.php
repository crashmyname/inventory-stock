@extends('layout.app')
@section('content')
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card-header">
                    <h4 class="card-title">Menu Report EVA</h4>
                </div>
                <div class="card-body">
                    <hr class="mb-1 mt-2">
                    <form action="" method="" id="formhistory" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="">Start Date</label>
                                    <div class="input-group input-group-flat">
                                        <input type="date" name="start_date" class="form-control" id="hidden_start_date">
                                        <input type="hidden" name="start_date" class="form-control" id="start_date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="">End Date</label>
                                    <div class="input-group input-group-flat">
                                        <input type="date" name="end_date" class="form-control" id="hidden_end_date">
                                        <input type="hidden" name="end_date" class="form-control" id="end_date">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <br>
                                    <button class="btn btn-primary" type="submit" id="search">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                                        Search
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-lg-3 mb-3">
                            <button class="btn btn-info" id="detail">Detail</button>
                        </div>
                    </div>
                    <table id="reportTable" class="table card-table table-vcenter datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Code Barang</th>
                                <th>Nama Barang</th>
                                <th>Spek</th>
                                <th>Satuan</th>
                                <th>Factory</th>
                                <th>Initial Stock</th>
                                <th>IN</th>
                                <th>OUT</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Code Barang</th>
                                <th>Nama Barang</th>
                                <th>Spek</th>
                                <th>Satuan</th>
                                <th>Factory</th>
                                <th>Initial Stock</th>
                                <th>IN</th>
                                <th>OUT</th>
                                <th>Balance</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
      var getReportEVA = "{{ route('reporteva') }}";
      var detailEVA = "{{ url('/detaileva') }}";
      var getApiEmployee = "{{url('/apidata')}}";
      var csrfToken = "{{ csrf_token() }}";
    </script>
    <script src="{{asset('js/reporteva.js')}}"></script>
@endsection
