@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-headline">
                <div class="panel-heading">
                    <h3 class="panel-title">Data BTS</h3>
                    <div class="right">
                        <a href="/bts/form" class="btn btn-primary"><i class="far fa-plus"></i>&nbsp; Tambah data BTS</a>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="stripe" id="datatable"></table>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Include DataTables CSS and JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    <!-- Include SweetAlert CSS and JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        $(document).ready(function() {
            getBts();

            function getBts() {
                $('#loader').show();
                $.ajax({
                    url: "/bts/get",
                    method: "GET",
                    success: function(result) {
                        $('#loader').hide();
                        createDataTable(result.data);
                    },
                    error: function(error) {
                        $('#loader').hide();
                        console.error("Error fetching BTS data:", error);
                    }
                });
            }

            var dataTable = null;

            function createDataTable(data) {
                // DataTable initialization
                dataTable = $('#datatable').DataTable({
                    data: data,
                    columns: [{
                            title: 'Map',
                            data: null,
                            orderable: false,
                            render: function(data, type, row) {
                                // Embed the map using Leaflet
                                var mapId = 'map_' + row.id;
                                var mapDiv = '<div id="' + mapId +
                                    '" style="height: 100px; width: 100%;"></div>';
                                var openMapLink = '<a href="https://www.google.com/maps?q=' + row
                                    .latitude + ',' + row.longitude +
                                    '" target="_blank">Open google map</a>';
                                return mapDiv + openMapLink;
                            }
                        },
                        {
                            title: 'BTS',
                            data: 'title'
                        },
                        {
                            title: 'Alamat',
                            data: 'alamat'
                        },
                        {
                            title: 'Aksi',
                            data: null,
                            orderable: false,
                            render: function(data, type, row) {
                                return '<a class="btn btn-info btn-sm" href="/bts/form?id=' +
                                    row.id +
                                    '">Detail</a>\
                                        <a class="btn btn-danger btn-sm delete-data" data-id="' +
                                    row.id +
                                    '" href="javascript:;">Delete</a>';
                            }
                        }
                    ]
                });

                // Event listener for table draw event
                dataTable.on('draw', function() {
                    // Get the currently visible rows
                    var visibleRows = dataTable.rows({
                        page: 'current'
                    }).data();

                    // Render maps for the currently visible rows
                    visibleRows.each(function(row) {
                        var mapId = 'map_' + row.id;
                        createMap(mapId, row.latitude, row.longitude);
                    });
                });

                // Initial map population
                data.forEach(function(row) {
                    var mapId = 'map_' + row.id;
                    createMap(mapId, row.latitude, row.longitude);
                });
            }

            function createMap(mapId, latitude, longitude) {
                var map = L.map(mapId).setView([latitude, longitude], 14);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                L.marker([latitude, longitude]).addTo(map);
                map.removeControl(map.zoomControl);
            }

            $('#datatable').on('click', '.delete-data', function() {
                var btsId = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You won\'t be able to revert this!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteBTSRecord(btsId);
                    }
                });
            });

            function deleteBTSRecord(btsId) {
                $('#loader').show();
                $.ajax({
                    url: '/bts/delete/' + btsId,
                    method: 'DELETE',
                    success: function(result) {
                        $('#loader').hide();
                        Swal.fire('Deleted!', 'Your record has been deleted.', 'success');
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    },
                    error: function(error) {
                        console.error('Error deleting BTS record:', error);
                        Swal.fire('Error!', 'Failed to delete the record.', 'error');
                        $('#loader').hide();
                    }
                });
            }
        });
    </script>
@endsection
