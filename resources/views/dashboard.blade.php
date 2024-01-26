@extends('layouts.master')
@section('content')
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
                    <span class="icon"><i class="fas fa-boxes"></i></span>
                    <p>
                        <span class="number" style="margin-bottom: .5rem" id="total-produk">&nbsp;</span>
                        <span class="title" style="font-size: 1.4rem;">Produk</span>
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric" style="height: 99px">
                    <span class="icon"><i class="fas fa-sack-dollar"></i></span>
                    <p>
                        <span class="number" style="margin-bottom: .5rem" id="total-terjual">&nbsp;</span>
                        <span class="title" style="font-size: 1.4rem;">Total Produk Terjual</span>
                    </p>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-7">
                <div class="panel-heading">
                    <h3 class="panel-title">Grafik Produk</h3>
                </div>
                <div class="panel-body" id="chart-content">
                    <div class="loader">
                        <div class="loader4"></div>
                        <h5 style="margin-top: 2.5rem">Loading data</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="panel-heading">
                    <h3 class="panel-title">Produk Terlaris</h3>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Produk</th>
                                <th>Terjual</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-produk-terlaris">
                            <tr>
                                <td><p class="loading">1</p></td>
                                <td><p class="loading">Lorem, ipsum.</p></td>
                                <td><p class="loading">1234</p></td>
                            </tr>
                            <tr>
                                <td><p class="loading">1</p></td>
                                <td><p class="loading">Lorem, ipsum.</p></td>
                                <td><p class="loading">1234</p></td>
                            </tr>
                            <tr>
                                <td><p class="loading">1</p></td>
                                <td><p class="loading">Lorem, ipsum.</p></td>
                                <td><p class="loading">1234</p></td>
                            </tr>
                            <tr>
                                <td><p class="loading">1</p></td>
                                <td><p class="loading">Lorem, ipsum.</p></td>
                                <td><p class="loading">1234</p></td>
                            </tr>
                            <tr>
                                <td><p class="loading">1</p></td>
                                <td><p class="loading">Lorem, ipsum.</p></td>
                                <td><p class="loading">1234</p></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <br><br>
        
    </div>
</div>
@endsection