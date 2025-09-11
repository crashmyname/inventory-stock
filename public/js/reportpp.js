var table;
$(document).ready(function(){
    getReport();
    openScanner();
    $('.select2').select2({
        theme: 'bootstrap-5'
      });
      const columns= [
        {
            data: 'barang_id',
            name: 'barang_id',
            render: function(data, type, row, meta){
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
            data: 'code_barang',
            name: 'code_barang',
        },
        {
            data: 'nama_barang',
            name: 'nama_barang',
        },
        {
            data: 'spek',
            name: 'spek',
        },
        {
            data: 'satuan',
            name: 'satuan',
        },
        {
            data: 'factory',
            name: 'transactions.factory',
        },
        {
            data: 'stock',
            name: 'opening_stock.stock_opening',
            render:function(data,type,row){
                return '<span class="badge bg-secondary text-secondary-fg">'+data+'</span>';
            }
        },
        {
            data: 'qtyin',
            name: 'transactions.qtyin',
            searchable: false,
            render:function(data,type,row){
                return '<span class="badge bg-green text-green-fg">'+data+'</span>';
            }
        },
        {
            data: 'qtyout',
            name: 'transactions.qtyout',
            searchable: false,
            render:function(data,type,row){
                return '<span class="badge bg-red text-red-fg">'+data+'</span>';
            }
        },
        {
            data: 'balance',
            name: '',
            searchable: false,
            render:function(data,type,row){
                return '<span class="badge bg-primary text-primary-fg">'+(parseFloat(row.stock)+(parseFloat(row.qtyin)-parseFloat(row.qtyout)))+'</span>';
            }
        },
    ];
    table = $('#reportTable').DataTable({
        processing: true,
        serverSide: true,
        select: true,
        paging: true,
        ajax: {
            url : getReportPP,
            type : 'GET',
            data: function(d){
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
            },
        },
        error: function(xhr) {
            console.error('Error occurred:', xhr.statusText);
            setTimeout(function() {
                table.ajax.reload(null, false);
            }, 1000);
        },
        columns: columns,
        dom: `
            <"row mb-2"
                <"col-md-6 d-flex flex-column align-items-start"
                    <"dt-buttons mb-2"B>
                    <"d-flex align-items-center"l>
                >
                <"col-md-6 d-flex justify-content-end"f>
            >
            <"table-responsive"t>
            <"row mt-2"
                <"col-md-6"i>
                <"col-md-6 d-flex justify-content-end"p>
            >
        `,
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print', 'colvis'],
        initComplete: function() {
            this.api()
                .columns()
                .every(function() {
                    let column = this;
                    let title = column.footer().textContent;

                    // Create input element
                    let input = document.createElement('input');
                    input.placeholder = title;
                    input.classList.add('form-control')
                    column.footer().replaceChildren(input);

                    // Event listener for user input
                    input.addEventListener('keyup', () => {
                        if (column.search() !== this.value) {
                            column.search(input.value).draw();
                        }
                    });
                });
        },
    });

    // FLATPICKER
    flatpickr("#hidden_date", {
        dateFormat: "d F Y",
        locale: 'id', 
        allowInput: true,
        defaultDate: new Date(),
        onChange:function(selectedDates, dateStr, instance){
            var formatedDate = instance.formatDate(selectedDates[0],'Y-m-d');
            $('#tanggal').val(formatedDate);
        },
        onReady: function(selectedDates, dateStr, instance) {
            if (selectedDates.length > 0) {
                var formatedDate = instance.formatDate(selectedDates[0],'Y-m-d');
                $('#tanggal').val(formatedDate);
            }
        }
    });
    flatpickr("#shidden_date", {
        dateFormat: "d F Y",
        locale: 'id', 
        allowInput: true,
        defaultDate: new Date(),
        onChange:function(selectedDates, dateStr, instance){
            var formatedDate = instance.formatDate(selectedDates[0],'Y-m-d');
            $('#stanggal').val(formatedDate);
        },
        onReady: function(selectedDates, dateStr, instance) {
            if (selectedDates.length > 0) {
                var formatedDate = instance.formatDate(selectedDates[0],'Y-m-d');
                $('#stanggal').val(formatedDate);
            }
        }
    });
    const firstDayOfMonth = new Date();
    firstDayOfMonth.setDate(1);
    flatpickr("#hidden_start_date", {
        dateFormat: "d-m-Y",
        locale: 'id', 
        allowInput: true,
        defaultDate: firstDayOfMonth,
        onChange:function(selectedDates, dateStr, instance){
            var formatedDate = instance.formatDate(selectedDates[0],'Y-m-d');
            $('#start_date').val(formatedDate);
        },
        onReady: function(selectedDates, dateStr, instance) {
            if (selectedDates.length > 0) {
                var formatedDate = instance.formatDate(selectedDates[0],'Y-m-d');
                $('#start_date').val(formatedDate);
            }
        }
    });
    flatpickr("#hidden_end_date", {
        dateFormat: "d-m-Y",
        locale: 'id', 
        allowInput: true,
        defaultDate: new Date(),
        onChange:function(selectedDates, dateStr, instance){
            var formatedDate = instance.formatDate(selectedDates[0],'Y-m-d');
            $('#end_date').val(formatedDate);
        },
        onReady: function(selectedDates, dateStr, instance) {
            if (selectedDates.length > 0) {
                var formatedDate = instance.formatDate(selectedDates[0],'Y-m-d');
                $('#end_date').val(formatedDate);
            }
            table.ajax.reload();
        }
    });
})
function getReport(){
    $('#search').on('click',function(e){
        e.preventDefault();
        table.ajax.reload();
    })
    $('#detail').on('click',function(e){
        e.preventDefault();
        var selectedData = table.rows({
            selected: true
        }).data();
        startDate = $('#start_date').val();
        endDate = $('#end_date').val();
        barangID = selectedData[0].code_barang;
        var url = detailPP + '/' + barangID + '/' + startDate + '/' + endDate;
        window.open(url, '_blank');
    })
}
function Scanner(){
    codeReader.listVideoInputDevices().then((videoInputDevices) => {
            if (videoInputDevices.length > 1) {
                selectedDeviceId = videoInputDevices[1].deviceId;
            } else {
                selectedDeviceId = videoInputDevices[0].deviceId;
            }
            return codeReader.decodeFromVideoDevice(selectedDeviceId, 'video', (result, err) => {
                if (result) {
                    const code_barang = result.text;
                    document.getElementById('scode_barang').value = code_barang;
                    // document.getElementById('result').textContent = `Scanned: ${result.text}`;
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: getBarangs,
                        data: {
                            _token: csrfToken,
                            code_barang: code_barang,
                        },
                        success: function(response) {
                            console.log(response);
                            const resultElement = document.getElementById('result');
                            if (response.status == 200) {
                                document.getElementById('scode_barang').value = response.data.code_barang;
                                document.getElementById('snama_barang').value = response.data.nama_barang;
                                document.getElementById('sspek').value = response.data.spek;
                                document.getElementById('ssatuan').value = response.data.satuan;
                                document.getElementById('sstatus_stock').value = response.data.status_barang;
                            } else {
                                resultElement.classList.remove('alert-light-success',
                                    'color-success');
                                resultElement.classList.add('alert-light-danger',
                                    'color-danger');
                                resultElement.innerHTML =
                                    '<i class="bi bi-exclamation-circle"></i> Supplier Not Found: ';
                            }
                        }
                    });
                    codeReader.reset();
                }
                if (err && !(err instanceof ZXing.NotFoundException)) {
                    console.error(err);
                    document.getElementById('result').textContent = err;
                }
            });
        })
    .catch((err) => {
        console.error(err);
    });
}
function StopScanner(){
    codeReader.reset();
}
function resetScanner(){
    Scanner();
    $('#formaddscantransaction')[0].reset();
}
function openScanner(){
    $('#modal-scantransaction').on('shown.bs.modal', function() {
        Scanner();
    });

    $('#modal-scantransaction').on('hidden.bs.modal', function() {
        StopScanner();
        $('#formaddscantransaction')[0].reset();
    });

    $('#reset-button').on('click', function() {
        Scanner();
        $('#formaddscantransaction')[0].reset();
    });
}