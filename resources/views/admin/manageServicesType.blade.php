@extends('admin.layout.main')
@push('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush
@section('main')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Manage Services Type</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/admin-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/all-branch') }}">All Branch</a></li>
                            <li class="breadcrumb-item active">Manage Services Type</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Assign Services Type</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form id="servicesForm">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="services">Services</label>
                                                <select name="services" id="services" class="form-control">
                                                    <option>Select</option>
                                                    <option value="ss">Standard</option>
                                                    <option value="ex">Express</option>
                                                    <option value="se">Super Express</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="servicesType">Services Type</label>
                                                <select name="servicesType" id="servicesType" class="form-control">

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row py-3 bg-light">
                                        <div class="col-lg-12">
                                            <table id="example0" class="table table-bordered table-striped table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Sr No</th>
                                                        <th>Title</th>
                                                        <th>Price</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="bodyData">
                                                    @include('admin.inc.servicesType')
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <input type="hidden" name="userId" value="{{ request()->segment(2) }}">
                                    <button class="btn btn-primary float-right d-none" id="addBtn">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <table id="example1" class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Service</th>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="bodyData">
                                @include('admin.inc.servicesTypeData')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script>
        $(function() {
            $('#example0').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
            });
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
        $(document).ready(function() {
            $('#services').on('change', function(e) {
                e.preventDefault();
                var services = $(this).val();
                if (services == 'ss') {
                    $('#servicesType').html(
                        '<option>Select</option><option value="ss">Regular</option><option value="stss">Special Price</option>'
                    );
                } else if (services == 'ex') {
                    $('#servicesType').html(
                        '<option>Select</option><option value="ex">Regular</option><option value="stex">Special Price</option>'
                    );
                } else if (services == 'se') {
                    $('#servicesType').html(
                        '<option>Select</option><option value="se">Regular</option><option value="stse">Special Price</option>'
                    );
                } else {
                    $('#servicesType').html('');
                }
            });

            $('#servicesType').on('change', function(e) {
                e.preventDefault();
                var services = $(this).val();
                $.ajax({
                    url: "{{ route('admin.servicesType') }}",
                    type: "POST",
                    data: {
                        services: services
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            $('#bodyData').html(response.html);
                            $("#addBtn").removeClass('d-none');
                        } else {
                            Toast("error", response.message);
                        }
                    }
                });
            });

            $('#servicesForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.addServicesType') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Toast("success", response.message);
                            location.reload();
                        } else {
                            Toast("error", response.message);
                        }
                    },
                    error: function(err) {
                        Toast("error",
                            "An unexpected error occurred. Please try again.");
                    }
                });
            });
        });
    </script>
@endpush
