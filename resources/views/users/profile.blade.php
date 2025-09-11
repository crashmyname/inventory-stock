@extends('layout.app')
@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
          <div class="row g-2 align-items-center">
            <div class="col">
              <h2 class="page-title">
                Account Settings
              </h2>
            </div>
          </div>
        </div>
      </div>
      <!-- Page body -->
      <div class="page-body">
        <form action="" method="POST" enctype="multipart/form-data" id="formupdate">
            @csrf
            <div class="container-xl">
            <div class="card">
                <div class="row g-0">
                <div class="col-12 col-md-3 border-end">
                    <div class="card-body">
                    <h4 class="subheader">Business settings</h4>
                    <div class="list-group list-group-transparent">
                        <a href="#" class="list-group-item list-group-item-action d-flex align-items-center active">My Account</a>
                    </div>
                    </div>
                </div>
                <div class="col-12 col-md-9 d-flex flex-column">
                    <div class="card-body">
                    <h2 class="mb-4">My Account</h2>
                    <h3 class="card-title">Profile Details</h3>
                    <div class="row align-items-center">
                        <div class="col-auto"><span class="avatar avatar-xl" style="background-image: url({{asset('storage/profile/'.auth()->user()->picture)}})"></span>
                        </div>
                        <div class="col-auto"><input type="file" name="picture" id="picture" class="form-control" value="Change Avatar"></div>
                        <div class="col-auto"><a href="#" class="btn btn-ghost-danger">
                            Delete avatar
                        </a></div>
                    </div>
                    <h3 class="card-title mt-4">Profile</h3>
                    <div class="row g-3">
                        <div class="col-md">
                        <div class="form-label">Username</div>
                        <input type="text" class="form-control" name="username" id="username" value="{{$user->username}}" readonly>
                        </div>
                        <div class="col-md">
                        <div class="form-label">Name</div>
                        <input type="text" class="form-control" name="name" id="name" value="{{$user->name}}">
                        </div>
                        <div class="col-md">
                        <div class="form-label">Factory</div>
                        <input type="text" class="form-control" name="factory" id="factory"
                    value="{{$user->factory}}">
                        </div>
                    </div>
                    <h3 class="card-title mt-4">Departement</h3>
                    <div class="row g-2">
                        <div class="col-md">
                        <div class="form-label">Dept</div>
                        <input type="text" class="form-control" name="dept" id="dept" value="{{$user->dept}}">
                        </div>
                        <div class="col-md">
                        <div class="form-label">Section</div>
                        <input type="text" class="form-control" name="section" id="section" value="{{$user->section}}">
                        </div>
                    </div>
                    <h3 class="card-title mt-4">Email</h3>
                    <p class="card-subtitle">This contact will be shown to others publicly, so choose it carefully.</p>
                    <div>
                        <div class="row g-2">
                        <div class="col-md">
                            <input type="text" class="form-control" name="email" id="email" value="{{$user->email}}">
                        </div>
                        </div>
                    </div>
                    <h3 class="card-title mt-4">Password</h3>
                    <p class="card-subtitle">You can set a permanent password if you don't want to use temporary login codes.</p>
                    <div>
                        <div class="row">
                            <div class="col-lg-3">
                                <a href="#" class="btn">
                                Set new password
                                </a>
                            </div>
                            <div class="col-lg-9">
                                <input type="password" name="password" id="password" class="form-control" placeholder="New Password">
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="card-footer bg-transparent mt-auto">
                    <div class="btn-list justify-content-end">
                        <a href="#" class="btn">
                        Cancel
                        </a>
                        <button type="submit" class="btn btn-primary" id="btnupdate">
                        Submit
                        </button>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </form>
        <script>
            $(document).ready(function(){
                $('#btnupdate').on('click',function(e){
                    e.preventDefault();
                    var form = new FormData($('#formupdate')[0]);
                    Swal.fire({
                        title: 'Update',
                        icon: 'warning',
                        text: 'Yakin data ingin diubah?',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Ubah!!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'POST',
                                url: '{{url('/profile/'.$user->user_id)}}',
                                data: form,
                                contentType: false,
                                processData: false,
                                dataType: 'json',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success:function(response){
                                    if(response.status == 201){
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success',
                                            text: response.message
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: response.message
                                        });
                                    }
                                }
                            })
                        }
                    })
                })
            })
        </script>
@endsection