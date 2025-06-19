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
                        <h1>Manage Express Services</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Add Express Services</li>
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
                                <h3 class="card-title">Add New Service</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form id="serviceForm">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="servicesTitle">Services Title</label>
                                                <input type="text" class="form-control" id="servicesTitle"
                                                    name="servicesTitle" placeholder="Services Title"
                                                    value="{{ !empty($singleService) ? $singleService->title : '' }}">

                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="servicesPrice">Services Price+GST</label>
                                                <input type="text" class="form-control" id="servicesPrice"
                                                    name="servicesPrice" placeholder="Services Price"
                                                    value="{{ !empty($singleService) ? $singleService->price : '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="servicesType">Services Type</label>
                                                <select name="type" id="servicesType" class="form-control">
                                                    <option value="ex"
                                                        @if (!empty($singleService) && $singleService->type == 'ex') selected @endif>
                                                        Regular</option>
                                                    <option value="stex"
                                                        @if (!empty($singleService) && $singleService->type == 'stex') selected @endif>
                                                        Special Price</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <input type="hidden" name="id"
                                        value="{{ !empty($singleService) ? $singleService->id : '' }}">
                                    <button class="btn btn-primary">Add Service</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Title</th>
                                            <th>Price</th>
                                            <th>status</th>
                                            <th>Service Type</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodyData">
                                        @include('admin.inc.expressServices')
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
    <!-- Page specific script -->
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });

        $(document).ready(function() {
            $("#serviceForm").on("submit", function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                // Front-end validation
                var servicesTitle = $('#servicesTitle').val().trim();
                var servicesPrice = $('#servicesPrice').val().trim();

                if (!servicesTitle) {
                    Toast("error", "Service Title is required");
                    return;
                }
                if (!servicesPrice) {
                    Toast("error", "Service Price is required");
                    return;
                }

                $.ajax({
                    url: "{{ route('admin.addExpressServices') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        $('#serviceForm')[0].reset();
                        if (response.success) {
                            Toast("success", response.message);
                            $('#bodyData').html(response.html);
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

        // update services status
        $('.status-toggle').on('change', function() {
            var serviceId = $(this).data('id');
            var newStatus = $(this).is(':checked') ? 'active' : 'inactive';
            // console.log('Service ID:', serviceId, 'New Status:', newStatus);
            $.ajax({
                url: "{{ route('admin.update.ExSs') }}",
                type: 'POST',
                data: {
                    id: serviceId,
                    status: newStatus,
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Toast("success", response.message);
                    } else {
                        Toast("error", response.message);
                    }
                },
                error: function() {
                    Toast('error', 'An error occurred. Please try again.');
                }
            });

        });
    </script>
@endpush
