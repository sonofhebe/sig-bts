$('.min').on('input', function(){
    if ($(this).val() < 0.1) {
        $(this).val(0.1)
    }

    if ($(this).val() > 1) {
        $(this).val(1)
    }
})

$('#btn-asosiasi-data').on('click', function(){
    if ($('#periode').val().length == 0) {
        alert('Pilih tanggal transaksi')
    }else if ($('#min-support').val().length == 0) {
        alert('Masukkan min support')
    }else if ($('#min-confidence').val().length == 0) {
        alert('Masukkan min confidence')
    }else{
        $('#btn-asosiasi-data').attr('disabled', true)

        $('#hasil-asosiasi').show()
        $('#hasil-asosiasi').html(`<div class="panel panel-headline">
                                        <div class="loader">
                                            <div class="loader4"></div>
                                            <h5 style="margin-top: 2.5rem">Membuat asosiasi</h5>
                                        </div>
                                    </div>`)

        let params = {
            "periode": $('#periode').val(),
            "minSupport": parseFloat($('#min-support').val()),
            "minConfidence": parseFloat($('#min-confidence').val())
        }

        ajaxRequest.post({
            "url": "/asosiasi/start",
            "data": params
        }).then(function(result){
            console.log(result);
            $('#btn-asosiasi-data').removeAttr('disabled')
            if (result.response == "success") {
                let data = result.data

                $('#hasil-asosiasi').html(`<div class="panel panel-headline">
                                                <div id="itemset"></div>
                                                <div id="rule"></div>
                                                <div id="final"></div>
                                                <br><br>
                                            </div>`)

                if (data.itemset1.length > 0) {
                    $('#itemset').append(itemset1Component)
                    let tbody = ``
                    let no = 1
                    $.each(data.itemset1, function(i, v){
                        tbody = tbody + `<tr>
                                            <td>${no}</td>
                                            <td>${v.produk}</td>
                                            <td>${v.transaksi}</td>
                                            <td>${v.support}</td>
                                        </tr>`
                        no = no + 1
                    })
                    $('#itemset1-body').html(tbody)
                }

                if (data.itemset2.length > 0) {
                    $('#itemset').append(itemset2Component)
                    let tbody = ``
                    let no = 1
                    $.each(data.itemset2, function(i, v){
                        tbody = tbody + `<tr>
                                            <td>${no}</td>
                                            <td>${v.produk}</td>
                                            <td>${v.transaksi}</td>
                                            <td>${v.support}</td>
                                        </tr>`
                        no = no + 1
                    })
                    $('#itemset2-body').html(tbody)
                }

                if (data.itemset3.length > 0) {
                    $('#itemset').append(itemset3Component)
                    let tbody = ``
                    let no = 1
                    $.each(data.itemset3, function(i, v){
                        tbody = tbody + `<tr>
                                            <td>${no}</td>
                                            <td>${v.produk}</td>
                                            <td>${v.transaksi}</td>
                                            <td>${v.support}</td>
                                        </tr>`
                        no = no + 1
                    })
                    $('#itemset3-body').html(tbody)
                }

                if (data.itemset4.length > 0) {
                    $('#itemset').append(itemset4Component)
                    let tbody = ``
                    let no = 1
                    $.each(data.itemset4, function(i, v){
                        tbody = tbody + `<tr>
                                            <td>${no}</td>
                                            <td>${v.produk}</td>
                                            <td>${v.transaksi}</td>
                                            <td>${v.support}</td>
                                        </tr>`
                        no = no + 1
                    })
                    $('#itemset4-body').html(tbody)
                }

                if (data.rule2itemset.length > 0) {
                    $('#rule').append(rule2Component)
                    let tbody = ``
                    let no = 1
                    $.each(data.rule2itemset, function(i, v){
                        tbody = tbody + `<tr>
                                            <td>${no}</td>
                                            <td>${v.rule}</td>
                                            <td>${v.ab}</td>
                                            <td>${v.a}</td>
                                            <td>${v.confidence}</td>
                                        </tr>`
                        no = no + 1
                    })
                    $('#rule2-body').html(tbody)
                }

                if (data.rule3itemset.length > 0) {
                    $('#rule').append(rule3Component)
                    let tbody = ``
                    let no = 1
                    $.each(data.rule3itemset, function(i, v){
                        tbody = tbody + `<tr>
                                            <td>${no}</td>
                                            <td>${v.rule}</td>
                                            <td>${v.ab}</td>
                                            <td>${v.a}</td>
                                            <td>${v.confidence}</td>
                                        </tr>`
                        no = no + 1
                    })
                    $('#rule3-body').html(tbody)
                }

                if (data.rule4itemset.length > 0) {
                    $('#rule').append(rule4Component)
                    let tbody = ``
                    let no = 1
                    $.each(data.rule4itemset, function(i, v){
                        tbody = tbody + `<tr>
                                            <td>${no}</td>
                                            <td>${v.rule}</td>
                                            <td>${v.ab}</td>
                                            <td>${v.a}</td>
                                            <td>${v.confidence}</td>
                                        </tr>`
                        no = no + 1
                    })
                    $('#rule4-body').html(tbody)
                }

                $('#final').html(finalComponent())
                if (data.assosiasi.length > 0) {
                    let finalRule = ``
                    let no = 1
                    $.each(data.assosiasi, function(i, v){
                        finalRule = finalRule + `<tr>
                                            <td>${no}</td>
                                            <td>${v.rule}</td>
                                            <td>${v.support}</td>
                                            <td>${v.confidence}</td>
                                            <td>${v.result}</td>
                                        </tr>`
                        no = no + 1
                    })
                    $('#final-rule').html(finalRule)
                }else{
                    $('#asosiasi-final table').remove()
                    $('#asosiasi-final').append(`<p><i class="fas fa-ban"></i> &nbsp; Tidak ada aturan yang terpilih</p>`)
                }

                let finalResult = `<p>Asosiasi final yang terpilih adalah yang memiliki nilai support x confidence &ge; ${$('#min-confidence').val()}</p>`
                if (data.final.length > 0) {
                    $.each(data.final, function(i, v){
                        finalResult = finalResult + `<p><i class="fas fa-chevron-right"></i> &nbsp; ${v}</p>`
                    })
                }else{
                    finalResult = finalResult + `<p><i class="fas fa-ban"></i> &nbsp; Tidak ada asosiasi final yang terpilih</p>`
                }
                $('#final-result').html(finalResult)
            }else if (result.response == "failed") {
                $('#hasil-asosiasi').html(`<div class="panel panel-headline">
                                                <div class="loader">
                                                    <i class="fas fa-ban" style="font-size: 5rem; opacity: .5"></i>
                                                    <h5 style="margin-top: 2.5rem; opacity: .75">${result.message}</h5>
                                                </div>
                                            </div>`)
            }
        })
    }
})