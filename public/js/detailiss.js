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
            data: 'quantity',
            name: 'quantity',
            render:function(data,type,row){
                return '<center><span class="badge bg-red text-red-fg">'+data+'</span></center>';
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
        ajax: getReportISS + '/' + idB + '/' + startdate + '/' + enddate,
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
    });
});