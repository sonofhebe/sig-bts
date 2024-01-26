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
                    <h3 class="panel-title">Form Report BTS</h3>
                    <div class="right">
                        <a href="/report" class="btn btn-light-primary"><i class="far fa-arrow-left"></i>&nbsp; Back</a>
                    </div>
                </div>
                <div class="panel-body">
                    <form id="btsForm">
                        <input type="hidden" id="id" name="id">
                        <div class="form-group col-md-12">
                            <label for="nama_bts">BTS:</label><br>
                            <select class="col-12" aria-label="Select" id="nama_bts" name="nama_bts">
                                <option selected>Pilih BTS</option>
                                @foreach ($bts as $item)
                                    <option value="{{ $item->title }}">{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="tingkat_kepentingan">Tingkat Kepentingan:</label><br>
                            <select class="col-12" aria-label="Select" id="tingkat_kepentingan" name="tingkat_kepentingan">
                                <option selected>Pilih Tingkat Kepentingan</option>
                                <option value="Rendah">Rendah</option>
                                <option value="Standar">Standar</option>
                                <option value="Tinggi">Tinggi</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="kategori">Kategori:</label><br>
                            <select class="col-12" aria-label="Select" id="kategori" name="kategori">
                                <option selected>Pilih Kategory Report</option>
                                <option value="Masalah BTS">Masalah BTS</option>
                                <option value="Masalah Lingkungan">Masalah Lingkungan</option>
                                <option value="Masalah Lain">Masalah Lain</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="deskripsi">Deskripsi:</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
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
            $('#btsForm').submit(function(event) {
                $('#loader').show();
                event.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    url: '/report/store',
                    method: "POST",
                    data: formData,
                    success: function(result) {
                        // Redirect back to /bts after 2 seconds
                        toastr.success('Data store successfully!');
                        setTimeout(function() {
                            $('#loader').hide();
                            window.location.href = '/report';
                        }, 2000);
                    },
                    error: function(error) {
                        $('#loader').hide();
                        toastr.error('Data store failed!');
                    }
                });
            });

        });
    </script>
@endsection
