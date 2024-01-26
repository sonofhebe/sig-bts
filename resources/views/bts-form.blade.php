@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-headline">
                <div class="panel-heading">
                    <h3 class="panel-title">Form Data BTS</h3>
                    <div class="right">
                        <a href="/bts" class="btn btn-light-primary"><i class="far fa-arrow-left"></i>&nbsp; Back</a>
                    </div>
                </div>
                <div class="panel-body">
                    <form id="btsForm">
                        <div id="map" style="height: 300px; display: none;" class="col-12"></div>
                        <input type="hidden" id="id" name="id">
                        <div class="form-group col-md-6">
                            <label for="title">Title:</label>
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="alamat">Alamat:</label>
                            <textarea class="form-control" id="alamat" name="alamat"></textarea>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="latitude">Latitude:</label>
                            <input type="text" class="form-control" id="latitude" name="latitude">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="longitude">Longitude:</label>
                            <input type="text" class="form-control" id="longitude" name="longitude">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="jumlah_antena">Jumlah Antena:</label>
                            <input type="number" class="form-control" id="jumlah_antena" name="jumlah_antena">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="frekuensi">Frekuensi:</label>
                            <input type="text" class="form-control" id="frekuensi" name="frekuensi">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="teknologi_jaringan">Teknologi Jaringan:</label>
                            <input type="text" class="form-control" id="teknologi_jaringan" name="teknologi_jaringan">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="luas_jaringan">Luas Jaringan:</label>
                            <input type="text" class="form-control" id="luas_jaringan" name="luas_jaringan">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="kapasitas_user">Kapasitas User:</label>
                            <input type="number" class="form-control" id="kapasitas_user" name="kapasitas_user">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="informasi_lain">Informasi Lain:</label>
                            <textarea class="form-control" id="informasi_lain" name="informasi_lain"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Save</button>
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
            // Check if URL contains 'id' parameter
            var urlParams = new URLSearchParams(window.location.search);
            var btsId = urlParams.get('id');

            // If 'id' parameter is present, fetch existing data for updating
            if (btsId) {
                $('#loader').show();
                // Make an AJAX request to fetch existing data
                $.ajax({
                    url: "/bts/getById/" + btsId,
                    method: "GET",
                    success: function(result) {
                        // Populate the form with existing data
                        $('#id').val(result.data.id);
                        $('#title').val(result.data.title);
                        $('#alamat').val(result.data.alamat);
                        $('#longitude').val(result.data.longitude);
                        $('#latitude').val(result.data.latitude);
                        $('#jumlah_antena').val(result.data.jumlah_antena);
                        $('#frekuensi').val(result.data.frekuensi);
                        $('#teknologi_jaringan').val(result.data.teknologi_jaringan);
                        $('#luas_jaringan').val(result.data.luas_jaringan);
                        $('#kapasitas_user').val(result.data.kapasitas_user);
                        $('#informasi_lain').val(result.data.informasi_lain);

                        //init map
                        $('#map').show();
                        initMap(result.data.latitude, result.data.longitude);

                        $('#loader').hide();
                    },
                    error: function(error) {
                        $('#loader').hide();
                        console.error("Error fetching BTS data:", error);
                    }
                });
            }

            function initMap(latitude, longitude) {
                var map = L.map('map').setView([latitude, longitude], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                L.marker([latitude, longitude]).addTo(map);
            }

            $('#btsForm').submit(function(event) {
                $('#loader').show();
                event.preventDefault();
                var formData = $(this).serialize();

                // Determine whether to create or update based on 'id' parameter
                var url = btsId ? "/bts/update" : "/bts/store";
                var type = btsId ? "update" : "store";

                // Make an AJAX request for either creation or update
                $.ajax({
                    url: url,
                    method: "POST",
                    data: formData,
                    success: function(result) {
                        // Redirect back to /bts after 2 seconds
                        toastr.success('Data ' + type + ' successfully!');
                        setTimeout(function() {
                            $('#loader').hide();
                            window.location.href = '/bts';
                        }, 2000);
                    },
                    error: function(error) {
                        $('#loader').hide();
                        toastr.error('Data ' + type + ' failed!');
                    }
                });
            });

        });
    </script>
@endsection
