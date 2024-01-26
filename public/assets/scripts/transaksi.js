
let produkOption = ``
function setProdukOption() {
    ajaxRequest.get({
        "url": "/produk/get"
    }).then(function(result){
        produkOption = `<option value=""></option>`
        $.each(result.data, function(i, v){
            produkOption = produkOption + `<option value="${v.id}" data-stok="${v.stok}">${v.nama}</option>`
        })
        $('.input-produk').empty()
        $('.input-produk').append(produkOption)
    })
}

setProdukOption()
selectProduk()

$('.transaksi-tambah-produk').on('click', function(){
    $('#transaksi-produk').append(`<div class="row" style="margin-bottom: 2rem">
                                        <div class="col-md-6">
                                            <p>Produk</p>
                                            <select class="form-control input-produk">
                                                <option value=""></option>
                                                ${produkOption}
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <p>Stok Tersisa</p>
                                            <input type="number" class="form-control sisa-stok" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <p>Jumlah</p>
                                            <input type="number" class="form-control input-jumlah">
                                        </div>
                                        <div class="col-md-1" style="padding-left: 5px">
                                            <p style="margin-bottom: 14px">&nbsp;</p>
                                            <button class="btn-table-action delete transaksi-delete-produk"><i class="fas fa-trash-alt"></i></button>
                                        </div>
                                    </div>`)
    selectProduk()
    btnDeleteProduk()
})

function btnDeleteProduk() {
    $('.transaksi-delete-produk').unbind('click')
    $('.transaksi-delete-produk').on('click', function(){
        $(this).parent().parent().remove()
        selectProduk()
        btnDeleteProduk()
    })
}

function selectProduk() {
    $('.input-produk').unbind('change')
    $('.input-produk').on('change', function(){
        let sisaStok = $(this).find(':selected').data('stok')
        $(this).closest('.row').children('.col-md-3').children('.sisa-stok').val(sisaStok)

        $(this).parent().parent().removeClass('invalid')
    })

    $('.input-jumlah').unbind('input')
    $('.input-jumlah').on('input', function(){
        $(this).parent().parent().removeClass('invalid')
    })
}

function getTransaksi() {
    let params = {
        "periode": $('#periode-transaksi').val()
    }
    ajaxRequest.post({
        "url": "/transaksi/get",
        "data": params
    }).then(function(result){
        if (result.response == "success") {
            if (result.data.length > 0) {
                $('#data-transaksi').html(`<button type="button" class="btn btn-primary admin-hide" data-toggle="modal" data-target="#modalInput"><i class="far fa-plus"></i>&nbsp; Input Transaksi</button>
                                            <br><br>
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Invoice</th>
                                                        <th>Tanggal Transaksi</th>
                                                        <th>Waktu Transaksi</th>
                                                        <th>Produk</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody-transaksi">
                                                    
                                                </tbody>
                                            </table>`)
                
    
                let tbody = ``
                $.each(result.data, function(i, v){
                    tbody = tbody + `<tr>
                                        <td>${v.invoice}</td>
                                        <td>${v.periode}</td>
                                        <td>${v.waktu}</td>
                                        <td>${v.produk}</td>
                                        <td><button class="btn-table-action detail detail-transaksi" data-id="${v.invoice}" data-toggle="modal" data-target="#modalDetail"><i class="fas fa-eye" style="font-size: 1.2rem"></i>&nbsp; Detail</button></td>
                                    </tr>`
                })
                $('#tbody-transaksi').html(tbody)
                detailTransaksi()
            }else{
                $('#data-transaksi').html(`<button type="button" class="btn btn-primary admin-hide" data-toggle="modal" data-target="#modalInput"><i class="far fa-plus"></i>&nbsp; Input Transaksi</button>
                                            <br><br>
                                            <div class="loader">
                                                <i class="fas fa-ban" style="font-size: 5rem; opacity: .5"></i>
                                                <h5 style="margin-top: 2.5rem; opacity: .75">Belum ada transaksi di tanggal ini</h5>
                                            </div>`)
            }
        }
    })
}

$('#search-transaksi').on('click', function(){
    if ($('#periode-transaksi').val().length == 0) {
        alert('Pilih periode transaksi')
    }else{
        $('#data-transaksi').html(`<br><br>
                                    <div class="loader">
                                        <div class="loader4"></div>
                                        <h5 style="margin-top: 2.5rem">Loading data</h5>
                                    </div>`)

        getTransaksi()
    }
})

$('#search-transaksi').click()

$('#btn-input-data').on('click', function(){
    let produk = []
    let valid = true

    $.each($('.input-produk'), function(i, v){
        let thisProduk = $(this)
        if (thisProduk.val().length == 0  || $('.input-jumlah').eq(i).val().length == 0) {
            valid = false
            thisProduk.parent().parent().addClass('invalid')
        }else{
            $.each(produk, function(pIndex, pVal){
                if (pVal.produk == thisProduk.val()) {
                    valid = false
                    thisProduk.parent().parent().addClass('invalid')
                    alert('Duplikat input produk')
                }
            })
            produk.push({
                "produk": $(this).val(),
                "jumlah": $('.input-jumlah').eq(i).val()
            })
        }
    })

    if (valid == true) {
        $('#btn-input-data').attr('disabled', true)

        let params = {
            "periode": $('#periode-transaksi').val(),
            "produk": produk
        }

        ajaxRequest.post({
            "url": "/transaksi/input",
            "data": params
        }).then(function(result){
            $('#btn-input-data').removeAttr('disabled')
            if (result.response == "success") {
                getTransaksi()
                setProdukOption()
                $('#modalInput').modal('hide')
                $('#transaksi-produk').html(`<div class="row" style="margin-bottom: 2rem">
                                                <div class="col-md-6">
                                                    <p>Produk</p>
                                                    <select class="form-control input-produk">
                                                        <option value=""></option>
                                                        ${produkOption}
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <p>Stok Tersisa</p>
                                                    <input type="number" class="form-control sisa-stok" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <p>Jumlah</p>
                                                    <input type="number" class="form-control input-jumlah">
                                                </div>
                                                <div class="col-md-1" style="padding-left: 5px">
                                                    <p style="margin-bottom: 14px">&nbsp;</p>
                                                </div>
                                            </div>`)

                selectProduk()
                toastr.option = {
                    "timeout": "5000"
                }
                toastr["success"](result.message)
            }else if (result.response == "failed") {
                toastr.option = {
                    "timeout": "5000"
                }
                toastr["error"](result.message)
            }
        })
    }
})

$('#modalInput').on('hide.bs.modal', function(){
    setProdukOption()
    $('#transaksi-produk').html(`<div class="row" style="margin-bottom: 2rem">
                                                <div class="col-md-6">
                                                    <p>Produk</p>
                                                    <select class="form-control input-produk">
                                                        <option value=""></option>
                                                        ${produkOption}
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <p>Stok Tersisa</p>
                                                    <input type="number" class="form-control sisa-stok" readonly>
                                                </div>
                                                <div class="col-md-2">
                                                    <p>Jumlah</p>
                                                    <input type="number" class="form-control input-jumlah">
                                                </div>
                                                <div class="col-md-1" style="padding-left: 5px">
                                                    <p style="margin-bottom: 14px">&nbsp;</p>
                                                </div>
                                            </div>`)
    selectProduk()
})

function detailTransaksi() {
    $('.detail-transaksi').unbind('click')
    $('.detail-transaksi').on('click', function(){
        let params = {
            "id": $(this).data('id')
        }

        $('#modal-body-detail-transaksi').html(`<div class="loader">
                                                    <div class="loader4"></div>
                                                    <h5 style="margin-top: 2.5rem">Loading data</h5>
                                                </div>`)
    
        ajaxRequest.post({
            "url": "/transaksi/detail",
            "data": params
        }).then(function(result){
            let tproduk = ``
            $.each(result.produk, function(i, v){
                tproduk = tproduk + `<tr>
                                        <td>${v.produk}</td>
                                        <td>${v.harga}</td>
                                        <td>${v.jumlah}</td>
                                        <td>${v.total}</td>
                                    </tr>`
            })

            $('#modal-body-detail-transaksi').html(`<p>Invoice <span style="float: right">${result.invoice}</span></p>
                                                    <p>Tanggal <span style="float: right">${result.tanggal}</span></p>
                                                    <p>Waktu <span style="float: right">${result.waktu}</span></p>
                                                    <br>
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Produk</th>
                                                                <th>Harga</th>
                                                                <th>Jumlah</th>
                                                                <th>Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="detail-transaksi-body">
                                                            ${tproduk}
                                                        </tbody>
                                                        <thead>
                                                            <tr>
                                                                <th>Total Belanja</th>
                                                                <th></th>
                                                                <th></th>
                                                                <th>${result.total}</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                    <br>`)
        })
    })
}