var table;
$(document).ready(function(){
    crudLane();
    table = $('#laneTable').DataTable({
        processing: true,
        serverSide: true,
        select: true,
        paging: true,
        ajax: getLane,
        columns: [
            {
                data: 'lane_id',
                name: 'lane_id',
                render: function(data, type, row, meta){
                    return meta.row + meta.settings._iDisplayStart + 1; 
                }
            },
            {
                data: 'no_lane',
                name: 'no_lane',
            },
            {
                data: 'keterangan',
                name: 'keterangan',
            },
        ]
    })
})
function crudLane(){
  $('#addlane').on('click', function(e){
      e.preventDefault();
      var formData = new FormData($('#formaddlane')[0]);
      $('#addlane').hide();
      $('#loading').show();
      $.ajax({
          url: createLane,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response){
              if(response.status === 201){
                $('#addlane').show();
                $('#loading').hide();
                  Swal.fire({
                      title: 'Success',
                      icon: 'success',
                      text: response.message
                  });
                  table.ajax.reload()
              } else {
                $('#addlane').show();
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
  $('#modaleditlane').on('click', function(e){
    e.preventDefault();
    var selectedData = table.rows({
        selected: true
    }).data();
    var no_lane = $('#uno_lane');
    var keterangan = $('#uketerangan');
        if(selectedData.length > 0){
            no_lane.val(selectedData[0].no_lane);
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
  $('#editlane').on('click', function(e){
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
    var uID = row.lane_id;
    var updatelane = editLane + '/' + uID;
    var formID = '#formeditlane';
    if (selectedData.length > 0) {
        Swal.fire({
            title: 'Update',
            icon: 'warning',
            text: 'Yakin data ingin diubah?',
            showCancelButton: true,
            confirmButtonText: 'Ya, Ubah!!',
        }).then((result) => {
            if (result.isConfirmed) {
                $('#editlane').hide();
                $('#loadingupdate').show();
                var formUplane = new FormData($(formID)[0]);
                $.ajax({
                    type: 'POST',
                    url: updatelane,
                    data: formUplane,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 201) {
                            $('#editlane').show();
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
                            $('#formeditlane')[0].reset();
                        } else {
                            $('#editlane').show();
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
  $('#deletelane').on('click', function(e){
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
                    const uuid = data.lane_id;
                    $.ajax({
                        type: 'DELETE',
                        url: deleteLane +'/'+ uuid,
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