var table;
$(document).ready(function(){
    crudSupplier();
    table = $('#supplierTable').DataTable({
        processing: true,
        serverSide: true,
        select: true,
        paging: true,
        ajax: getSupplier,
        columns: [
            {
                data: 'supplier_id',
                name: 'supplier_id',
                render: function(data, type, row, meta){
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'supplier_name',
                name: 'supplier_name',
            },
            {
                data: 'keterangan',
                name: 'keterangan',
            },
        ]
    })
})
function crudSupplier(){
  $('#addsupplier').on('click', function(e){
      e.preventDefault();
      var formData = new FormData($('#formaddsupplier')[0]);
      $('#addsupplier').hide();
      $('#loading').show();
      $.ajax({
          url: createSupplier,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response){
              if(response.status === 201){
                $('#addsupplier').show();
                $('#loading').hide();
                  Swal.fire({
                      title: 'Success',
                      icon: 'success',
                      text: response.message
                  });
                  table.ajax.reload()
              } else if(response.status === 406){
                  $('#addsupplier').show();
                  $('#loading').hide();
                Swal.fire({
                    title: 'Error',
                    icon: 'error',
                    text: response.message,
                });
              } else {
                $('#addsupplier').show();
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
  $('#importsupplier').on('click', function(e){
    e.preventDefault();
    var formData = new FormData($('#formimportsupplier')[0]);
    $('#importsupplier').hide();
    $('#loadingimport').show();
    $.ajax({
        url: importSupplier,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response){
            if(response.status === 201){
                $('#importsupplier').show();
                $('#loadingimport').hide();
                Swal.fire({
                    title: 'Success',
                    icon: 'success',
                    text: response.message
                });
                table.ajax.reload(null, false);
            } else {
                $('#importsupplier').show();
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
  $('#modaleditsupplier').on('click', function(e){
    e.preventDefault();
    var selectedData = table.rows({
        selected: true
    }).data();
    var supplier_name = $('#usupplier_name');
    var keterangan = $('#uketerangan');
        if(selectedData.length > 0){
            supplier_name.val(selectedData[0].supplier_name);
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
  $('#editsupplier').on('click', function(e){
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
    var uID = row.supplier_id;
    var updateSupplier = editSupplier + '/' + uID;
    var formID = '#formeditsupplier';
    if (selectedData.length > 0) {
        Swal.fire({
            title: 'Update',
            icon: 'warning',
            text: 'Yakin data ingin diubah?',
            showCancelButton: true,
            confirmButtonText: 'Ya, Ubah!!',
        }).then((result) => {
            if (result.isConfirmed) {
                $('#editsupplier').hide();
                $('#loadingupdate').show();
                var formUpsupplier = new FormData($(formID)[0]);
                $.ajax({
                    type: 'POST',
                    url: updateSupplier,
                    data: formUpsupplier,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 201) {
                            $('#editsupplier').show();
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
                            $('#formeditsupplier')[0].reset();
                        } else {
                            $('#editsupplier').show();
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
  $('#deletesupplier').on('click', function(e){
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
                    const uuid = data.supplier_id;
                    $.ajax({
                        type: 'DELETE',
                        url: deleteSupplier +'/'+ uuid,
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