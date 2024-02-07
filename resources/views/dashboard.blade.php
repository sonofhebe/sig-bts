@extends('layouts.master')
@section('content')
    <!-- Load Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="panel panel-headline">
        <div class="panel-heading">
            <h3 class="panel-title"></h3>
            <p class="panel-subtitle">

            </p>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="metric" style="height: 99px">
                        <span class="icon"><i class="fas fa-broadcast-tower"></i></span>
                        <p>
                            <span class="number" style="margin-bottom: .5rem" id="total-bts">&nbsp;</span>
                            <span class="title" style="font-size: 1.4rem;">BTS</span>
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric" style="height: 99px">
                        <span class="icon"><i class="fas fa-file-alt"></i></span>
                        <p>
                            <span class="number" style="margin-bottom: .5rem" id="total-report">&nbsp;</span>
                            <span class="title" style="font-size: 1.4rem;">Total Report BTS</span>
                        </p>
                    </div>
                </div>
                <a href="https://www.multipirantijaya.com/" target="_blank">
                    <div class="col-md-4">
                        <div class="metric"
                            style="height: 99px; display: flex; align-items: center; justify-content: space-between">
                            <img src="{{ asset('assets/img/logo.png') }}" style="height: 100%;" alt="">
                            <p>
                                <span class="" style="margin-bottom: .5rem; font-weight: 100px; font-size: 2.2rem">PT.
                                    Multi Piranti Jaya
                                </span>
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            <br>
            <div class="row">
                <div class="col-md-7">
                    <div class="panel-heading">
                        <h3 class="panel-title">Grafik Report {{ date('Y') }}</h3>
                    </div>
                    <div class="panel-body" id="chart-content">
                        <canvas id="myChart" width="400" height="400"></canvas>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="panel-heading">
                        <h3 class="panel-title">Jumlah Report per-BTS {{ date('Y') }}</h3>
                    </div>
                    <div class="panel-body p-0">
                        <table class="stripe" id="datatable"></table>
                    </div>
                </div>
            </div>
            <br><br>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#loader').show();
            getData();

            function getData() {
                $.ajax({
                    url: "/dashboard/get",
                    method: "GET",
                    success: function(result) {
                        $('#total-bts').html(result.data.totalBts);
                        $('#total-report').html(result.data.totalReport);
                        initChart(result.data.chartData);
                        createDataTable(result.data.totalReportByBts);
                        $('#loader').hide();
                    },
                    error: function(error) {
                        $('#loader').hide();
                        console.error("Error fetching BTS data:", error);
                    }
                });
            }

            function createDataTable(data) {
                // DataTable initialization
                $('#datatable').DataTable({
                    data: data,
                    columns: [{
                            title: 'No',
                            data: null,
                            render: function(data, type, row, meta) {
                                // Mengembalikan nomor urut baris dalam tabel
                                return meta.row + 1;
                            }
                        },
                        {
                            title: 'BTS',
                            data: 'nama_bts'
                        },
                        {
                            title: 'total Report',
                            data: 'total'
                        }
                    ],
                    searching: false, // Menyembunyikan fitur pencarian
                    paging: false, // Menyembunyikan halaman navigasi
                    info: false
                });
            }

            function initChart(params) {
                var data = {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov',
                        'Desc'
                    ],
                    datasets: [{
                        label: 'Report BTS',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        data: params,
                    }]
                };

                var options = {
                    scales: {
                        y: {
                            ticks: {
                                precision: 0,
                                beginAtZero: true
                            }
                        }
                    }
                };

                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: data,
                    options: options
                });
            }

        });
    </script>
@endsection
