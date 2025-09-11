var table;
$(document).ready(function(){
    crudBarang();
    getApi();
    generateCode();
    table = $('#barangTable').DataTable({
        processing: true,
        serverSide: true,
        select: true,
        paging: true,
        ajax: getBarang,
        columns: [
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
                data: 'qrcode',
                name: 'qrcode',
                render: function(data, type, row){
                    return '<img src="'+pathBarcode+'/'+data+'" alt="Barcode" width="200" height="200">';
                }
            },
            {
                data: 'keterangan',
                name: 'keterangan',
            },
        ],
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
        lengthMenu: [10, 25, 50, 100, 100000]
    })
})
function crudBarang(){
  $('#addbarang').on('click', function(e){
      e.preventDefault();
      var formData = new FormData($('#formaddbarang')[0]);
      $('#addbarang').hide();
      $('#loading').show();
      $.ajax({
          url: createBarang,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response){
              if(response.status === 201){
                $('#addbarang').show();
                $('#loading').hide();
                  Swal.fire({
                      title: 'Success',
                      icon: 'success',
                      text: response.message
                  });
                  table.ajax.reload()
              } else if(response.status === 406){
                  $('#addbarang').show();
                  $('#loading').hide();
                Swal.fire({
                    title: 'Error',
                    icon: 'error',
                    text: response.message,
                });
              } else {
                $('#addbarang').show();
                $('#loading').hide();
                  Swal.fire({
                      title: 'error',
                      icon: 'error',
                      text: 'error'
                  });
              }
          }
      })
  });
  $('#importbarang').on('click', function(e){
    e.preventDefault();
    var formData = new FormData($('#formimportbarang')[0]);
    $('#importbarang').hide();
    $('#loadingimport').show();
    $.ajax({
        url: importBarang,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response){
            if(response.status === 201){
                $('#importbarang').show();
                $('#loadingimport').hide();
                Swal.fire({
                    title: 'Success',
                    icon: 'success',
                    text: response.message
                });
                table.ajax.reload(null, false);
            } else {
                $('#importbarang').show();
                $('#loadingimport').hide();
                Swal.fire({
                    title: 'Error',
                    icon: 'error',
                    text: response.message
                });
                table.ajax.reload(null, false);
            }
        }
    })
  })
  $('#modaleditbarang').on('click', function(e){
    e.preventDefault();
    var selectedData = table.rows({
        selected: true
    }).data();
    var codebarang = $('#ucodebarang');
    var namabarang = $('#unamabarang');
    var spek = $('#uspek');
    var satuan = $('#usatuan');
    var keterangan = $('#uketerangan');
        if(selectedData.length > 0){
            codebarang.val(selectedData[0].code_barang);
            namabarang.val(selectedData[0].nama_barang);
            spek.val(selectedData[0].spek);
            satuan.val(selectedData[0].satuan);
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
  $('#editbarang').on('click', function(e){
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
    var uID = row.barang_id;
    var updatebarang = editBarang + '/' + uID;
    var formID = '#formeditbarang';
    if (selectedData.length > 0) {
        Swal.fire({
            title: 'Update',
            icon: 'warning',
            text: 'Yakin data ingin diubah?',
            showCancelButton: true,
            confirmButtonText: 'Ya, Ubah!!',
        }).then((result) => {
            if (result.isConfirmed) {
                $('#editbarang').hide();
                $('#loadingupdate').show();
                var formUpbarang = new FormData($(formID)[0]);
                $.ajax({
                    type: 'POST',
                    url: updatebarang,
                    data: formUpbarang,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 201) {
                            $('#editbarang').show();
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
                            $('#formeditbarang')[0].reset();
                        } else {
                            $('#editbarang').show();
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
  $('#deletebarang').on('click', function(e){
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
                    const uuid = data.barang_id;
                    $.ajax({
                        type: 'DELETE',
                        url: deleteBarang +'/'+ uuid,
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
function getApi(){
    $('#barangname').on('change', function(e){
        e.preventDefault();
        var barangname = $('#barangname').val();
        $.ajax({
            type: 'POST',
            data: {
                nik: barangname,
                _token: csrfToken,
            },
            url: getApiEmployee,
            dataType: 'json',
            success: function(response){
                if(response.status === 200){
                    $('#name').val(response.data[0].nama);
                    $('#email').val(response.data[0].work_email);
                    $('#dept').val(response.data[0].dept);
                    $('#section').val(response.data[0].kode_section);
                }   
            }
        })
    })
}
function generateCode(){
    $('#btnbarang').on('click', function(e){
        e.preventDefault();
        $.ajax({
            type: 'GET',
            url: generateCodeBarang,
            dataType: 'json',
            success: function(response){
                if(response.status === 200){
                    $('#codebarang').val(response.code)
                } else {
                    $('#codebarang').val('');
                }
            }
        })
    })
}