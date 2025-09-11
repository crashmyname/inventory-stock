var table;
$(document).ready(function(){
    crudUser();
    getApi();
    table = $('#userTable').DataTable({
        processing: true,
        serverSide: true,
        select: true,
        paging: true,
        ajax: getUser,
        columns: [
            {
                data: 'user_id',
                name: 'user_id',
                render: function(data, type, row, meta){
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'username',
                name: 'username',
            },
            {
                data: 'name',
                name: 'name',
            },
            {
                data: 'dept',
                name: 'dept',
            },
            {
                data: 'section',
                name: 'section',
            },
            {
                data: 'factory',
                name: 'factory',
            },
            {
                data: 'email',
                name: 'email',
            },
            {
                data: 'picture',
                name: 'picture',
            },
            {
                data: 'role',
                name: 'role',
            },
        ]
    })
})
function crudUser(){
  $('#adduser').on('click', function(e){
      e.preventDefault();
      var formData = new FormData($('#formadduser')[0]);
      $('#adduser').hide();
      $('#loading').show();
      $.ajax({
          url: createUser,
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response){
              if(response.status === 201){
                $('#adduser').show();
                $('#loading').hide();
                  Swal.fire({
                      title: 'Success',
                      icon: 'success',
                      text: response.message
                  });
                  table.ajax.reload()
              } else {
                $('#adduser').show();
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
  $('#modaledituser').on('click', function(e){
    e.preventDefault();
    var selectedData = table.rows({
        selected: true
    }).data();
    var username = $('#uusername');
    var name = $('#uname');
    var email = $('#uemail');
    var dept = $('#udept');
    var section = $('#usection');
    var factory = $('#ufactory');
    var role = $('#urole');
        if(selectedData.length > 0){
            username.val(selectedData[0].username);
            name.val(selectedData[0].name);
            email.val(selectedData[0].email);
            dept.val(selectedData[0].dept);
            section.val(selectedData[0].section);
            factory.val(selectedData[0].factory);
            role.val(selectedData[0].role);
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
  $('#edituser').on('click', function(e){
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
    var uID = row.user_id;
    var updateUser = editUser + '/' + uID;
    var formID = '#formedituser';
    if (selectedData.length > 0) {
        Swal.fire({
            title: 'Update',
            icon: 'warning',
            text: 'Yakin data ingin diubah?',
            showCancelButton: true,
            confirmButtonText: 'Ya, Ubah!!',
        }).then((result) => {
            if (result.isConfirmed) {
                var formUpUser = new FormData($(formID)[0]);
                $.ajax({
                    type: 'POST',
                    url: updateUser,
                    data: formUpUser,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 201) {
                            Swal.fire({
                                title: 'success',
                                icon: 'success',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500,
                                timerProgressBar: true,
                            })
                            table.ajax.reload(null, false);
                            $('#formupdateshippers')[0].reset();
                        } else {
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
  $('#deleteuser').on('click', function(e){
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
                    const uuid = data.user_id;
                    $.ajax({
                        type: 'DELETE',
                        url: deleteUser +'/'+ uuid,
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
    $('#username').on('change', function(e){
        e.preventDefault();
        var username = $('#username').val();
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
                    $('#name').val(response.data[0].nama);
                    $('#email').val(response.data[0].work_email);
                    $('#dept').val(response.data[0].dept);
                    $('#section').val(response.data[0].kode_section);
                }   
            }
        })
    })
}