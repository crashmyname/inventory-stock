var table;
$(document).ready(function(){
    crudStock();
    getBarang();
    $('.select2').select2({
        theme: 'bootstrap-5'
      });
    table = $('#stockTable').DataTable({
        processing: true,
        serverSide: true,
        select: true,
        paging: true,
        ajax: getStockGA,
        columns: [
            {
                data: 'stock_id',
                name: 'stock_id',
                render: function(data, type, row, meta){
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'code_barang',
                name: 'barang.code_barang',
            },
            {
                data: 'nama_barang',
                name: 'barang.nama_barang',
            },
            {
                data: 'spek',
                name: 'barang.spek',
            },
            {
                data: 'satuan',
                name: 'barang.satuan',
            },
            {
                data: 'stock',
                name: 'stock',
            },
            {
                data: 'min_stock',
                name: 'min_stock',
                render:function(data,type,row){
                    return '<span class="badge bg-red text-red-fg">'+data+'</span>';
                }
            },
            {
                data: 'std_stock',
                name: 'std_stock',
                render:function(data,type,row){
                    return '<span class="badge bg-green text-green-fg">'+data+'</span>';
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
        ],
    })
})
function crudStock(){
  $('#addstock').on('click', function(e){
      e.preventDefault();
      var formData = new FormData($('#formaddstock')[0]);
      $('#addstock').hide();
      $('#loading').show();
      $.ajax({
          url: createStockGA,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response){
              if(response.status === 201){
                $('#addstock').show();
                $('#loading').hide();
                  Swal.fire({
                      title: 'Success',
                      icon: 'success',
                      text: response.message
                  });
                  table.ajax.reload()
              } else {
                $('#addstock').show();
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
  $('#importstock').on('click', function(e){
    e.preventDefault();
    var fromData = new FormData($('#formimportstock')[0]);
    $('#importstock').hide();
    $('#loadingimport').show();
    $.ajax({
        type: 'POST',
        data: fromData,
        url: importStock,
        processData: false,
        contentType: false,
        success: function(response){
            if(response.status === 201){
                $('#importstock').show();
                $('#loadingimport').hide();
                Swal.fire({
                    title: 'Success',
                    icon: 'success',
                    text: response.message,
                })
                table.ajax.reload(null, false);
            } else {
                $('#importstock').show();
                $('#loadingimport').hide();
                Swal.fire({
                    title: 'Error',
                    icon: 'error',
                    text: response.message,
                })
            }
        }
    })
  })
  $('#modaleditstock').on('click', function(e){
    e.preventDefault();
    var selectedData = table.rows({
        selected: true
    }).data();
    var codebarang = $('#ucodebarang');
    var namabarang = $('#unamabarang');
    var spek = $('#uspek');
    var satuan = $('#usatuan');
    var stock = $('#ustock');
    var min_stock = $('#umin_stock');
    var std_stock = $('#ustd_stock');
    var keterangan = $('#uketerangan');
        if(selectedData.length > 0){
            codebarang.val(selectedData[0].code_barang);
            namabarang.val(selectedData[0].nama_barang);
            spek.val(selectedData[0].spek);
            satuan.val(selectedData[0].satuan);
            satuan.val(selectedData[0].satuan);
            stock.val(selectedData[0].stock);
            min_stock.val(selectedData[0].min_stock);
            std_stock.val(selectedData[0].std_stock);
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
  $('#editstock').on('click', function(e){
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
    var uID = row.stock_id;
    var updatestock = editStockGA + '/' + uID;
    var formID = '#formeditstock';
    if (selectedData.length > 0) {
        Swal.fire({
            title: 'Update',
            icon: 'warning',
            text: 'Yakin data ingin diubah?',
            showCancelButton: true,
            confirmButtonText: 'Ya, Ubah!!',
        }).then((result) => {
            if (result.isConfirmed) {
                $('#editstock').hide();
                $('#loadingupdate').show();
                var formUpstock = new FormData($(formID)[0]);
                $.ajax({
                    type: 'POST',
                    url: updatestock,
                    data: formUpstock,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 201) {
                            $('#editstock').show();
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
                            $('#formeditstock')[0].reset();
                        } else {
                            $('#editstock').show();
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
  $('#deletestock').on('click', function(e){
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
                    const uuid = data.stock_id;
                    $.ajax({
                        type: 'DELETE',
                        url: deleteStockGA +'/'+ uuid,
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
            url: getBarangGA,
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
                }
            }
        })
    })
}