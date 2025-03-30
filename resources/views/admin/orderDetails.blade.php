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
                        <h1>Order Details</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/admin-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/all-branch') }}">All Branch</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ url('/branch-Manage-Branch/' . request()->segment(2)) }}">Branch Details</a>
                            </li>
                            <li class="breadcrumb-item active">Order Details</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                @if (request()->segment(3) == 'toDayOrder' ||
                                        request()->segment(3) == 'toDayPendingOrder' ||
                                        request()->segment(3) == 'toDayOrderPicUp' ||
                                        request()->segment(3) == 'toDayCancelledOrder')
                                    <h3 class="card-title">Today Orders</h3>
                                @elseif(request()->segment(3) == 'totalOrder' ||
                                        request()->segment(3) == 'totalPendingOrder' ||
                                        request()->segment(3) == 'totalOrderPicUp' ||
                                        request()->segment(3) == 'totalCompleteOrder' ||
                                        request()->segment(3) == 'totalCanceledOrder')
                                    <h3 class="card-title">Total Orders</h3>
                                @endif
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Label</th>
                                            <th>Order Id</th>
                                            <th>Order Date</th>
                                            <th>Sender Name</th>
                                            <th>Receiver Name</th>
                                            <th>Amount</th>
                                            <th>Payment Status</th>
                                            <th>Pickup PinCode</th>
                                            <th>Delivery PinCode</th>
                                            <th>Order Type</th>
                                            <th>Order Status</th>
                                            <th>Assign Order</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                        @include('admin.inc.orderDetails')
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
            $(document).on("click", ".status", function() {
                const id = $(this).data('id');
                $.ajax({
                    url: "{{ route('admin.status.get') }}",
                    type: "POST",
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#dBoy_orderStatus').modal('show');
                            $('#assignForm')[0].reset();
                            $('#type').val('OrderStatus');
                            $('#dBoyLabel').html('Order Status');
                            $('#orderIdedit').val(response.orderId);
                            $('#optionId').html('Select Order Status');
                            $('#btn').html('Update Order Status');
                            $('#deliverBoy').empty();
                            $('#deliverBoy').append(
                                "<option value='Booked'>Booked</option> <option value='Item Picked Up'>Item Picked Up</option> <option value='Returned'>Returned</option> <option value='In Transit'>In Transit</option> <option value='Arrived at Destination'>Arrived at Destination</option> <option value='Out for Delivery'>Out for Delivery</option> <option value='Delivered'>Delivered</option> <option value='Not Delivered'>Not Delivered</option> <option value='Returning to Origin'>Returning to Origin</option> <option value='Out for Delivery to Origin'>Out for Delivery to Origin</option>"
                            );
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

            $(document).on("click", ".assign", function() {
                const id = $(this).data('id');
                $.ajax({
                    url: "{{ route('admin.assign.get') }}",
                    type: "POST",
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#dBoy_orderStatus').modal('show');
                            $('#dBoyLabel').html('Order Assign');
                            $('#orderIdedit').val(response.orderId);
                            $('#optionId').html('Select Delivery Boy');
                            $('#btn').html('Assign Now');
                            $('#deliverBoy').empty();
                            if (response.data.length > 0) {
                                response.data.forEach(function(boy) {
                                    $('#deliverBoy').append('<option value="' + boy
                                        .id + '">' + boy.name + '</option>');
                                });
                            } else {
                                $('#deliverBoy').append(
                                    '<option value="">No active delivery boys available</option>'
                                );
                            }
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

            $(document).on("submit", '#assignForm', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('admin.assign.add') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        $('#assignForm')[0].reset();
                        if (response.success) {
                            Toast("success", response.message);
                            $('#dBoy_orderStatus').modal('hide');
                            // $("#tbody").html(response.html);
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
@push('modals')
    <!-- Modal dBoy  -->
    <div class="modal fade" id="dBoy_orderStatus" tabindex="-1" role="dialog" aria-labelledby="dBoyLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dBoyLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="assignForm">
                        <input type="hidden" name="type" id="type" name="type" value="">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="orderIdedit">Order Id</label>
                                <input type="text" class="form-control" id="orderIdedit" name="orderIdedit"
                                    value="" readonly>
                            </div>
                            <div class="form-group">
                                <label for="deliverBoy" id="optionId"></label>
                                <select class="custom-select rounded-0" id="deliverBoy" name="deliverBoy">

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status_message">Message</label>
                                <textarea class="form-control" id="status_message" name="status_message" placeholder="Enter Message"></textarea>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" id="btn"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush
