@extends('layout.app')
@section('content')
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card-header">
                    <h4 class="card-title">Menu User</h4>
                </div>
                <div class="card-body">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-user">
                        Add User
                    </button>
                    <button class="btn btn-yellow" data-bs-toggle="modal" data-bs-target="#modal-user-edit" id="modaledituser">
                        Edit User
                    </button>
                    <button type="submit" class="btn btn-danger" id="deleteuser">Delete User</button>
                    <table id="userTable" class="table card-table table-vcenter datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Dept</th>
                                <th>Section</th>
                                <th>Factory</th>
                                <th>Email</th>
                                <th>Picture</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-blur fade" id="modal-user" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="formadduser" action="" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">NIK</label>
                            <input type="text" class="form-control" name="username" id="username" placeholder="Your NIK">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="name"
                                placeholder="Your name" readonly>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <div class="input-group input-group-flat">
                                        <input type="email" name="email" class="form-control" id="email"
                                            autocomplete="off" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Dept</label>
                                    <input type="text" class="form-control" name="dept" id="dept" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Section</label>
                                    <input type="text" class="form-control" name="section" id="section" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Factory</label>
                                    <select name="factory" id="factory" class="form-control">
                                        <option value="" disabled selected hidden> -- Pilih -- </option>
                                        <option value="1">Factory 1</option>
                                        <option value="2">Factory 2</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Profile</label>
                                    <input type="file" name="picture" id="picture" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group input-group-flat">
                                    <input type="password" name="password" class="form-control" id="password"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <div class="input-group input-group-flat">
                                    <select name="role" id="role" class="form-control">
                                        <option value="" hidden selected disabled> -- Pilih -- </option>
                                        <option value="Administrator"> Administrator </option>
                                        <option value="Admin LA"> Admin LA </option>
                                        <option value="Admin PP"> Admin PP </option>
                                        <option value="Admin EVA"> Admin EVA </option>
                                        <option value="Admin PO"> Admin PO </option>
                                        <option value="Admin MDF"> Admin MDF </option>
                                        <option value="Admin GA"> Admin GA </option>
                                        <option value="Admin ISS"> Admin ISS </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="adduser" class="btn btn-primary ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Create new user
                        </button>
                        <button class="btn btn-primary ms-auto" style="display: none;" id="loading" disabled>
                            <div class="spinner-border me-2" role="status"></div>
                            <strong>Loading...</strong>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal modal-blur fade" id="modalEdit" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="formedituser" action="" enctype="multipart/form-data" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">NIK</label>
                            <input type="text" class="form-control" name="username" id="uusername" placeholder="Your NIK">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="uname"
                                placeholder="Your name">
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <div class="input-group input-group-flat">
                                        <input type="email" name="email" class="form-control" id="uemail"
                                            autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Dept</label>
                                    <input type="text" class="form-control" name="dept" id="udept">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Section</label>
                                    <input type="text" class="form-control" name="section" id="usection">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Factory</label>
                                    <select name="factory" id="ufactory" class="form-control">
                                        <option value="" disabled selected hidden> -- Pilih -- </option>
                                        <option value="1">Factory 1</option>
                                        <option value="2">Factory 2</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Profile</label>
                                    <input type="file" name="picture" id="picture" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group input-group-flat">
                                    <input type="password" name="password" class="form-control" id="upassword"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <div class="input-group input-group-flat">
                                    <select name="role" id="urole" class="form-control">
                                        <option value="" hidden selected disabled> -- Pilih -- </option>
                                        <option value="Administrator"> Administrator </option>
                                        <option value="Admin LA"> Admin LA </option>
                                        <option value="Admin PP"> Admin PP </option>
                                        <option value="Admin EVA"> Admin EVA </option>
                                        <option value="Admin PO"> Admin PO </option>
                                        <option value="Admin MDF"> Admin MDF </option>
                                        <option value="Admin GA"> Admin GA </option>
                                        <option value="Admin ISS"> Admin ISS </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="edituser" class="btn btn-yellow ms-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Update user
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
      var getUser = "{{route('user')}}";
      var createUser = "{{url('/cuser')}}";
      var editUser = "{{url('/user')}}";
      var deleteUser = "{{url('/user')}}";
      var getApiEmployee = "{{url('/apidata')}}";
      var csrfToken = "{{ csrf_token() }}";
    </script>
    <script src="{{asset('js/user.js')}}"></script>
@endsection
