@extends('delivery.layout.main')
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
                            <li class="breadcrumb-item"><a href="{{ url('/delivery-dashboard') }}">Home</a></li>
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
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h3 class="card-title">All Orders</h3>
                                    </div>
                                    <div class="col-lg-6 text-lg-right">
                                        <div class="form-group row">
                                            <label for="filterOrder" class="col-sm-6 col-form-label"> Filter Orders</label>
                                            <div class="col-sm-6">
                                                <select class="form-control" id="filterOrder">
                                                    <option selected disabled>Select</option>
                                                    <option value="ex">Express</option>
                                                    <option value="ss">Standard</option>
                                                    <option value="SuperExpress">Super Express</option>
                                                </select>
                                            </div>
                                        </div>

                                        @foreach ($data as $value)
                                            @if (empty($value->assign_to))
                                                <button class="btn btn-sm btn-light font-weight-bold btn-assign d-none"
                                                    id="assignOrder">Assign
                                                    Now</button>
                                            @endif
                                            @if (empty($value->sender_order_pin_by))
                                                <button class="btn btn-sm btn-light font-weight-bold btn-transfer d-none"
                                                    id="transferOrder">Transfer
                                                    Now</button>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>
                                                <label for="select-all-assign">Assign</label>
                                                <input type="checkbox" id="select-all-assign"
                                                    onclick="toggleCheckboxes(this, 'row-checkbox1', 'btn-assign')">
                                            </th>
                                            {{-- <th>
                                                <label for="select-all-transfer">Transfer</label>
                                                <input type="checkbox" id="select-all-transfer"
                                                    onclick="toggleCheckboxes(this, 'row-checkbox2', 'btn-transfer')">
                                            </th> --}}
                                            <th>Order Id</th>
                                            <th>Service Type</th>
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
                                            <th>Transfer Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                        @include('delivery.inc.otherTransferOrderDetails')
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
        function toggleCheckboxes(source, className, buttonClass) {
            document.querySelectorAll('.' + className).forEach(cb => cb.checked = source.checked);
            toggleButton(className, buttonClass);
        }

        function toggleButton(className, buttonClass) {
            document.querySelector('.' + buttonClass).classList.toggle('d-none',
                !document.querySelector('.' + className + ':checked'));
        }

        document.addEventListener("change", e => {
            if (e.target.classList.contains('row-checkbox1')) {
                toggleButton('row-checkbox1', 'btn-assign');
            } else if (e.target.classList.contains('row-checkbox2')) {
                toggleButton('row-checkbox2', 'btn-transfer');
            }
        });
    </script>


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
                    url: "{{ route('delivery.status.get') }}",
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
                    url: "{{ route('delivery.assign.get') }}",
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

            $(document).on("submit", "#assignForm", function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('delivery.assign.add') }}",
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

            $(document).on("click", "#assignOrder", function() {
                let selectedOrders = $(".row-checkbox1:checked").map(function() {
                    return $(this).data("id");
                }).get();
                // console.log(selectedOrders);
                $.ajax({
                    url: "{{ route('delivery.boy.get') }}",
                    type: "GET",
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#assignOrderData').modal('show');
                            $('#assignOrderLabel').html('Order Assign');
                            $('#orderId').val(selectedOrders);
                            $('#assignOrderOptionId').html('Select Delivery Boy');
                            $('#assignOrderBtn').html('Assign Now');
                            if (response.data.length > 0) {
                                response.data.forEach(function(boy) {
                                    $('#deliverBoyData').append('<option value="' + boy
                                        .id + '">' + boy.name + '</option>');
                                });
                            } else {
                                $('#deliverBoyData').append(
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

            $(document).on("submit", "#assignOrderForm", function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('delivery.assign.order') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function(response) {
                        $('#assignOrderForm')[0].reset();
                        if (response.success) {
                            Toast("success", response.message);
                            $('#assignOrderData').modal('hide');
                            // location.reload();
                        } else {
                            Toast("error", response.message);
                        }
                    }
                });
            });

            $(document).on("click", "#transferOrder", function() {
                let selectedOrders = $(".row-checkbox2:checked").map(function() {
                    return $(this).data("id");
                }).get();
                // console.log(selectedOrders);

                let url = window.location.href;
                let pinCodeFromUrl = url.split("/").pop();
                // console.log(pinCodeFromUrl);

                $.ajax({
                    url: "{{ route('delivery.transfer.boy.get') }}",
                    type: "GET",
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#transferOrderData').modal('show');
                            $('#transferOrderLabel').html('Order Assign');
                            $('#transferOrderId').val(selectedOrders);
                            $('#transferOrderOptionId').html('Select Delivery Boy');
                            $('#transferOrderBtn').html('Assign Now');
                            if (response.data.length > 0) {
                                response.data.forEach(function(boy) {
                                    $('#transferDeliverBoyData').append(
                                        '<option value="' + boy
                                        .id + '">' + boy.name + '</option>');
                                });
                                response.pinCode.forEach(function(pin) {
                                    let option = $('<option>', {
                                        value: pin.pincodes,
                                        text: pin.pincodes
                                    });

                                    // Auto-select if the pin code matches
                                    if (pin.pincodes == pinCodeFromUrl) {
                                        option.attr("selected", "selected");
                                    }

                                    $('#transferOrderPinCode').append(option);
                                });
                            } else {
                                $('#transferDeliverBoyData').append(
                                    '<option value="">No available</option>'
                                );
                                $('#transferOrderPinCode').append(
                                    '<option value="">No available</option>'
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

            $(document).on("submit", "#transferOrderForm", function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('delivery.transfer.assign.order') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function(response) {
                        $('#transferOrderForm')[0].reset();
                        if (response.success) {
                            Toast("success", response.message);
                            $('#transferOrderData').modal('hide');
                            location.reload();
                        } else {
                            Toast("error", response.message);
                        }
                    }
                });
            });

            $(document).on("change", "#filterOrder", function() {
                let filter = $(this).val();
                $.ajax({
                    url: "{{ route('delivery.order-pin-code-orders') }}",
                    type: "POST",
                    data: {
                        filter: filter
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $("#tbody").html(response.html);
                        } else {
                            Toast("error", response.message);
                        }
                    },
                    error: function(err) {
                        Toast("error", "Error. Please try again.");
                    }
                });
            });
        });
    </script>
@endpush
@push('modals')
    <!-- Modal dBoy -->
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
                        <input type="hidden" id="type" name="type" value="">
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
    <div class="modal fade" id="assignOrderData" tabindex="-1" role="dialog" aria-labelledby="assignOrderLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignOrderLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="assignOrderForm">
                        <input type="hidden" id="orderId" name="orderId" value="">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="deliverBoyData" id="assignOrderOptionId"></label>
                                <select class="custom-select rounded-0" id="deliverBoyData" name="deliverBoyData">

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status_message">Message</label>
                                <textarea class="form-control" id="status_message" name="status_message" placeholder="Enter Message"></textarea>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" id="assignOrderBtn"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="transferOrderData" tabindex="-1" role="dialog" aria-labelledby="transferOrderLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transferOrderLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="transferOrderForm">
                        <input type="hidden" id="transferOrderId" name="transferOrderId" value="">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="transferDeliverBoyData" id="transferOrderOptionId"></label>
                                <select class="custom-select rounded-0" id="transferDeliverBoyData"
                                    name="transferDeliverBoyData">

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="transferOrderPinCode">Delivered PinCode</label>
                                <select class="custom-select rounded-0" id="transferOrderPinCode"
                                    name="transferOrderPinCode">

                                </select>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" id="transferOrderBtn"></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush
