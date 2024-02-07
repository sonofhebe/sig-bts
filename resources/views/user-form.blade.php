@extends('layouts.master')
@section('content')
    <style>
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 20px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 13px;
            width: 13px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(20px);
            -ms-transform: translateX(20px);
            transform: translateX(20px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .overlay {
            position: absolute;
            z-index: 2;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.2);
            /* Semi-transparent black */
            display: none;
            /* Initially hidden */
        }

        .show {
            display: block;
            cursor: no-drop;
            /* Show overlay */
        }
    </style>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-headline">
                <div class="panel-heading">
                    <h3 class="panel-title">Form Data User</h3>
                    <div class="right">
                        <a href="/user" class="btn btn-light-primary"><i class="far fa-arrow-left"></i>&nbsp; Back</a>
                    </div>
                </div>
                <div class="panel-body">
                    <form id="userForm">
                        <input type="hidden" id="id" name="id">
                        <div class="form-group col-md-6">
                            <label for="name">Nama:</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="username">Username/email:</label>
                            <input type="text" class="form-control" id="username" name="username">
                        </div>
                        <div class="form-group col-md-6">
                            <div style="display: flex; justify-content: space-between">
                                <label for="password">Password:</label>
                                <div id="change-password-toggle-group" style="display: none;">
                                    <label for="">Change Password</label>
                                    <label id="changePasswordLabel" class="switch" style="cursor: pointer; margin-left:5px">
                                        <input type="checkbox" id="passwordToggle">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="input-group" id="passwordInputGroup" style="display: flex; width: 100%">
                                <input type="password" class="form-control" id="password" name="password"
                                    autocomplete="new-password">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <div id="overlay" class="overlay"></div>
                            </div>
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
            var userId = urlParams.get('id');
            // Initially, password field is disabled
            var passwordField = $('#password');
            var isPasswordFieldEnabled = false;

            // Toggle password field and change label text on click
            $('#changePasswordLabel').on('click', function() {
                checkTogglePw();
            });

            function checkTogglePw() {
                var isChecked = $('#passwordToggle').prop('checked');
                $('#password').prop('disabled', !isChecked);
                if (isChecked) {
                    $('#overlay').removeClass('show');
                } else {
                    $('#overlay').addClass('show');
                }
            }

            // Toggle password visibility
            $('#togglePassword').on('click', function() {
                var passwordFieldType = $('#password').attr('type');
                if (passwordFieldType === 'password') {
                    $('#password').attr('type', 'text');
                    $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    $('#password').attr('type', 'password');
                    $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });

            // If 'id' parameter is present, fetch existing data for updating
            if (userId) {
                checkTogglePw();
                $('#change-password-toggle-group').show();
                $('#loader').show();
                // Make an AJAX request to fetch existing data
                $.ajax({
                    url: "/user/getById/" + userId,
                    method: "GET",
                    success: function(result) {
                        // Populate the form with existing data
                        $('#id').val(result.data.id);
                        $('#name').val(result.data.name);
                        $('#username').val(result.data.username);
                        $('#overlay').addClass('show');

                        $('#loader').hide();
                    },
                    error: function(error) {
                        $('#loader').hide();
                        console.error("Error fetching User data:", error);
                    }
                });
            } else {}

            $('#userForm').submit(function(event) {
                $('#loader').show();
                event.preventDefault();
                var formData = $(this).serialize();
                var url = userId ? "/user/update" : "/user/store";
                var type = userId ? "update" : "store";
                $.ajax({
                    url: url,
                    method: "POST",
                    data: formData,
                    success: function(result) {
                        // Redirect back to /user after 2 seconds
                        toastr.success('Data ' + type + ' successfully!');
                        setTimeout(function() {
                            $('#loader').hide();
                            window.location.href = '/user';
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
