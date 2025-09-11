@extends('layout.app')
@section('content')
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card-header">
                    <h4 class="card-title">{{$title}}</h4>
                </div>
                <input type="hidden" name="id" id="id" value="{{$id}}">
                <input type="hidden" name="startdate" id="startdate" value="{{$startdate}}">
                <input type="hidden" name="enddate" id="enddate" value="{{$enddate}}">
                <div class="card-body">
                    <hr class="mb-1 mt-2">
                    <table id="reportTable" class="table card-table table-vcenter datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Code Barang</th>
                                <th>Nama Barang</th>
                                <th>Spek</th>
                                <th>Satuan</th>
                                <th>Factory</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
      var getReportLA = "{{url('/detailla')}}";
      var getApiEmployee = "{{url('/apidata')}}";
      var csrfToken = "{{ csrf_token() }}";
    </script>
    <script src="{{asset('js/detailla.js')}}"></script>
@endsection
