@extends('layouts.master')
@section('content')
    <style>
        /* Style the container holding the select */
        .select-container {
            position: relative;
            width: 200px;
            /* Adjust the width as needed */
        }

        /* Style the select itself */
        select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            appearance: none;
            /* Remove default styles in some browsers */
        }

        /* Style the arrow icon for the select */
        .select-container::after {
            content: '\25BC';
            /* Unicode character for a down arrow */
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            pointer-events: none;
            /* Make sure the arrow doesn't interfere with select events */
        }

        /* Style the options in the dropdown */
        select option {
            background-color: #fff;
            color: #333;
        }

        /* Style the selected option */
        select option:checked {
            background-color: #e0e0e0;
        }
    </style>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-headline">
                <div class="panel-heading">
                    <h3 class="panel-title">Download Report BTS</h3>
                    <div class="right">
                        {{-- <a href="/report" class="btn btn-light-primary"><i class="far fa-arrow-left"></i>&nbsp; Back</a> --}}
                    </div>
                </div>
                <div class="panel-body">
                    <form id="form">
                        <input type="hidden" id="id" name="id">
                        <div class="form-group col-md-6">
                            <label for="nama_bts">BTS:</label><br>
                            <select class="col-12" aria-label="Select" id="nama_bts" name="nama_bts">
                                <option value="SEMUA BTS" selected>SEMUA BTS</option>
                                @foreach ($bts as $item)
                                    <option value="{{ $item->title }}">{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="bulan">Bulan:</label><br>
                            <select id="bulan" name="bulan">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">
                                        {{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="tahun">Tahun:</label><br>
                            <select id="tahun" name="tahun">
                                @for ($i = date('Y'); $i >= 2000; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Download</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#form').submit(function(event) {
                $('#loader').show();
                event.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: "report/download",
                    method: "POST",
                    data: formData,
                    xhrFields: {
                        responseType: 'blob' // Mengatur tipe respons sebagai blob
                    },
                    success: function(result) {
                        // Membuat objek blob
                        var blob = new Blob([result], {
                            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        });
                        var url = window.URL.createObjectURL(blob);
                        var a = document.createElement('a');
                        a.href = url;
                        a.download = 'Report ' + $('#nama_bts').val() + ' ' + $('#bulan')
                        .val() + ' ' + $('#tahun').val() + ' - ' + Date.now() +
                            '.xlsx';
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        toastr.success('Data download successfully!');
                        setTimeout(function() {
                            $('#loader').hide();
                            window.location.href = '/download-report';
                        }, 2000);
                    },
                    error: function(error) {
                        $('#loader').hide();
                        toastr.error('Data download failed!');
                    }
                });
            });

        });
    </script>
@endsection
