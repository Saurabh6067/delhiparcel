@extends('deliveryBoy.layout.main')

@push('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <style>
        .timer {
            font-family: monospace;
            font-weight: bold;
        }
        .timer.running {
            color: #007bff; /* Blue for running timers under 3 hours */
        }
        .timer.expired {
            color: #dc3545; /* Red for timers exceeding 3 hours */
        }
        .timer.stopped {
            color: #28a745; /* Green for stopped timers (Delivered) */
        }
    </style>
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
                            <li class="breadcrumb-item"><a href="{{ url('/delivery-boy-dashboard') }}">Home</a></li>
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
                                        @php
                                            $todayOrders = ['toDayOrder', 'toDayCompleteOrder'];
                                            $totalOrders = [
                                                'totalOrder',
                                                'PendingOrder',
                                                'PendingSuperExpressOrder',
                                                'PendingDeliveryOrder',
                                                'DirectOrders',
                                                'totalCompleteOrder',
                                            ];
                                            $segment = request()->segment(2);
                                        @endphp

                                        @if (in_array($segment, $todayOrders))
                                            <h3 class="card-title">All Orders</h3>
                                        @elseif (in_array($segment, $totalOrders))
                                            <h3 class="card-title">Total Orders</h3>
                                        @endif
                                    </div>
                                    <div class="col-lg-6 text-right">
                                        <button class="btn btn-sm btn-light d-none" id="assignOrder">Assign Order</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Time Elapsed</th>
                                            <th>Order Id</th>
                                            <th>Order Date</ta>
                                            <th>Sender Name</th>
                                            <th>Receiver Name</th>
                                            <th>Pickup PinCode</th>
                                            <th>Delivery PinCode</th>
                                            <th>Action</th>
                                            <th>Order Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                        @foreach ($data as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td class="timer {{ $item->service_type == 'SuperExpress' && $item->order_status != 'Delivered' ? 'running' : ($item->order_status == 'Delivered' && $item->service_type == 'SuperExpress' ? 'stopped' : '') }}"
                                                    data-created-at="{{ $item->service_type == 'SuperExpress' ? $item->created_at : '' }}"
                                                    data-updated-at="{{ $item->service_type == 'SuperExpress' ? $item->updated_at : '' }}"
                                                    data-service-type="{{ $item->service_type }}"
                                                    data-order-status="{{ $item->order_status }}"
                                                    data-final-time="">
                                                    {{ $item->service_type == 'SuperExpress' ? 'Loading...' : '-' }}
                                                </td>
                                                <td>{{ $item->order_id }}</td>
                                                <td>{{ $item->datetime }}</td>
                                                <td>
                                                    <span><b>Name: </b>{{ $item->sender_name }}</span> <br>
                                                    <span><b>Number: </b>{{ $item->sender_number }}</span> <br>
                                                    <span><b>Email: </b>{{ $item->sender_email }}</span> <br>
                                                    <span><b>Address: </b>{{ $item->sender_address }}</span> <br>
                                                </td>
                                                <td>
                                                    <span><b>Name: </b>{{ $item->receiver_name }}</span> <br>
                                                    <span><b>Number: </b>{{ $item->receiver_cnumber }}</span> <br>
                                                    <span><b>Email: </b>{{ $item->receiver_email }}</span> <br>
                                                    <span><b>Address: </b>{{ $item->receiver_add }}</span> <br>
                                                </td>
                                                <td>{{ $item->sender_pincode ?? '' }}</td>
                                                <td>{{ $item->receiver_pincode ?? $item->receiverPinCode }}</td>
                                                <td>
                                                    @if ($item->order_status == 'Booked' || $item->order_status == 'Item Picked Up')
                                                        <a href="tel:{{ $item->sender_number }}" class="btn btn-sm btn-success">Call Now</a>
                                                    @else
                                                        <a href="tel:{{ $item->receiver_cnumber }}" class="btn btn-sm btn-success">Call Now</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item->order_status !== 'Delivered' && $item->order_status !== 'Cancelled' && $item->order_status !== 'Out for Delivery to Origin' )
                                                        <button class="btn btn-sm btn-warning status" data-id="{{ $item->id }}" title="{{ $item->status_message }}"
                                                            data-action="{{ request()->segment(2) }}">
                                                            <span class="font-weight-bold font-weight-light">
                                                                {{ $item->order_status }} | {{ $item->status_message ?? '-' }}
                                                            </span>
                                                        </button>
                                                    @elseif($item->order_status == 'Delivered')
                                                        <span class="badge badge-success" title="{{ $item->status_message }}">{{ $item->order_status }}
                                                            | {{ $item->status_message ?? '-' }}</span>
                                                    @elseif($item->order_status == 'Cancelled')
                                                        <span class="badge badge-danger" title="{{ $item->status_message }}">{{ $item->order_status }}
                                                            | {{ $item->status_message ?? '-' }}</span>
                                                    @else
                                                        <span class="badge badge-danger" title="{{ $item->status_message }}">{{ $item->order_status }}
                                                            | {{ $item->status_message ?? '-' }}</span>
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
    <!-- Moment.js for reliable date parsing -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script>
        $(function () {
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

        // Timer functions
        function formatTimeDifference(seconds) {
            const days = Math.floor(seconds / (3600 * 24));
            const hours = Math.floor((seconds % (3600 * 24)) / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = Math.floor(seconds % 60);
            return `${days}d ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }

        function updateTimers() {
            const now = moment();
            document.querySelectorAll('.timer').forEach(timerCell => {
                const serviceType = timerCell.getAttribute('data-service-type');
                const orderStatus = timerCell.getAttribute('data-order-status');
                const createdAtAttr = timerCell.getAttribute('data-created-at');
                const updatedAtAttr = timerCell.getAttribute('data-updated-at');
                let finalTime = timerCell.getAttribute('data-final-time');

                if (serviceType !== 'SuperExpress' || !createdAtAttr) {
                    timerCell.textContent = '-';
                    timerCell.classList.remove('running', 'expired', 'stopped');
                    return;
                }

                const createdAt = moment(createdAtAttr);
                if (!createdAt.isValid()) {
                    console.error('Invalid date format for created_at:', createdAtAttr);
                    timerCell.textContent = 'Invalid Date';
                    timerCell.classList.remove('running', 'expired', 'stopped');
                    return;
                }

                if (orderStatus === 'Delivered') {
                    if (!finalTime && updatedAtAttr) {
                        const updatedAt = moment(updatedAtAttr);
                        if (!updatedAt.isValid()) {
                            console.error('Invalid date format for updated_at:', updatedAtAttr);
                            timerCell.textContent = 'Invalid Date';
                            timerCell.classList.remove('running', 'expired', 'stopped');
                            return;
                        }
                        const diffSeconds = Math.floor(updatedAt.diff(createdAt) / 1000);
                        finalTime = diffSeconds >= 0 ? diffSeconds : 0;
                        timerCell.setAttribute('data-final-time', finalTime);
                    }
                    timerCell.classList.remove('running', 'expired');
                    timerCell.classList.add('stopped');
                    timerCell.textContent = finalTime ? formatTimeDifference(parseInt(finalTime)) : '0d 00:00:00';
                } else {
                    const diffSeconds = Math.floor(now.diff(createdAt) / 1000);
                    timerCell.setAttribute('data-final-time', '');
                    timerCell.classList.remove('stopped');
                    timerCell.classList.add('running');
                    if (diffSeconds > 10800) {
                        timerCell.classList.add('expired');
                    } else {
                        timerCell.classList.remove('expired');
                    }
                    timerCell.textContent = formatTimeDifference(diffSeconds);
                }
            });
        }

        $(document).ready(function () {
            // Initialize timers and update every second
            updateTimers();
            setInterval(updateTimers, 1000);

            $(document).on("click", ".status", function () {
                const id = $(this).data('id');
                const action = $(this).data('action');
                const currentStatus = $(this).find('span').text().trim().split('|')[0].trim();

                // Only proceed for specific statuses - "Delivered to branch" or "Out for Delivery to Origin"
                if (currentStatus !== 'Delivered to branch' && currentStatus !== 'Out for Delivery to Origin' && currentStatus !== 'Not Delivered') {
                    return; // Don't open modal for other statuses
                }

                $.ajax({
                    url: "{{ route('delivery.boy.status.get') }}",
                    type: "POST",
                    data: {
                        id: id,
                        action: action,
                        currentStatus: currentStatus // Send current status to backend
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            $('#dBoy_orderStatus').modal('show');
                            $('#assignForm')[0].reset();
                            $('#type').val('OrderStatus');
                            $('#dBoyLabel').html('Order Status');
                            $('#orderIdedit').val(response.orderId);
                            $('#optionId').html('Select Order Status');
                            $('#btn').html('Update Order Status');
                            $('#deliverBoy').empty();

                            // Check current status and provide appropriate options
                            if (currentStatus === 'Delivered to branch') {
                                // Add specific options for "Delivered to branch" status
                                $('#deliverBoy').append('<option selected disabled>Select</option>');
                                $('#deliverBoy').append('<option value="Out for Delivery to Origin">Out for Delivery to Origin</option>');
                            }
                            else if (currentStatus === 'Not Delivered') {
                                // Add specific options for "Delivered to branch" status
                                $('#deliverBoy').append('<option selected disabled>Select</option>');
                                $('#deliverBoy').append('<option value="Delivered">Delivered</option>');
                            }
                            else if (currentStatus === 'Out for Delivery to Origin') {
                                // Add specific options for "Out for Delivery to Origin" status
                                $('#deliverBoy').append('<option selected disabled>Select</option>');
                                $('#deliverBoy').append('<option value="Delivered">Delivered</option>');
                                $('#deliverBoy').append('<option value="Not Delivered">Not Delivered</option>');
                            }
                            else {
                                // Use the general status options from the backend
                                $('#deliverBoy').append(response.status);
                            }
                        } else {
                            Toast("error", response.message);
                        }
                    },
                    error: function (err) {
                        Toast("error",
                            "An unexpected error occurred. Please try again.");
                    }
                });
            });

            $(document).on("submit", "#assignForm", function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('delivery.boy.status.update') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function (response) {
                        $('#assignForm')[0].reset();
                        if (response.success) {
                            Toast("success", response.message);
                            $('#dBoy_orderStatus').modal('hide');
                            location.reload();
                        } else {
                            Toast("error", response.message);
                        }
                    },
                    error: function (err) {
                        Toast("error",
                            "An unexpected error occurred. Please try again.");
                    }
                });
            });

            $('#deliverBoy').change(function () {
                const value = $(this).val();
                if (value == 'Not Delivered' || value == 'Item Not Picked Up') {
                    $('#reason').removeClass('d-none');
                    $('#message').addClass('d-none');
                    $('#reason_msg').empty();

                    if (value == 'Not Delivered') {
                        $('#reason_msg').append(
                            '<option selected disabled>Select</option><option value="Incorrect Address">Incorrect Address</option><option value="Call Not Pickup">Call Not Pickup</option><option value="Incorrect Mobile No">Incorrect Mobile No</option><option value="Re Schedule">Re Schedule</option><option value="Cancel">Cancel</option><option value="Customer want open delivery">Customer want open delivery</option><option value="Mismatch in COD Amount">Mismatch in COD Amount</option>'
                        );
                    } else {
                        $('#reason_msg').append(
                            '<option selected disabled>Select</option> <option value="Parcel not ready">Parcel not ready</option> <option value="Address not found">Address not found</option>  <option value="Call not picked">Call not picked</option>  <option value="Incorrect mobile no">Incorrect mobile no</option>'
                        );
                    }
                } else {
                    $('#reason').addClass('d-none');
                    $('#message').removeClass('d-none');
                }
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
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="assignForm">
                        <input type="hidden" id="type" name="type" value="">
                        <input type="hidden" id="action" name="action" value="{{ request()->segment(2) }}">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="orderIdedit">Order Id</label>
                                <input type="text" class="form-control" id="orderIdedit" name="orderIdedit" value=""
                                    readonly>
                            </div>
                            <div class="form-group">
                                <label for="deliverBoy" id="optionId"></label>
                                <select class="custom-select rounded-0" id="deliverBoy" name="deliverBoy">
                                </select>
                            </div>
                            <div class="form-group d-none" id="reason">
                                <label for="reason_msg">Reason</label>
                                <select class="custom-select rounded-0" id="reason_msg" name="Reason_message">
                                </select>
                            </div>
                            <div class="form-group" id="message">
                                <label for="status_message">Message</label>
                                <textarea class="form-control" id="status_message" name="status_message"
                                    placeholder="Enter Message"></textarea>
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