var table;
$(document).ready(function(){
    crudTransaction();
    getBarang();
    getApi();
    openScanner();
    Others();
    const columns= [
        {
            data: 'transaction_id',
            name: 'transaction_id',
            render: function(data, type, row, meta){
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
            data: 'nama_barang',
            name: 'barang.nama_barang',
        },
        {
            data: 'nik',
            name: 'nik',
        },
        {
            data: 'namapic',
            name: 'namapic',
        },
        {
            data: 'section',
            name: 'section',
        },
    ];
    if(userRole == 'Admin LA' || userRole == 'Administrator'){
        columns.push({
            data: 'no_lane',
            name: 'no_lane',
        });
    }
    if(userRole == 'Admin MDF' || userRole == 'Administrator'){
        columns.push({
            data: 'supplier_name',
            name: 'supplier_name'
        });
    }
    columns.push(
        {
            data: 'quantity',
            name: 'quantity',
        },
        {
            data: 'tanggal',
            name: 'tanggal',
            render: function(data,type,row){
                return moment(data).format('DD-MM-YYYY');
            }
        },
        {
            data: 'status',
            name: 'status',
            render: function(data,type,row){
                if(row.status == 'IN'){
                    return '<span class="badge bg-green text-green-fg">'+data+'</span>';
                } else {
                    return '<span class="badge bg-red text-red-fg">'+data+'</span>';
                }
            }
        },
        {
            data: 'factory',
            name: 'factory',
        },
        {
            data: 'keterangan',
            name: 'keterangan',
        },
    );
    let lastAjaxRequest = null;

    table = $('#transactionTable').DataTable({
        processing: true,
        serverSide: true,
        select: true,
        paging: true,
        ajax: function(data, callback, settings) {
            if (lastAjaxRequest) {
                lastAjaxRequest.abort();
            }
            lastAjaxRequest = $.ajax({
                url: getTransaction,
                type: 'GET',
                data: {
                    ...data,
                    start_date: $('#start_date').val(),
                    end_date: $('#end_date').val(),
                },
                success: function(res) {
                    callback(res);
                },
                error: function(xhr, textStatus) {
                    if (textStatus !== 'abort') {
                        console.error("Error:", xhr.statusText);
                        setTimeout(() => table.ajax.reload(null, false), 1000);
                    }
                }
            });
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
        lengthMenu: [10, 25, 50, 100, 100000],
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
    let searchDelay;
    $('#transactionTable tfoot input').on('keyup', function () {
        clearTimeout(searchDelay);
        const self = this;
        searchDelay = setTimeout(() => {
            table
                .column($(self).parent().index() + ':visible')
                .search(self.value)
                .draw();
        }, 500); 
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
    flatpickr("#bhidden_date", {
        dateFormat: "d F Y",
        locale: 'id', 
        allowInput: true,
        defaultDate: new Date(),
        onChange:function(selectedDates, dateStr, instance){
            var formatedDate = instance.formatDate(selectedDates[0],'Y-m-d');
            $('#btanggal').val(formatedDate);
        },
        onReady: function(selectedDates, dateStr, instance) {
            if (selectedDates.length > 0) {
                var formatedDate = instance.formatDate(selectedDates[0],'Y-m-d');
                $('#btanggal').val(formatedDate);
            }
        }
    });
    flatpickr("#hidden_start_date", {
        dateFormat: "d-m-Y",
        locale: 'id', 
        allowInput: true,
        defaultDate: new Date(),
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
function Others(){
    $('#modal-transaction').on('shown.bs.modal', function () {
        $('#code_barang').select2({
            dropdownParent: $('#modal-transaction'),
            width: '100%'
        });
        $('#no_lane').select2({
            dropdownParent: $('#modal-transaction'),
            width: '100%'
        });
    });
    $('#modal-scantransaction').on('shown.bs.modal', function () {
        $('#sno_lane').select2({
            dropdownParent: $('#modal-scantransaction'),
            dropdownPosition: 'below',
            width: '100%'
        });
    });
    $('#modal-barcodetransaction').on('shown.bs.modal', function () {
        $('#bcode_barang').focus();
        $('#bcode_barang').off('keydown').on('keydown', function(event) { 
            if (event.key === 'Enter') {
                event.preventDefault();
                $('#bnik').focus(); // Fokus langsung ke input NIK
            }
        });
        $('#bnik').off('keydown').on('keydown', function(event) { 
            if (event.key === 'Enter') {
                event.preventDefault();
                $('#bqty').focus();
            }
        });
        $('#bno_lane').select2({
            dropdownParent: $('#modal-barcodetransaction'),
            dropdownPosition: 'below',
            width: '100%'
        });
    });
}
function crudTransaction(){
    $('#search').on('click',function(e){
        e.preventDefault();
        table.ajax.reload();
    })
  $('#addtransaction').on('click', function(e){
      e.preventDefault();
    //   if($('#nama').val() === '' || $('#nik').val() === '' || $('#section').val() === '' || $('#code_barang').val() === '' || $('#qty').val() === ''){
    //     Swal.fire({
    //         title: 'Error',
    //         icon: 'error',
    //         text: 'NIK, Nama, dan Section harus diisi!'
    //     })
    //     return;
    //   }
      const showError = (message) => {
            Swal.fire({
                title: 'Error',
                icon: 'error',
                text: message,
            });
        };

        const validateForm = () => {
            const fields = [
                { id: '#nik', message: 'Nik harus diisi' },
                { id: '#nama', message: 'Nama harus diisi' },
                { id: '#section', message: 'Section harus diisi' },
                { id: '#code_barang', message: 'Code barang harus diisi' },
                { id: '#qty', message: 'quantity harus diisi' },
            ];

            for (const field of fields) {
                if ($(field.id).val() === '' || $(field.id).val() == null) {
                    showError(field.message);
                    return false; 
                }
            }
            return true; 
        };
        if(!validateForm()){
            return;
        }
      var formData = new FormData($('#formaddtransaction')[0]);
      $('#addtransaction').hide();
      $('#loading').show();
      $.ajax({
          url: createTransaction,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response){
              if(response.status === 201){
                $('#addtransaction').show();
                $('#loading').hide();
                  Swal.fire({
                      title: 'Success',
                      icon: 'success',
                      text: response.message
                  });
                  table.ajax.reload()
              } else {
                $('#addtransaction').show();
                $('#loading').hide();
                  Swal.fire({
                      title: 'Error',
                      icon: 'error',
                      text: response.message
                  });
              }
          }
      })
  });
  $('#resetscantransaction').on('click', function(e){
    e.preventDefault();
    resetScanner();
  })
  $('#addstransaction').on('click', function(e){
      e.preventDefault();
      var formData = new FormData($('#formaddscantransaction')[0]);
      $('#addstransaction').hide();
      $('#sloading').show();
      $.ajax({
          url: createTransaction,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response){
              if(response.status === 201){
                $('#addstransaction').show();
                $('#sloading').hide();
                  Swal.fire({
                      title: 'Success',
                      icon: 'success',
                      text: response.message
                  });
                  resetScanner();
                  table.ajax.reload();
              } else {
                $('#addstransaction').show();
                $('#sloading').hide();
                  Swal.fire({
                      title: 'error',
                      icon: 'error',
                      text: 'error'
                  });
              }
          }
      })
  });
  $('#addbarcodetransaction').on('click', function(e){
      e.preventDefault();
      var formData = new FormData($('#formaddbarcodetransaction')[0]);
      $('#addbarcodetransaction').hide();
      $('#bloading').show();
      $.ajax({
          url: createTransaction,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response){
              if(response.status === 201){
                $('#addbarcodetransaction').show();
                $('#bloading').hide();
                  Swal.fire({
                      title: 'Success',
                      icon: 'success',
                      text: response.message
                  });
                  table.ajax.reload();
              } else {
                $('#addbarcodetransaction').show();
                $('#bloading').hide();
                  Swal.fire({
                      title: 'error',
                      icon: 'error',
                      text: 'error'
                  });
              }
          }
      })
  });
  $('#modaledittransaction').on('click', function(e){
    e.preventDefault();
    var selectedData = table.rows({
        selected: true
    }).data();
    var codebarang = $('#ucode_barang');
    var namabarang = $('#unama_barang');
    var spek = $('#uspek');
    var satuan = $('#usatuan');
    var status_stock = $('#ustatus_stock');
    var factory = $('#ufactory');
    var nik = $('#unik');
    var nama = $('#unama');
    var section = $('#usection');
    var no_lane = $('#uno_lane');
    var supplier_name = $('#usupplier_name');
    var qty = $('#uqty');
    var tanggal = $('#utanggal');
    var keterangan = $('#uketerangan');
        if(selectedData.length > 0){
            codebarang.val(selectedData[0].code_barang);
            namabarang.val(selectedData[0].nama_barang);
            spek.val(selectedData[0].spek);
            satuan.val(selectedData[0].satuan);
            status_stock.val(selectedData[0].status_barang);
            factory.val(selectedData[0].factory);
            nik.val(selectedData[0].nik);
            nama.val(selectedData[0].namapic);
            section.val(selectedData[0].section);
            no_lane.val(selectedData[0].no_lane);
            supplier_name.val(selectedData[0].supplier_name);
            qty.val(selectedData[0].quantity);
            tanggal.val(selectedData[0].tanggal);
            keterangan.val(selectedData[0].keterangan);
            $('#modalEdit').modal('show');
        } else {
            $('#modalEdit').modal('hide');
            Swal.fire({
                title: 'Info',
                icon: 'info',
                text: 'No Data Selected',
            });
        }
  })
  $('#edittransaction').on('click', function(e){
    e.preventDefault();
    var selectedData = table.rows({
        selected: true
    }).data();
    if (selectedData.length == 0) {
        Swal.fire({
            title: 'Error',
            icon: 'error',
            text: 'Tidak ada data yang dipilih!',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        });
        return;
    }
    var row = selectedData[0];
    var uID = row.transaction_id;
    var updatetransaction = editTransaction + '/' + uID;
    var formID = '#formedittransaction';
    if (selectedData.length > 0) {
        Swal.fire({
            title: 'Update',
            icon: 'warning',
            text: 'Yakin data ingin diubah?',
            showCancelButton: true,
            confirmButtonText: 'Ya, Ubah!!',
        }).then((result) => {
            if (result.isConfirmed) {
                $('#edittransaction').hide();
                $('#loadingupdate').show();
                var formUptransaction = new FormData($(formID)[0]);
                $.ajax({
                    type: 'POST',
                    url: updatetransaction,
                    data: formUptransaction,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 201) {
                            $('#edittransaction').show();
                            $('#loadingupdate').hide();
                            Swal.fire({
                                title: 'success',
                                icon: 'success',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true,
                            })
                            table.ajax.reload(null, false);
                            $('#formedittransaction')[0].reset();
                        } else {
                            $('#edittransaction').show();
                            $('#loadingupdate').hide();
                            Swal.fire({
                                title: 'error',
                                icon: 'error',
                                text: 'Data gagal diupdate',
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true,
                            })
                        }
                    }
                })
            }
        })
    }
  })
  $('#deletetransaction').on('click', function(e){
    e.preventDefault();
    var selectedData = table.rows({
        selected: true
    }).data();
    if(selectedData.length === 0){
        Swal.fire({
            title: 'info',
            icon: 'info',
            text: 'No data selected',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
        })
        return
    }
    if (selectedData.length > 0) {
        Swal.fire({
            title: 'Delete',
            icon: 'warning',
            text: 'Yakin ingin dihapus?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!!',
        }).then((result) => {
            if (result.isConfirmed) {
                selectedData.each(function(data) {
                    const uuid = data.transaction_id;
                    $.ajax({
                        type: 'DELETE',
                        url: deleteTransaction +'/'+ uuid,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.status === 200) {
                                Swal.fire({
                                    title: 'Success',
                                    icon: 'success',
                                    text: response.message,
                                    timer: 1500,
                                    timerProgressBar: true,
                                });
                                table.ajax.reload(null, false);
                            } else {
                                Swal.fire({
                                    title: 'Error',
                                    icon: 'error',
                                    text: 'Data Error',
                                    timer: 1500,
                                    timerProgressBar: true,
                                });
                            }
                        }
                    })
                })
            }
        })
    }
  })
}
function getBarang(){
    $('#code_barang').on('change',function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            data: {
                code_barang : $('#code_barang').val(),
            },
            url: getBarangs,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.status === 200){
                    $('#nama_barang').val(response.data.nama_barang);
                    $('#spek').val(response.data.spek);
                    $('#satuan').val(response.data.satuan);
                    $('#status_stock').val(response.data.status_barang);
                    $('#min_stock').val(response.data.min_stock);
                    $('#std_stock').val(response.data.std_stock);
                    $('#stock').val(response.data.stock);
                }
            }
        })
    })
    $('#bcode_barang').on('change',function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            data: {
                code_barang : $('#bcode_barang').val(),
            },
            url: getBarangs,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(response.status === 200){
                    $('#bnama_barang').val(response.data.nama_barang);
                    $('#bspek').val(response.data.spek);
                    $('#bsatuan').val(response.data.satuan);
                    $('#bstatus_stock').val(response.data.status_barang);
                    $('#bmin_stock').val(response.data.min_stock);
                    $('#bstd_stock').val(response.data.std_stock);
                    $('#bstock').val(response.data.stock);
                }
            }
        })
    })
}
function getApi(){
    $('#nik').on('change', function(e){
        e.preventDefault();
        var username = $('#nik').val();
        $.ajax({
            type: 'POST',
            data: {
                nik: username,
                _token: csrfToken,
            },
            url: getApiEmployee,
            dataType: 'json',
            success: function(response){
                if(response.status === 200){
                    $('#nama').val(response.data[0].nama);
                    $('#section').val(response.data[0].kode_section);
                }   
            }
        })
    })
    $('#snik').on('change', function(e){
        e.preventDefault();
        var username = $('#snik').val();
        $.ajax({
            type: 'POST',
            data: {
                nik: username,
                _token: csrfToken,
            },
            url: getApiEmployee,
            dataType: 'json',
            success: function(response){
                if(response.status === 200){
                    $('#snama').val(response.data[0].nama);
                    $('#ssection').val(response.data[0].kode_section);
                }   
            }
        })
    })
    $('#bnik').on('change', function(e){
        e.preventDefault();
        var username = $('#bnik').val();
        $.ajax({
            type: 'POST',
            data: {
                nik: username,
                _token: csrfToken,
            },
            url: getApiEmployee,
            dataType: 'json',
            success: function(response){
                if(response.status === 200){
                    $('#bnama').val(response.data[0].nama);
                    $('#bsection').val(response.data[0].kode_section);
                }   
            }
        })
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
                            const resultElement = document.getElementById('result');
                            if (response.status == 200) {
                                document.getElementById('scode_barang').value = response.data.code_barang;
                                document.getElementById('snama_barang').value = response.data.nama_barang;
                                document.getElementById('sspek').value = response.data.spek;
                                document.getElementById('ssatuan').value = response.data.satuan;
                                document.getElementById('sstatus_stock').value = response.data.status_barang;
                                document.getElementById('smin_stock').value = response.data.min_stock;
                                document.getElementById('sstd_stock').value = response.data.std_stock;
                                document.getElementById('sstock').value = response.data.stock;
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