$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
})

class requestData {
    post(params){
        let url = params.url
        let data = params.data

        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'POST',
                url: url,
                dataType: "json",
                contentType: 'application/json',
                data: JSON.stringify(data),
                success:function(result){
                    resolve(result)
                },
                error:function(result){
                    alert('Oops! Something went wrong ..')
                }
            })
        })
    }

    get(params){
        let url = params.url

        return new Promise((resolve, reject) => {
            $.ajax({
                type: 'GET',
                url: url,
                dataType: "json",
                contentType: 'application/json',
                success:function(result){
                    resolve(result)
                },
                error:function(result){
                    alert('Oops! Something went wrong ..')
                }
            })
        })
    }
}

const ajaxRequest = new requestData()


$('.date-picker').datetimepicker({
    timepicker: false,
    format: 'Y-m-d'
})

$('.date-picker.today').datetimepicker({
    timepicker: false,
    minDate: 'today',
    format: 'Y-m-d'
})

$('.time-picker').datetimepicker({
    datepicker: false,
    timepicker: true,
    format: 'H:i'
})

$('.month-picker').datepicker({
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    dateFormat: 'MM yy',
    onClose: function (dateText, inst) {
        $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
    }
})

$('.input-number').on('keypress', function(e){
    let charCode = (e.which) ? e.which : e.keyCode;
    if(charCode > 31 && (charCode < 48 || charCode > 57)){
        return false;
    }
    return true;
})

