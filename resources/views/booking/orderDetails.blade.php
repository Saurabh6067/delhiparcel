@extends('booking.layout.main')

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
                            <li class="breadcrumb-item"><a href="{{ url('/booking-dashboard') }}">Dashboard</a></li>
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
                                @if (request()->segment(2) == 'toDayOrder' ||
                                        request()->segment(2) == 'toDayPendingOrder' ||
                                        request()->segment(2) == 'toDayOrderPicUp' ||
                                        request()->segment(2) == 'toDayCompleteOrder' ||
                                        request()->segment(2) == 'toDayCancelledOrder')
                                    <h3 class="card-title">Today Orders</h3>
                                @elseif(request()->segment(2) == 'totalOrder' ||
                                        request()->segment(2) == 'totalPendingOrder' ||
                                        request()->segment(2) == 'totalOrderPicUp' ||
                                        request()->segment(2) == 'totalCompleteOrder' ||
                                        request()->segment(2) == 'totalCancelledOrder')
                                    <h3 class="card-title">Total Orders</h3>
                                @endif
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>SrNo.</th>
                                            <th>Order Id</th>
                                            <th>Label</th>
                                            <th>Parcel Type</th>
                                            <th>Sender</th>
                                            <th>Receiver</th>
                                            <th>Pickup PinCode</th>
                                            <th>Delivery PinCode</th>
                                            <th>Service Type</th>
                                            <th>Weight/Distance</th>
                                            <th>Service Price</th>
                                            <th>Insurance</th>
                                            <th>Payment Method</th>
                                            <th>Price</th>
                                            <th>Order Status</th>
                                            <th>Order DateTime</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                        @php
                                            $sr = 1;
                                        @endphp
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $sr++ }}</td>
                                                <td>{{ $item->order_id }}</td>
                                                <td class="text-center">
                                                    <a href="{{ url('/booking-label/' . $item->order_id) }}" title="Order Label" target="_blank"
                                                        class="btn btn-sm btn-success">
                                                        <i class="fas fa-solid fa-file-invoice"></i>
                                                    </a>
                                                    <br/>
                                                    <span class="badge badge-danger" title="{{ $item->status_message }}">{{ $item->order_status }}</span>
                                                </td>
                                                <td class="text-capitalize">{{ $item->parcel_type }}</td>
                                                <td>
                                                    <span><b>Name: </b>{{ $item->sender_name }}</span> <br>
                                                    <span><b>Email: </b>{{ $item->sender_email ?? '--' }}</span> <br>
                                                    <span><b>Number: </b>{{ $item->sender_number }}</span> <br>
                                                    <span><b>Address: </b>{{ $item->sender_address ?? '--' }}</span>
                                                    @if ($item->order_status == 'Booked' && $item->parcel_type == 'Pickup')
                                                        <button class="btn btn-sm btn-primary edit-sender" data-id="{{ $item->id }}">
                                                            <i class="fas fa-edit text-white"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger cancel-order" data-id="{{ $item->id }}">
                                                            Cancel Order
                                                        </button>
                                                    @else
                                                        --
                                                    @endif
                                                </td>
                                                <td>
                                                    <span><b>Name: </b>{{ $item->receiver_name }}</span> <br>
                                                    <span><b>Email: </b>{{ $item->receiver_email ?? '--' }}</span> <br>
                                                    <span><b>Number: </b>{{ $item->receiver_cnumber }}</span> <br>
                                                    <span><b>Address: </b>{{ $item->receiver_add ?? '--' }}</span>
                                                    @if ($item->order_status == 'Booked' && $item->parcel_type == 'delivery')
                                                        <button class="btn btn-sm btn-primary edit-receiver" data-id="{{ $item->id }}">
                                                            <i class="fas fa-edit text-white"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger cancel-order" data-id="{{ $item->id }}">
                                                            Cancel Order
                                                        </button>
                                                    @else
                                                        --
                                                    @endif
                                                </td>
                                                <td>{{ $item->sender_pincode }}</td>
                                                <td>{{ $item->receiver_pincode }}</td>
                                                <td>
                                                    @if ($item->service_type == 'stss' || $item->service_type == 'ss')
                                                        <span class="font-weight-bold">Standard</span>
                                                    @elseif ($item->service_type == 'stex' || $item->service_type == 'ex')
                                                        <span class="font-weight-bold">Express</span>
                                                    @else
                                                        <span class="font-weight-bold">Super Express</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->service_title }}</td>
                                                <td>{{ $item->service_price }}</td>
                                                <td>
                                                    @if ($item->insurance == 'Yes')
                                                        <span class="btn btn-sm btn-success">Yes</span>
                                                    @else
                                                        <span class="btn btn-sm btn-danger">NO</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item->payment_mode == 'online')
                                                        <span class="btn btn-sm btn-success">{{ $item->payment_mode }}</span>
                                                    @else
                                                        <span class="btn btn-sm btn-danger">{{ $item->payment_mode }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->price }}</td>
                                                <td>
                                                    <span class="badge badge-danger" title="{{ $item->status_message }}">{{ $item->order_status }}</span>
                                                </td>
                                                <td>{{ $item->datetime }}</td>
                                                <td>
                                                    @if($item->order_status == 'Booked')
                                                    <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $item->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    @endif
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

@push('modals')
    <!-- Sender Details Modal -->
    <div class="modal fade" id="senderDetails" tabindex="-1" role="dialog" aria-labelledby="senderDetails" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sender Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="senderForm">
                        <input type="hidden" name="orderId" id="sender_orderId" value="">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="sender_id">Order Id</label>
                                <input type="text" class="form-control" id="sender_id" value="" disabled>
                            </div>
                            <div class="form-group">
                                <label for="sender_name">Sender Name</label>
                                <input type="text" class="form-control" id="sender_name" name="sender_name" value="">
                            </div>
                            <div class="form-group">
                                <label for="sender_number">Sender Contact Number</label>
                                <input type="number" class="form-control" id="sender_number" name="sender_number" value="">
                            </div>
                            <div class="form-group">
                                <label for="sender_email">Sender Email</label>
                                <input type="email" class="form-control" id="sender_email" name="sender_email" value="">
                            </div>
                            <div class="form-group">
                                <label for="sender_address">Full Address</label>
                                <textarea class="form-control" id="sender_address" name="sender_address"></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Update Details</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Receiver Details Modal -->
    <div class="modal fade" id="receiverDetails" tabindex="-1" role="dialog" aria-labelledby="receiverDetails" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Receiver Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="receiverForm">
                        <input type="hidden" name="orderId" id="receiver_orderId" value="">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="receiver_id">Order Id</label>
                                <input type="text" class="form-control" id="receiver_id" value="" disabled>
                            </div>
                            <div class="form-group">
                                <label for="receiver_name">Receiver Name</label>
                                <input type="text" class="form-control" id="receiver_name" name="receiver_name" value="">
                            </div>
                            <div class="form-group">
                                <label for="receiver_cnumber">Receiver Contact Number</label>
                                <input type="number" class="form-control" id="receiver_cnumber" name="receiver_cnumber" value="">
                            </div>
                            <div class="form-group">
                                <label for="receiver_email">Receiver Email</label>
                                <input type="email" class="form-control" id="receiver_email" name="receiver_email" value="">
                            </div>
                            <div class="form-group">
                                <label for="receiver_add">Full Address</label>
                                <textarea class="form-control" id="receiver_add" name="receiver_add"></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Update Details</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delivery Boy Order Status Modal -->
    <div class="modal fade" id="dBoy_orderStatus" tabindex="-1" role="dialog" aria-labelledby="dBoyLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dBoyLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="assignForm">
                        <input type="hidden" name="type" id="type" value="">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="orderIdedit">Order Id</label>
                                <input type="text" class="form-control" id="orderIdedit" name="orderIdedit" value="" readonly>
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

@push('scripts')
    <!-- DataTables & Plugins -->
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
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        $(document).ready(function() {
            // CSRF Token Setup
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Delete Order
            $(document).on("click", ".delete-btn", function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this order data!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('delete.orders.booking') }}",
                            data: { id: id },
                            dataType: "json",
                            success: function(response) {
                                if (response.success) {
                                    swal("Poof! Your order data has been deleted!", {
                                        icon: "success",
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    swal("Error!", response.message || "Unable to delete the data.", "error");
                                }
                            },
                            error: function(err) {
                                swal("Error!", "An unexpected error occurred. Please try again.", "error");
                            }
                        });
                    } else {
                        swal("Your order data is safe!");
                    }
                });
            });

            // Cancel Order
            $(document).on("click", ".cancel-order", function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                swal({
                    title: "Are you sure?",
                    text: "Do you really want to cancel this order?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willCancel) => {
                    if (willCancel) {
                        $.ajax({
                            type: "GET",
                            url: "{{ url('/booking-cancelled-order') }}/" + id,
                            dataType: "json",
                            success: function(response) {
                                if (response.success) {
                                    swal("Order cancelled successfully!", {
                                        icon: "success",
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    swal("Error!", response.message || "Unable to cancel the order.", "error");
                                }
                            },
                            error: function(err) {
                                swal("Error!", "An unexpected error occurred. Please try again.", "error");
                            }
                        });
                    } else {
                        swal("Order cancellation aborted!");
                    }
                });
            });

            // Assign Delivery Boy
            $(document).on("click", ".assign", function() {
                const id = $(this).data('id');
                $.ajax({
                    url: "{{ route('booking.assign.get') }}",
                    type: "POST",
                    data: { id: id },
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
                                    $('#deliverBoy').append('<option value="' + boy.id + '">' + boy.name + '</option>');
                                });
                            } else {
                                $('#deliverBoy').append('<option value="">No active delivery boys available</option>');
                            }
                        } else {
                            swal("Error!", response.message, "error");
                        }
                    },
                    error: function(err) {
                        swal("Error!", "An unexpected error occurred. Please try again.", "error");
                    }
                });
            });

            // Update Order Status
            $(document).on("click", ".status", function() {
                const id = $(this).data('id');
                $.ajax({
                    url: "{{ route('booking.status.get') }}",
                    type: "POST",
                    data: { id: id },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#dBoy_orderStatus').modal('show');
                            $('#type').val('OrderStatus');
                            $('#dBoyLabel').html('Order Status');
                            $('#orderIdedit').val(response.orderId);
                            $('#optionId').html('Select Order Status');
                            $('#btn').html('Update Order Status');
                            $('#deliverBoy').empty();
                            $('#deliverBoy').append(
                                "<option value='Booked'>Booked</option>" +
                                "<option value='Item Picked Up'>Item Picked Up</option>" +
                                "<option value='Returned'>Returned</option>" +
                                "<option value='In Transit'>In Transit</option>" +
                                "<option value='Arrived at Destination'>Arrived at Destination</option>" +
                                "<option value='Out for Delivery'>Out for Delivery</option>" +
                                "<option value='Delivered'>Delivered</option>" +
                                "<option value='Not Delivered'>Not Delivered</option>" +
                                "<option value='Returning to Origin'>Returning to Origin</option>" +
                                "<option value='Out for Delivery to Origin'>Out for Delivery to Origin</option>"
                            );
                        } else {
                            swal("Error!", response.message, "error");
                        }
                    },
                    error: function(err) {
                        swal("Error!", "An unexpected error occurred. Please try again.", "error");
                    }
                });
            });

            // Assign Form Submission
            $(document).on("submit", '#assignForm', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('booking.assign.add') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        $('#assignForm')[0].reset();
                        if (response.success) {
                            swal("Success!", response.message, "success");
                            $("#tbody").html(response.html);
                            $('#dBoy_orderStatus').modal('hide');
                        } else {
                            swal("Error!", response.message, "error");
                        }
                    },
                    error: function(err) {
                        swal("Error!", "An unexpected error occurred. Please try again.", "error");
                    }
                });
            });

            // Edit Receiver Details
            $(document).on("click", ".edit-receiver", function() {
                const id = $(this).data('id');
                $.ajax({
                    url: "{{ route('booking.edit.get') }}",
                    type: "POST",
                    data: { id: id },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#receiverDetails').modal('show');
                            $('#receiver_orderId').val(response.data.id);
                            $('#receiver_id').val(response.data.order_id);
                            $('#receiver_name').val(response.data.receiver_name);
                            $('#receiver_cnumber').val(response.data.receiver_cnumber);
                            $('#receiver_email').val(response.data.receiver_email || '');
                            $('#receiver_add').val(response.data.receiver_add || '');
                        } else {
                            swal("Error!", response.message, "error");
                        }
                    },
                    error: function(err) {
                        swal("Error!", "An unexpected error occurred. Please try again.", "error");
                    }
                });
            });

            // Update Receiver Details
            $(document).on("submit", '#receiverForm', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('booking.edit.update') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        $('#receiverForm')[0].reset();
                        if (response.success) {
                            swal("Success!", response.message, "success");
                            $('#receiverDetails').modal('hide');
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                        } else {
                            swal("Error!", response.message, "error");
                        }
                    },
                    error: function(err) {
                        swal("Error!", "An unexpected error occurred. Please try again.", "error");
                    }
                });
            });

            // Edit Sender Details
            $(document).on("click", ".edit-sender", function() {
                const id = $(this).data('id');
                $.ajax({
                    url: "{{ route('booking.edit.get') }}",
                    type: "POST",
                    data: { id: id },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#senderDetails').modal('show');
                            $('#sender_orderId').val(response.data.id);
                            $('#sender_id').val(response.data.order_id);
                            $('#sender_name').val(response.data.sender_name);
                            $('#sender_number').val(response.data.sender_number);
                            $('#sender_email').val(response.data.sender_email || '');
                            $('#sender_address').val(response.data.sender_address || '');
                        } else {
                            swal("Error!", response.message, "error");
                        }
                    },
                    error: function(err) {
                        swal("Error!", "An unexpected error occurred. Please try again.", "error");
                    }
                });
            });

            // Update Sender Details
            $(document).on("submit", '#senderForm', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('booking.edit.update') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        $('#senderForm')[0].reset();
                        if (response.success) {
                            swal("Success!", response.message, "success");
                            $('#senderDetails').modal('hide');
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                        } else {
                            swal("Error!", response.message, "error");
                        }
                    },
                    error: function(err) {
                        swal("Error!", "An unexpected error occurred. Please try again.", "error");
                    }
                });
            });
        });
    </script>
@endpush