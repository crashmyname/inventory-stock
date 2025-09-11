var table;
$(document).ready(function(){
    $('.select2').select2({
        theme: 'bootstrap-5'
      });
    const columns= [
        {
            data: 'transaction_id',
            name: 'transaction_id',
            render: function(data, type, row, meta){
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
            data: 'tanggal',
            name: 'tanggal',
            render: function(data,type,row){
                return moment(data).format('DD-MM-YYYY');
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
            name: 'factory',
        },
        {
            data: 'stock',
            name: 'stock',
            render:function(data,type,row,meta){
                if (meta.row === 0) {
                    return '<center><span class="badge bg-primary text-primary-fg">' + data + '</span></center>';
                } else {
                    return '';  // Untuk baris berikutnya, jangan tampilkan nilai stock
                }
            }
        },
        {
            data: 'in',
            name: 'transactions.qtyin',
            render:function(data,type,row){
                return '<center><span class="badge bg-green text-green-fg">'+row.qtyin+'</span></center>';
            }
        },
        {
            data: 'out',
            name: 'transactions.qtyout',
            render:function(data,type,row){
                return '<center><span class="badge bg-red text-red-fg">'+row.qtyout+'</span></center>';
            }
        },
        {
            data: 'balance',
            name: '',
            render:function(data,type,row){
                return '';
            }
        },
    ];
    var idB = $('#id').val();
    var startdate = $('#startdate').val();
    var enddate = $('#enddate').val();
    table = $('#reportTable').DataTable({
        processing: true,
        serverSide: true,
        select: true,
        paging: true,
        ajax: getReportPP + '/' + idB + '/' + startdate + '/' + enddate,
        error: function(xhr) {
            console.error('Error occurred:', xhr.statusText);
            setTimeout(function() {
                table.ajax.reload(null, false);
            }, 1000);
        },
        columns: columns,
        "drawCallback": function(settings) {
                var api = this.api();
                
                // Inisialisasi variabel untuk total per kolom
                var totalStock = 0;
                var totalIn = 0;
                var totalOut = 0;
                var totalBalance = 0;
                
                // Loop melalui setiap baris dan kalkulasi total
                api.rows().every(function() {
                    var data = this.data();
                    
                    totalStock = parseFloat(data.stock) || 0;
                    totalIn += parseFloat(data.qtyin) || 0;
                    totalOut += parseFloat(data.qtyout) || 0;
                    totalBalance = totalStock + totalIn - totalOut;
                });

                // Update footer dengan total per kolom
                $(api.column(7).footer()).html('<center><span class="badge bg-primary text-primary-fg">' + totalStock + '</span></center>');
                $(api.column(8).footer()).html('<center><span class="badge bg-green text-green-fg">' + totalIn + '</span></center>');
                $(api.column(9).footer()).html('<center><span class="badge bg-red text-red-fg">' + totalOut + '</span></center>');
                $(api.column(10).footer()).html('<center><span class="badge bg-secondary text-secondary-fg">' + totalBalance + '</span></center>');
            },
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
    });
});