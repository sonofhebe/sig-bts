@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-headline">
                <div class="panel-heading">
                    <h3 class="panel-title">Data Report BTS</h3>
                    <div class="right">
                        <a href="/report/form" class="btn btn-primary"><i class="far fa-plus"></i>&nbsp; Tambah data report
                            BTS</a>
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
                    url: "/report/get",
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
                            title: 'BTS',
                            data: 'nama_bts'
                        },
                        {
                            title: 'Tingkat Kepentingan',
                            data: 'tingkat_kepentingan'
                        },
                        {
                            title: 'Kategori Report',
                            data: 'kategori'
                        },
                        {
                            title: 'Deskripsi',
                            data: 'deskripsi'
                        },
                        {
                            title: 'Status',
                            data: 'status',
                            render: function(data, type, row) {
                                if (data == "terdaftar") {
                                    return '<span class="badge">Terdaftar</span>';
                                } else if (data == "proses") {
                                    return '<span class="badge" style="background-color:blue;">Proses</span>';
                                } else if (data == "selesai") {
                                    return '<span class="badge" style="background-color:green;">Selesai</span>';
                                } else if (data == "tunda") {
                                    return '<span class="badge" style="background-color:orange;">Tunda</span>';
                                } else if (data == "batal") {
                                    return '<span class="badge" style="background-color:red;">Batal</span>';
                                } else {
                                    return '-';
                                }
                            }
                        },

                        {
                            title: 'Catatan',
                            data: 'catatan'
                        },
                        {
                            title: 'Aksi',
                            data: null,
                            orderable: false,
                            render: function(data, type, row) {
                                return '<a class="btn btn-info btn-sm" href="/report/form?id=' +
                                    row.id +
                                    '">Detail</a>';
                                // return '<a class="btn btn-danger btn-sm delete-data" data-id="' +
                                //     row.id +
                                //     '" href="javascript:;">Delete</a>';
                            }
                        }
                    ]
                });
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
                    url: '/report/delete/' + btsId,
                    method: 'DELETE',
                    success: function(result) {
                        $('#loader').hide();
                        Swal.fire('Deleted!', 'Your record has been deleted.', 'success');
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    },
                    error: function(error) {
                        console.error('Error deleting Report record:', error);
                        Swal.fire('Error!', 'Failed to delete the record.', 'error');
                        $('#loader').hide();
                    }
                });
            }

        });
    </script>
@endsection
