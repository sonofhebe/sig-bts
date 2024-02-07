@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-headline">
                <div class="panel-heading">
                    <h3 class="panel-title">Data User</h3>
                    <div class="right">
                        <a href="/user/form" class="btn btn-primary"><i class="far fa-plus"></i>&nbsp; Tambah data User</a>
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
                    url: "/user/get",
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

            function createDataTable(data) {
                // DataTable initialization
                $('#datatable').DataTable({
                    data: data,
                    columns: [{
                            title: 'Name',
                            data: 'name'
                        },
                        {
                            title: 'Username/Email',
                            data: 'username'
                        },
                        {
                            title: 'Aksi',
                            data: null,
                            orderable: false,
                            render: function(data, type, row) {
                                return '<a class="btn btn-info btn-sm" href="/user/form?id=' +
                                    row.id +
                                    '">Edit</a><a class="btn btn-danger btn-sm delete-data" data-id="' +
                                    row.id +
                                    '" href="javascript:;">Delete</a>';
                            }
                        }
                    ]
                });
            }

            function createMap(mapId, latitude, longitude) {
                var map = L.map(mapId).setView([latitude, longitude], 14);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
                L.marker([latitude, longitude]).addTo(map);
                map.removeControl(map.zoomControl);
            }

            $('#datatable').on('click', '.delete-data', function() {
                var rowId = $(this).data('id');
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
                        deleteBTSRecord(rowId);
                    }
                });
            });

            function deleteBTSRecord(rowId) {
                $('#loader').show();
                $.ajax({
                    url: '/user/delete/' + rowId,
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
