@extends('admin.layout.main')
@push('style')
    {{-- light gallery for zoom image --}}
    <link href="https://cdn.jsdelivr.net/npm/lightgallery@2.8.2/css/lightgallery.min.css" rel="stylesheet">
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
                        <h1>All Franchise Form</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/admin-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">All Franchise Form</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Name</th>
                                            <th>Address</th>
                                            <th>Pincode</th>
                                            <th>Mobile Number</th>
                                            <th>Premises (Owned or Rented)</th>
                                            <th>No of Delivery Boys</th>
                                            <th>Qualification</th>
                                            <th>Experience</th>
                                            <th>Reference (if any)</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodyData">
                                        @foreach ($data as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $item->name ?? '' }}</td>
                                                <td>{{ $item->address ?? ''  }}</td>
                                                <td>{{ $item->pincode ?? '' }}</td>
                                                <td>{{ $item->mobile ?? '' }}</td>
                                                <td>{{ $item->premises ?? '' }}</td>
                                                <td>{{ $item->no_of_delivery_boys ?? '' }}</td>
                                                <td>{{ $item->qualification ?? '' }}</td>
                                                <td>{{ $item->experience ?? '' }}</td>
                                                <td>{{ $item->reference ?? '' }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $item->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach

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
    <!-- /.modal -->
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Assign Branch</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="branchForm">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="branch" class="col-sm-3 col-form-label">Branch</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="branch" name="branch">
                                    <option>Select Branch</option>
                                    <option value="Booking">Booking</option>
                                    <option value="Seller">Seller</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="enquiry_id" id="enquiry_id" value="">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- light gallery for zoom image --}}
    <script src="https://cdn.jsdelivr.net/npm/lightgallery@2.8.2/lightgallery.min.js"></script>
    <script type="text/javascript">
        lightGallery(document.getElementById('lightgallery'), {
            plugins: [lgZoom, lgThumbnail],
            speed: 500,
            licenseKey: 'your_license_key'
        });
    </script>
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
        $(document).on("click", ".delete-btn", function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this Enquiry data!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })

                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('admin.delete.franchiseform') }}",
                            data: {
                                id: id
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.success) {
                                    swal("Poof! Your data has been deleted!", {
                                        icon: "success",
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    swal("Error!", response.message || "Unable to delete the data.",
                                        "error");
                                }
                            }
                        });
                    } else {
                        swal("Your Enquiry data is safe!");
                    }
                });
        });

        $(document).on("click", "#status", function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            $('#modal-default').modal('show');
            $('#enquiry_id').val(id);
        });

        $(document).on("submit", "#branchForm", function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $.ajax({
                url: "{{ route('admin.enquiry.assign.branch') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#branchForm')[0].reset();
                        $('#modal-default').modal('hide');
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
    </script>
@endpush
