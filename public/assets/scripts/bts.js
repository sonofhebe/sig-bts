getProduk()

function getProduk() {
    ajaxRequest.get({
        "url": "/bts/get",
    }).then(function(result){
        if (result.data.length > 0) {
            $('#loader').hide()
            produkComponent(result.data)
        }else{
            $('#loader h5').html('Belum ada produk yang terdaftar')
            $('#loader').show()
        }
    })
}

function produkComponent(data) {
    let produkData = ``
    $.each(data, function(i, v){
        produkData = produkData + `<div class="item-wrapper">
                                        <div class="item-image" style="background-image: url('${v.gambar}')"></div>
                                        <div class="item-detail">
                                            <h4 class="item-name" title="${v.nama}">${v.nama}</h4>
                                            <div class="item-left">
                                                <p>Rp ${v.harga}</p>
                                                <p>Stok ${v.stok}</p>
                                            </div>
                                            <button class="btn-delete-item kasir-hide"
                                                data-id="${v.id}"
                                                data-nama="${v.nama}"><i class="fal fa-trash"></i>
                                            </button>
                                            <button class="btn-edit-item kasir-hide"
                                                data-id="${v.id}"
                                                data-img="${v.gambar}"
                                                data-nama="${v.nama}"
                                                data-harga="${v.harga}"
                                                data-stok="${v.stok}"><i class="fal fa-pen"></i>
                                            </button>
                                        </div>
                                    </div>`
    })
    $('#produk-data').html(produkData)
    editItem()
    deleteItem()
}

let searchRequest
let searchLength = 0
$('#search-produk').on('input', function(){
    if ($(this).val().length < searchLength) {
        searchLength = $(this).val().length
        if ($(this).val().length == 0) {
            getProduk()
        }
    }else{
        searchLength = $(this).val().length
        if (searchRequest != null) {
            searchRequest.abort()
            searchRequest = null
        }

        searchRequest = $.ajax({
            type: "post",
            url: "/produk/search",
            data: {
                "search": $('#search-produk').val()
            },
            success:function(result){
                searchRequest = null
                if (result.data.length > 0) {
                    $('#loader').hide()
                    produkComponent(result.data)
                }else{
                    $('#produk-data').empty()
                    $('#loader h5').html('Produk tidak ditemukan')
                    $('#loader').show()
                }
            }
        })
    }
})

$('.choose-img-btn').on('click', function(){
    $(this).parent().children('.choose-img-file').click()
})

$('.choose-img-file').on('change', function(){
    let parent = $(this).parent()
    let fileObj = this.files[0]
    let fileReader = new FileReader()
    fileReader.readAsDataURL(fileObj)
    fileReader.onload = function(){
        let result = fileReader.result
        parent.children('.img-preview').css('background-image', `url('${result}')`)
    }
})

$('#btn-input-data').on('click', function(){
    if ($('#input-gambar').val().length == 0) {
        alert('Pilih gambar')
    }else if ($('#input-nama').val().length == 0) {
        alert('Masukkan nama produk')
    }else if ($('#input-harga').val().length == 0) {
        alert('Masukkan harga produk')
    }else if ($('#input-stok').val().length == 0) {
        alert('Masukkan stok produk')
    }else{
        let reader = new FileReader()
        reader.readAsDataURL($('#input-gambar')[0].files[0])
        reader.onload = function(){
            let gambar = reader.result
            let params = {
                "nama": $('#input-nama').val(),
                "harga": $('#input-harga').val(),
                "stok": $('#input-stok').val(),
                "gambar": gambar
            }

            ajaxRequest.post({
                "url": "/produk/input",
                "data": params
            }).then(function(result){
                $('#modalInput').modal('hide')
                $('.input-gambar .img-preview').removeAttr('style')
                $('#input-gambar').val('')
                $('#input-nama').val('')
                $('#input-harga').val('')
                $('#input-stok').val('')
                toastr.option = {
                    "timeout": "5000"
                }
                toastr["success"](result.message)
                getProduk()
            })
        }
    }
})

function editItem() {
    $('.btn-edit-item').unbind('click')
    $('.btn-edit-item').on('click', function(){
        let id = $(this).data('id')
        let gambar = $(this).data('img')
        let nama = $(this).data('nama')
        let harga = $(this).data('harga').replaceAll(',', '')
        let stok = $(this).data('stok')

        $('#update-id').val(id)
        $('#update-img-preview').css('background-image', `url('${gambar}')`)
        $('#update-nama').val(nama)
        $('#update-harga').val(harga)
        $('#update-stok').val(stok)

        $('#modalEditData').modal('show')
    })
}

$('#btn-edit-data').on('click', function(){
    if ($('#update-nama').val().length == 0) {
        alert('Masukkan nama produk')
    }else if ($('#update-harga').val().length == 0) {
        alert('Masukkan harga produk')
    }else if ($('#update-stok').val().length == 0) {
        alert('Masukkan stok produk')
    }else{
        if ($('#update-gambar').val().length == 0) {
            let params = {
                "id": $('#update-id').val(),
                "nama": $('#update-nama').val(),
                "harga": $('#update-harga').val(),
                "stok": $('#update-stok').val(),
                "gambar": null
            }
            updateProduk(params)
        }else{
            let reader = new FileReader()
            reader.readAsDataURL($('#update-gambar')[0].files[0])
            reader.onload = function(){
                let gambar = reader.result
                let params = {
                    "id": $('#update-id').val(),
                    "nama": $('#update-nama').val(),
                    "harga": $('#update-harga').val(),
                    "stok": $('#update-stok').val(),
                    "gambar": gambar
                }

                updateProduk(params)
            }
        }
    }
})

function updateProduk(params) {
    ajaxRequest.post({
        "url": "/produk/update",
        "data": params
    }).then(function(result){
        $('#modalEditData').modal('hide')
        $('#update-gambar').val('')
        toastr.option = {
            "timeout": "5000"
        }
        toastr["success"](result.message)
        getProduk()
    })
}

$('#modalEditData').on('hide.bs.modal', function(){
    $('#update-gambar').val('')
})

function deleteItem() {
    $('.btn-delete-item').unbind('click')
    $('.btn-delete-item').on('click', function(){
        $('#delete-warning-message').html(`Hapus ${$(this).data('nama')} ?`)
        $('#delete-id').val($(this).data('id'))
        $('#modalDeleteData').modal('show')
    })
}

$('#btn-delete-data').on('click', function(){
    let params = {
        "id": $('#delete-id').val()
    }

    ajaxRequest.post({
        "url": "/produk/delete",
        "data": params
    }).then(function(result){
        $('#modalDeleteData').modal('hide')
        toastr.option = {
            "timeout": "5000"
        }
        toastr["success"](result.message)
        getProduk()
    })
})
