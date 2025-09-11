@extends('layout.app')
@section('content')
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">User</div>
                        </div>
                        <div class="h1 mb-3">{{$user}}</div>
                        <div class="d-flex mb-2">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Barang</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-0 me-2">{{$barang}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Lane</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-3 me-2">{{$lane}}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Transaction</div>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <div class="h1 mb-3 me-2">{{$transaction}}</div>
                            <div class="me-auto">
                                {{-- <span class="text-green d-inline-flex align-items-center lh-1">
                                    4% <!-- Download SVG icon from http://tabler-icons.io/i/trending-up -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 17l6 -6l4 4l8 -8" />
                                        <path d="M14 7l7 0l0 7" />
                                    </svg>
                                </span> --}}
                            </div>
                        </div>
                        {{-- <div id="chart-active-users" class="chart-sm"></div> --}}
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-header">
                            <h4>Transaction</h4>
                        </div>
                        <div class="card-body">
                            <div>
                                <canvas id="myChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript">
        let myChart;

        function fetchDataAndUpdateChart() {
            $.ajax({
                url: "{{ route('countchart') }}",
                method: 'GET',
                success: function(data) {
                    const ctx = document.getElementById('myChart').getContext('2d');

                    if (myChart) {
                        myChart.data.datasets[0].data = Object.values(data);
                        myChart.data.labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep',
                            'Oct', 'Nov', 'Dec'
                        ];
                        myChart.update();
                    } else {
                        myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep',
                                    'Oct', 'Nov', 'Dec'
                                ],
                                datasets: [{
                                    label: 'Transaction ' + new Date().getFullYear(),
                                    data: Object.values(data),
                                    borderWidth: 1,
                                    barThickness: 40
                                }]
                            },
                            options: {
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data: ', error);
                }
            });
        }

        fetchDataAndUpdateChart();
        setInterval(fetchDataAndUpdateChart, 500000);
    </script>
@endsection
