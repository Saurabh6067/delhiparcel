
@extends('admin.layout.main')
<meta name="csrf-token" content="{{ csrf_token() }}">
@push('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush
@section('main')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Manage Estimated Services</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('admin-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Add Estimated Services</li>
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
                            <!-- form start -->
                            <form id="serviceForm">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="service_type">Service Type</label>
                                                <select class="form-control" id="service_type" name="service_type">
                                                    <option value="">Select Service Type</option>
                                                    <option value="ex">Express</option>
                                                    <option value="SuperExpress">SuperExpress</option>
                                                    <option value="ss">Standard</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="time">Estimated Time</label>
                                                <input type="time" class="form-control" id="time" name="time"
                                                       placeholder="e.g., 2 hours">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Add Service</button>
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
                            <div class="card-header">
                                <h3 class="card-title">All Estimated Services</h3>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Service Type</th>
                                            <th>Estimated Time</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodyData">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Edit Modal -->
        <div class="modal fade" id="editServiceModal" tabindex="-1" role="dialog" aria-labelledby="editServiceModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editServiceModalLabel">Edit Service</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editServiceForm">
                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit_service_id">
                            <div class="form-group">
                                <label for="edit_service_type">Service Type</label>
                                <select class="form-control" id="edit_service_type" name="service_type">
                                    <option value="">Select Service Type</option>
                                    <option value="ex">Express</option>
                                    <option value="SuperExpress">SuperExpress</option>
                                    <option value="ss">Standard</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="edit_time">Estimated Time</label>
                                <input type="time" class="form-control" id="edit_time" name="time">
                            </div>
                            <div class="form-group">
                                <label for="edit_status">Status</label>
                                <select class="form-control" id="edit_status" name="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Service</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteServiceModal" tabindex="-1" role="dialog" aria-labelledby="deleteServiceModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteServiceModalLabel">Confirm Delete</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this service?
                        <input type="hidden" id="delete_service_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <!-- DataTables scripts -->
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
    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(function () {
            // Initialize Toastr
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: 3000
            };

            // Initialize DataTable with AJAX
            let table = $("#example1").DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                buttons: ["copy", "excel", "pdf", "print"],
                ajax: {
                    url: "{{ route('estimated_services_data') }}",
                    dataSrc: 'data'
                },
                columns: [
                    { data: 'sr_no' },
                    { data: 'service_type' },
                    { data: 'time' },
                    { data: 'status' },
                    { data: 'action', orderable: false, searchable: false }
                ]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            // Handle form submission (Add Service)
            $('#serviceForm').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('store_estimated_service') }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            toastr.success(response.message);
                            $('#serviceForm')[0].reset();
                            table.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr) {
                        toastr.error('Error: ' + xhr.responseJSON.message);
                    }
                });
            });

            // Handle Edit Button Click
            $(document).on('click', '.edit-service', function () {
                let id = $(this).data('id');
                $.ajax({
                    url: "{{ url('admin/estimated-services') }}/" + id,
                    type: 'GET',
                    success: function (response) {
                        if (response.success) {
                            $('#edit_service_id').val(response.data.id);
                            $('#edit_service_type').val(response.data.service_type);
                            $('#edit_time').val(response.data.time);
                            $('#edit_status').val(response.data.status);
                            $('#editServiceModal').modal('show');
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr) {
                        toastr.error('Error: ' + xhr.responseJSON.message);
                    }
                });
            });

            // Handle Edit Form Submission
            $('#editServiceForm').on('submit', function (e) {
                e.preventDefault();
                let id = $('#edit_service_id').val();
                $.ajax({
                    url: "{{ url('admin/estimated-services') }}/" + id,
                    type: 'PUT',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            toastr.success(response.message);
                            $('#editServiceModal').modal('hide');
                            table.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr) {
                        toastr.error('Error: ' + xhr.responseJSON.message);
                    }
                });
            });

            // Handle Delete Button Click
            $(document).on('click', '.delete-service', function () {
                let id = $(this).data('id');
                $('#delete_service_id').val(id);
                $('#deleteServiceModal').modal('show');
            });

            // Handle Confirm Delete
            $('#confirmDelete').on('click', function () {
                let id = $('#delete_service_id').val();
                $.ajax({
                    url: "{{ url('admin/estimated-services') }}/" + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            toastr.success(response.message);
                            $('#deleteServiceModal').modal('hide');
                            table.ajax.reload();
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr) {
                        toastr.error('Error: ' + xhr.responseJSON.message);
                    }
                });
            });
        });
    </script>
@endpush
