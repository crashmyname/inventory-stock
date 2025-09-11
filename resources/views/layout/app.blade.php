
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>E-Mart System</title>
    <!-- CSS files -->
    <link href="{{asset('tabler/dist/css/tabler.min.css?1692870487')}}" rel="stylesheet"/>
    <link href="{{asset('tabler/dist/css/tabler-flags.min.css?1692870487')}}" rel="stylesheet"/>
    <link href="{{asset('tabler/dist/css/tabler-payments.min.css?1692870487')}}" rel="stylesheet"/>
    <link href="{{asset('tabler/dist/css/tabler-vendors.min.css?1692870487')}}" rel="stylesheet"/>
    <link href="{{asset('tabler/dist/css/demo.min.css?1692870487')}}" rel="stylesheet"/>
    <link rel="shortcut icon" href="{{ asset('image/logomart.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('image/logomart.png') }}" type="image/png">

    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
    {{-- DATATABLE --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.bootstrap5.min.css">

    <link href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.bootstrap5.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/select/3.0.0/css/select.dataTables.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    {{-- OTHERS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    

    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
      	--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
      	font-feature-settings: "cv03", "cv04", "cv11";
      }
      .select2-container {
          z-index: 999999 !important;
      }
      /* Supaya Select2 mirip form-control Bootstrap/Tabler */
      .select2-container--default .select2-selection--single {
          height: calc(1.5em + .75rem + 2px); /* Tinggi standar Bootstrap */
          padding: .375rem .75rem;
          font-size: 1rem;
          line-height: 1.5;
          color: #495057;
          background-color: #fff;
          border: 1px solid #ced4da;
          border-radius: .375rem;
      }

      .select2-container--default .select2-selection--single .select2-selection__rendered {
          line-height: 1.5;
          color: #495057;
          padding-left: 0;
      }

      .select2-container--default .select2-selection--single .select2-selection__arrow {
          height: 100%;
          right: 10px;
      }
    </style>
  </head>
  <body>
    <script src="{{asset('tabler/dist/js/demo-theme.min.js?1692870487')}}"></script>
    <div class="page">
      <!-- Sidebar -->
      <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
        <div class="container-fluid">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <h1 class="navbar-brand navbar-brand-autodark">
            <a href="{{route('homes')}}">
              <img src="{{asset('image/logomart.png')}}" width="110" height="32" alt="Tabler" class="navbar-brand-image">
            </a>
          </h1>
          <div class="navbar-nav flex-row d-lg-none">
            <div class="nav-item dropdown">
              <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                <span class="avatar avatar-sm" style="background-image: url({{asset('storage/profile/'.auth()->user()->picture)}})"></span>
                <div class="d-none d-xl-block ps-2">
                  <div>{{auth()->user()->name}}</div>
                  <div class="mt-1 small text-secondary">{{auth()->user()->role}}</div>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <a href="{{url('/profile/'.auth()->user()->user_id)}}" class="dropdown-item">Profile</a>
                <a href="{{route('logout')}}" class="dropdown-item">Logout</a>
              </div>
            </div>
          </div>
          <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">
              <li class="nav-item">
                <a class="nav-link" href="{{route('homes')}}" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                  </span>
                  <span class="nav-link-title">
                    Home
                  </span>
                </a>
              </li>
              <li class="nav-item dropdown">
                @if(auth()->user()->role == 'Administrator' || auth()->user()->role == 'Admin LA' || auth()->user()->role == 'Admin PP' || auth()->user()->role == 'Admin EVA' || auth()->user()->role == 'Admin PO'|| auth()->user()->role == 'Admin MDF' || auth()->user()->role == 'Admin GA' || auth()->user()->role == 'Admin ISS')
                <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="false" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-database-cog"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 6c0 1.657 3.582 3 8 3s8 -1.343 8 -3s-3.582 -3 -8 -3s-8 1.343 -8 3" /><path d="M4 6v6c0 1.657 3.582 3 8 3c.21 0 .42 -.003 .626 -.01" /><path d="M20 11.5v-5.5" /><path d="M4 12v6c0 1.657 3.582 3 8 3" /><path d="M19.001 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M19.001 15.5v1.5" /><path d="M19.001 21v1.5" /><path d="M22.032 17.25l-1.299 .75" /><path d="M17.27 20l-1.3 .75" /><path d="M15.97 17.25l1.3 .75" /><path d="M20.733 20l1.3 .75" /></svg>
                  </span>
                  <span class="nav-link-title">
                    Master Data
                  </span>
                </a>
                <div class="dropdown-menu">
                  <div class="dropdown-menu-columns">
                    <div class="dropdown-menu-column">
                      @if(auth()->user()->role == 'Administrator')
                      <a class="dropdown-item" href="{{route('user')}}">
                        Data User
                      </a>
                      @endif
                      <a class="dropdown-item" href="{{route('barang.index')}}">
                        Data Barang
                      </a>
                      @if(auth()->user()->role == 'Administrator' || auth()->user()->role == 'Admin LA' || auth()->user()->role == 'Admin PP' || auth()->user()->role == 'Admin EVA' || auth()->user()->role == 'Admin PO')
                      <a class="dropdown-item" href="{{route('lane.index')}}">
                        Data Lane
                      </a>
                      @endif
                      @if(auth()->user()->role == 'Administrator' || auth()->user()->role == 'Admin LA' || auth()->user()->role == 'Admin PP' || auth()->user()->role == 'Admin EVA' || auth()->user()->role == 'Admin PO' || auth()->user()->role == 'Admin MDF')
                      <a class="dropdown-item" href="{{route('supplier.index')}}">
                        Data Supplier
                      </a>
                      @endif
                    </div>
                  </div>
                </div>
                @endif
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="false" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/package -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12l0 9" /><path d="M12 12l-8 -4.5" /><path d="M16 5.25l-8 4.5" /></svg>
                  </span>
                  <span class="nav-link-title">
                    Stock Barang
                  </span>
                </a>
                <div class="dropdown-menu">
                  <div class="dropdown-menu-columns">
                    <div class="dropdown-menu-column">
                      @if(auth()->user()->role == 'Admin LA' || auth()->user()->role == 'Administrator')
                      <a class="dropdown-item" href="{{route('stockla')}}">
                        Stock LA/SMT
                      </a>
                      @endif
                      @if(auth()->user()->role == 'Admin PP' || auth()->user()->role == 'Administrator')
                      <a class="dropdown-item" href="{{route('stockpp')}}">
                        Stock PP
                      </a>
                      @endif
                      @if(auth()->user()->role == 'Admin EVA' || auth()->user()->role == 'Administrator')
                      <a class="dropdown-item" href="{{route('stockeva')}}">
                        Stock EVA
                      </a>
                      @endif
                      @if(auth()->user()->role == 'Admin PO' || auth()->user()->role == 'Administrator')
                      <a class="dropdown-item" href="{{route('stockpo')}}">
                        Stock PO
                      </a>
                      @endif
                      @if(auth()->user()->role == 'Admin MDF' || auth()->user()->role == 'Administrator')
                      <a class="dropdown-item" href="{{route('stockmdf')}}">
                        Stock MDF
                      </a>
                      @endif
                      @if(auth()->user()->role == 'Admin GA' || auth()->user()->role == 'Administrator')
                      <a class="dropdown-item" href="{{route('stockga')}}">
                        Stock GA
                      </a>
                      @endif
                      @if(auth()->user()->role == 'Admin ISS' || auth()->user()->role == 'Administrator')
                      <a class="dropdown-item" href="{{route('stockiss')}}">
                        Stock ISS
                      </a>
                      @endif
                    </div>
                  </div>
                </div>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{route('transaction.index')}}" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-qrcode"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M7 17l0 .01" /><path d="M14 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M7 7l0 .01" /><path d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" /><path d="M17 7l0 .01" /><path d="M14 14l3 0" /><path d="M20 14l0 .01" /><path d="M14 14l0 3" /><path d="M14 20l3 0" /><path d="M17 17l3 0" /><path d="M20 17l0 3" /></svg>
                  </span>
                  <span class="nav-link-title">
                    Transaction
                  </span>
                </a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="false" >
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-file-type-doc"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" /><path d="M5 15v6h1a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-1z" /><path d="M20 16.5a1.5 1.5 0 0 0 -3 0v3a1.5 1.5 0 0 0 3 0" /><path d="M12.5 15a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1 -3 0v-3a1.5 1.5 0 0 1 1.5 -1.5z" /></svg>
                  </span>
                  <span class="nav-link-title">
                    Report
                  </span>
                </a>
                <div class="dropdown-menu">
                  <div class="dropdown-menu-columns">
                    <div class="dropdown-menu-column">
                      @if(auth()->user()->role == 'Admin LA' || auth()->user()->role == 'Administrator')
                      <a class="dropdown-item" href="{{route('reportla')}}">
                        LA/SMT
                      </a>
                      @endif
                      @if(auth()->user()->role == 'Admin PP' || auth()->user()->role == 'Administrator')
                      <a class="dropdown-item" href="{{route('reportpp')}}">
                        PP
                      </a>
                      @endif
                      @if(auth()->user()->role == 'Admin EVA' || auth()->user()->role == 'Administrator')
                      <a class="dropdown-item" href="{{route('reporteva')}}">
                        EVA
                      </a>
                      @endif
                      @if(auth()->user()->role == 'Admin PO' || auth()->user()->role == 'Administrator')
                      <a class="dropdown-item" href="{{route('reportpo')}}">
                        PO
                      </a>
                      @endif
                      @if(auth()->user()->role == 'Admin MDF' || auth()->user()->role == 'Administrator')
                      <a class="dropdown-item" href="{{route('reportmdf')}}">
                        MDF
                      </a>
                      @endif
                      @if(auth()->user()->role == 'Admin GA' || auth()->user()->role == 'Administrator')
                      <a class="dropdown-item" href="{{route('reportga')}}">
                        GA
                      </a>
                      @endif
                      @if(auth()->user()->role == 'Admin ISS' || auth()->user()->role == 'Administrator')
                      <a class="dropdown-item" href="{{route('reportiss')}}">
                        ISS
                      </a>
                      @endif
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </aside>
      <div class="page-wrapper">
        <!-- Page header -->
        <div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                  Dashbord
                </div>
                <h2 class="page-title">
                  E-Mart System
                </h2>
              </div>
              <!-- Page title actions -->
              <div class="col-auto ms-auto d-print-none d-none d-lg-flex">
                <div class="navbar-nav ms-auto"> <!-- Tambahkan ms-auto di sini -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                            <span class="avatar avatar-sm" style="background-image: url({{asset('storage/profile/'.auth()->user()->picture)}})"></span>
                            <div class="d-none d-xl-block ps-2">
                                <div>{{auth()->user()->name}}</div>
                                <div class="mt-1 small text-secondary">{{auth()->user()->role}}</div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <a href="{{url('/profile/'.auth()->user()->user_id)}}" class="dropdown-item">Profile</a>
                            <a href="{{route('logout')}}" class="dropdown-item">Logout</a>
                        </div>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Page body -->
        <div class="page-body">
          @yield('content')
        </div>
        <footer class="footer footer-transparent d-print-none">
          <div class="container-xl">
            <div class="row text-center align-items-center flex-row-reverse">
              <div class="col-lg-auto ms-lg-auto">
                <ul class="list-inline list-inline-dots mb-0">
                  <li class="list-inline-item"><a href="https://crashmyname.github.io" target="_blank" class="link-secondary" rel="noopener">Develop By Fadli</a></li>
                </ul>
              </div>
              <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                <ul class="list-inline list-inline-dots mb-0">
                  <li class="list-inline-item">
                    Copyright &copy; 2023
                    <a href="https://tabler.io/admin-template" class="link-secondary">Tabler</a>.
                    All rights reserved.
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </div>
    {{-- <div class="modal modal-blur fade" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">New report</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Name</label>
              <input type="text" class="form-control" name="example-text-input" placeholder="Your report name">
            </div>
            <label class="form-label">Report type</label>
            <div class="form-selectgroup-boxes row mb-3">
              <div class="col-lg-6">
                <label class="form-selectgroup-item">
                  <input type="radio" name="report-type" value="1" class="form-selectgroup-input" checked>
                  <span class="form-selectgroup-label d-flex align-items-center p-3">
                    <span class="me-3">
                      <span class="form-selectgroup-check"></span>
                    </span>
                    <span class="form-selectgroup-label-content">
                      <span class="form-selectgroup-title strong mb-1">Simple</span>
                      <span class="d-block text-secondary">Provide only basic data needed for the report</span>
                    </span>
                  </span>
                </label>
              </div>
              <div class="col-lg-6">
                <label class="form-selectgroup-item">
                  <input type="radio" name="report-type" value="1" class="form-selectgroup-input">
                  <span class="form-selectgroup-label d-flex align-items-center p-3">
                    <span class="me-3">
                      <span class="form-selectgroup-check"></span>
                    </span>
                    <span class="form-selectgroup-label-content">
                      <span class="form-selectgroup-title strong mb-1">Advanced</span>
                      <span class="d-block text-secondary">Insert charts and additional advanced analyses to be inserted in the report</span>
                    </span>
                  </span>
                </label>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-8">
                <div class="mb-3">
                  <label class="form-label">Report url</label>
                  <div class="input-group input-group-flat">
                    <span class="input-group-text">
                      https://tabler.io/reports/
                    </span>
                    <input type="text" class="form-control ps-0"  value="report-01" autocomplete="off">
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="mb-3">
                  <label class="form-label">Visibility</label>
                  <select class="form-select">
                    <option value="1" selected>Private</option>
                    <option value="2">Public</option>
                    <option value="3">Hidden</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-lg-6">
                <div class="mb-3">
                  <label class="form-label">Client name</label>
                  <input type="text" class="form-control">
                </div>
              </div>
              <div class="col-lg-6">
                <div class="mb-3">
                  <label class="form-label">Reporting period</label>
                  <input type="date" class="form-control">
                </div>
              </div>
              <div class="col-lg-12">
                <div>
                  <label class="form-label">Additional information</label>
                  <textarea class="form-control" rows="3"></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
              Cancel
            </a>
            <a href="#" class="btn btn-primary ms-auto" data-bs-dismiss="modal">
              <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
              Create new report
            </a>
          </div>
        </div>
      </div>
    </div> --}}
    {{-- DATATABLE --}}
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/select/3.0.0/js/dataTables.select.js"></script>
    <script src="https://cdn.datatables.net/select/3.0.0/js/select.dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>

    <script src="https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    {{-- OTHERS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

    <!-- Libs JS -->
    <script src="{{asset('tabler/dist/libs/apexcharts/dist/apexcharts.min.js?1692870487')}}" defer></script>
    <script src="{{asset('tabler/dist/libs/jsvectormap/dist/js/jsvectormap.min.js?1692870487')}}" defer></script>
    <script src="{{asset('tabler/dist/libs/jsvectormap/dist/maps/world.js?1692870487')}}" defer></script>
    <script src="{{asset('tabler/dist/libs/jsvectormap/dist/maps/world-merc.js?1692870487')}}" defer></script>
    <!-- Tabler Core -->
    <script src="{{asset('tabler/dist/js/tabler.min.js?1692870487')}}" defer></script>
    <script src="{{asset('tabler/dist/js/demo.min.js?1692870487')}}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- SELECT2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  </body>
</html>