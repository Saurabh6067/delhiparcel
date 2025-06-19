
@extends('admin.layout.main')
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
                                <!-- Time Elapsed Filter Dropdown and Buttons -->
                                <div class="form-group mb-3">
                                    <label for="timeFilter">Filter by Time Elapsed</label>
                                    <div class="d-flex align-items-center">
                                        <select id="timeFilter" class="form-control" style="width: 200px; margin-right: 10px;">
                                            <option value="all">All Orders</option>
                                            <option value="0-1">0-1 Hour</option>
                                            <option value="1-2">1-2 Hours</option>
                                            <option value="2-3">2-3 Hours</option>
                                            <option value="3-4">3-4 Hours</option>
                                            <option value="beyond-4">Beyond 4 Hours</option>
                                        </select>
                                        <button id="applyFilter" class="btn btn-primary btn-sm">Apply Filter</button>
                                        <button id="clearFilter" class="btn btn-secondary btn-sm ml-2">Clear Filter</button>
                                    </div>
                                </div>
                                <table id="example1" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Time Elapsed</th>
                                            <th>Label</th>
                                            <th>Order Id</th>
                                            <th>Order Date</th>
                                            <th>Sender Name</th>
                                            <th>Receiver Name</th>
                                            <th>Shipping Charge</th>
                                            <th>COD Amount</th>
                                            <th>Payment Status</th>
                                            <th>Order Type</th>
                                            <th>Pickup PinCode</th>
                                            <th>Delivery PinCode</th>
                                            <th>Service Type</th>
                                            <th>Order Status</th>
                                            <th>Assign Order</th>
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
                                                <td class="timer {{ $item->service_type == 'SuperExpress' && $item->order_status != 'Delivered' && $item->order_status != 'Cancelled' ? 'running' : ($item->service_type == 'SuperExpress' && ($item->order_status == 'Delivered' || $item->order_status == 'Cancelled') ? 'stopped' : '') }}"
                                                    data-created-at="{{ $item->service_type == 'SuperExpress' ? $item->created_at : '' }}"
                                                    data-updated-at="{{ $item->service_type == 'SuperExpress' ? $item->updated_at : '' }}"
                                                    data-service-type="{{ $item->service_type }}"
                                                    data-order-status="{{ $item->order_status }}"
                                                    data-final-time="">
                                                    {{ $item->service_type == 'SuperExpress' ? 'Loading...' : '-' }}
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ url('/admin-label/' . $item->order_id) }}" title="Order Label" target="_blank"
                                                        class="btn btn-sm btn-success">
                                                        <i class="fas fa-solid fa-file-invoice"></i>
                                                    </a>
                                                    <br/>
                                                    {{ $item->dlyBoy->name ?? '-' }}
                                                </td>
                                                <td>
                                                    {{ $item->order_id }}
                                                    <br>
                                                    @if ($item->order_status !== 'Delivered' && $item->order_status !== 'Cancelled')
                                                        <button class="btn btn-sm btn-warning status" data-id="{{ $item->id }}"
                                                            title="{{ $item->status_message }}">
                                                            <span class="font-weight-bold font-weight-light">
                                                                {{ $item->order_status }}
                                                            </span>
                                                        </button>
                                                    @elseif($item->order_status == 'Delivered')
                                                        <span class="badge badge-success" title="{{ $item->status_message }}">{{ $item->order_status }}</span>
                                                    @elseif($item->order_status == 'Cancelled')
                                                        <span class="badge badge-danger" title="{{ $item->status_message }}">{{ $item->order_status }}</span>
                                                    @else
                                                        <span class="badge badge-danger" title="{{ $item->status_message }}">{{ $item->order_status }}</span>
                                                    @endif
                                                    | <span style="font-size:14px">{{ $item->status_message ?? '' }}</span>
                                                </td>
                                                <td>{{ $item->datetime }}</td>
                                                <td>
                                                    <span><b>Name: </b>{{ $item->sender_name ?? '-' }}</span> <br>
                                                    <span><b>Email: </b>{{ $item->sender_email ?? '-' }}</span> <br>
                                                    <span><b>Number: </b>{{ $item->sender_number ?? '-' }}</span> <br>
                                                    <span><b>Address: </b>{{ $item->sender_address ?? '-' }}</span> <br>
                                                </td>
                                                <td>
                                                    <span><b>Name: </b>{{ $item->receiver_name ?? '-' }}</span> <br>
                                                    <span><b>Email: </b>{{ $item->receiver_email ?? '-' }}</span> <br>
                                                    <span><b>Number: </b>{{ $item->receiver_cnumber ?? '-' }}</span> <br>
                                                    <span><b>Address: </b>{{ $item->receiver_add ?? '-' }}</span> <br>
                                                </td>
                                                <td>{{ $item->price }}</td>
                                                <td>{{ $item->codAmount }}</td>
                                                <td>
                                                    @if($item->payment_mode == 'online')
                                                        Prepaid
                                                    @elseif($item->payment_mode == 'COD')
                                                        COD
                                                    @else
                                                        Unknown
                                                    @endif
                                                </td>
                                                <td class="text-capitalize">{{ $item->parcel_type ?? '-' }}</td>
                                                <td>{{ $item->sender_pincode ?? '-' }}</td>
                                                <td>{{ $item->receiver_pincode ?? '-' }}</td>
                                                <td>
                                                    @if ($item->service_type == 'ex' || $item->service_type == 'stex')
                                                        Express
                                                    @elseif ($item->service_type == 'ss' || $item->service_type == 'stss')
                                                        Standard
                                                    @elseif ($item->service_type == 'SuperExpress')
                                                        SuperExpress
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item->order_status !== 'Delivered' && $item->order_status !== 'Cancelled')
                                                        <button class="btn btn-sm btn-warning status" data-id="{{ $item->id }}"
                                                            title="{{ $item->status_message }}">
                                                            <span class="font-weight-bold font-weight-light">
                                                                {{ $item->order_status }}
                                                            </span>
                                                        </button>
                                                    @elseif($item->order_status == 'Delivered')
                                                        <span class="badge badge-success" title="{{ $item->status_message }}">{{ $item->order_status }}</span>
                                                    @elseif($item->order_status == 'Cancelled')
                                                        <span class="badge badge-danger" title="{{ $item->status_message }}">{{ $item->order_status }}</span>
                                                    @else
                                                        <span class="badge badge-danger" title="{{ $item->status_message }}">{{ $item->order_status }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item->order_status == 'Delivered' || $item->order_status == 'Cancelled')
                                                        @if ($item->order_status == 'Cancelled')
                                                            <span class="badge badge-danger" title="{{ $item->status_message }}">NA</span>
                                                        @else
                                                            <span class="badge badge-success"
                                                                title="{{ $item->status_message }}">{{ $item->dlyBoy->name ?? '-' }}</span>
                                                        @endif
                                                    @else
                                                        <button class="btn btn-sm btn-secondary assign" data-id="{{ $item->id }}"
                                                            title="{{ $item->status_message }}">
                                                            @if ($item->assign_to)
                                                                <span class="font-weight-bold font-weight-light">
                                                                    {{ $item->dlyBoy->name ?? '-' }}
                                                                </span>
                                                            @else
                                                                Assign To
                                                            @endif
                                                        </button>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger delete-order" data-id="{{ $item->id }}"
                                                        title="Delete Order">
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
    <!-- jQuery (Ensure loaded first) -->
    <script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
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
    <!-- SweetAlert2 for Toast notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Initialize Toast for notifications
        function Toast(type, message) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            Toast.fire({
                icon: type,
                title: message
            });
        }

        // Timer functions
        function formatTimeDifference(seconds) {
            const days = Math.floor(seconds / (3600 * 24));
            const hours = Math.floor((seconds % (3600 * 24)) / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = Math.floor(seconds % 60);
            return `${days}d ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }

        function updateTimers(table) {
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
                    timerCell.setAttribute('data-diff-seconds', '0');
                    return;
                }

                const createdAt = moment(createdAtAttr);
                if (!createdAt.isValid()) {
                    console.error('Invalid date format for created_at:', createdAtAttr);
                    timerCell.textContent = 'Invalid Date';
                    timerCell.classList.remove('running', 'expired', 'stopped');
                    timerCell.setAttribute('data-diff-seconds', '0');
                    return;
                }

                if (orderStatus === 'Delivered' || orderStatus === 'Cancelled') {
                    if (!finalTime && updatedAtAttr) {
                        const updatedAt = moment(updatedAtAttr);
                        if (!updatedAt.isValid()) {
                            console.error('Invalid date format for updated_at:', updatedAtAttr);
                            timerCell.textContent = 'Invalid Date';
                            timerCell.classList.remove('running', 'expired', 'stopped');
                            timerCell.setAttribute('data-diff-seconds', '0');
                            return;
                        }
                        const diffSeconds = Math.floor(updatedAt.diff(createdAt) / 1000);
                        finalTime = diffSeconds >= 0 ? diffSeconds : 0;
                        timerCell.setAttribute('data-final-time', finalTime);
                        timerCell.setAttribute('data-diff-seconds', finalTime);
                    }
                    timerCell.classList.remove('running', 'expired');
                    timerCell.classList.add('stopped');
                    timerCell.textContent = finalTime ? formatTimeDifference(parseInt(finalTime)) : '0d 00:00:00';
                } else {
                    const diffSeconds = Math.floor(now.diff(createdAt) / 1000);
                    timerCell.setAttribute('data-final-time', '');
                    timerCell.setAttribute('data-diff-seconds', diffSeconds);
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

        $(document).ready(function() {
            // Initialize DataTable
            let table = null;
            try {
                table = $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "excel", "pdf", "print"],
                    "initComplete": function() {
                        console.log('DataTable initialized');
                        updateTimers(table); // Initial timer update
                        setInterval(function() { updateTimers(table); }, 1000); // Update timers every second
                    }
                });
                table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            } catch (e) {
                console.error('DataTable initialization failed:', e);
                Toast('error', 'Failed to initialize table. Please refresh the page.');
                return;
            }

            // Custom DataTable filter for time elapsed
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                if (!table) return true; // Fallback if table is not initialized
                const selectedRange = $('#timeFilter').val();
                const timerCell = $(table.row(dataIndex).node()).find('.timer');
                const serviceType = timerCell.data('service-type');
                const diffSeconds = parseInt(timerCell.attr('data-diff-seconds')) || 0;

                console.log('Filtering:', { selectedRange, serviceType, diffSeconds }); // Debug

                if (serviceType !== 'SuperExpress') {
                    return selectedRange === 'all';
                }

                if (selectedRange === 'all') {
                    return true;
                } else if (selectedRange === '0-1' && diffSeconds >= 0 && diffSeconds < 3600) {
                    return true;
                } else if (selectedRange === '1-2' && diffSeconds >= 3600 && diffSeconds < 7200) {
                    return true;
                } else if (selectedRange === '2-3' && diffSeconds >= 7200 && diffSeconds < 10800) {
                    return true;
                } else if (selectedRange === '3-4' && diffSeconds >= 10800 && diffSeconds < 14400) {
                    return true;
                } else if (selectedRange === 'beyond-4' && diffSeconds >= 14400) {
                    return true;
                }
                return false;
            });

            // Apply filter button click event
            $('#applyFilter').on('click', function() {
                if (table && typeof table.draw === 'function') {
                    table.draw();
                    Toast('info', 'Filter applied: ' + $('#timeFilter').find('option:selected').text());
                } else {
                    console.error('Table is not initialized or draw is not a function');
                    Toast('error', 'Table not initialized. Please refresh the page.');
                }
            });

            // Clear filter button click event
            $('#clearFilter').on('click', function() {
                if (table && typeof table.draw === 'function') {
                    $('#timeFilter').val('all');
                    table.draw();
                    Toast('info', 'Filter cleared');
                } else {
                    console.error('Table is not initialized or draw is not a function');
                    Toast('error', 'Table not initialized. Please refresh the page.');
                }
            });

            // Status click handler
            $(document).on("click", ".status", function() {
                const id = $(this).data('id');
                $.ajax({
                    url: "{{ route('admin.status.get') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
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
                        Toast("error", "An unexpected error occurred. Please try again.");
                    }
                });
            });

            // Assign click handler
            $(document).on("click", ".assign", function() {
                const id = $(this).data('id');
                $.ajax({
                    url: "{{ route('admin.assign.get') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
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
                                    $('#deliverBoy').append('<option value="' + boy.id + '">' + boy.name + '</option>');
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
                        Toast("error", "An unexpected error occurred. Please try again.");
                    }
                });
            });

            // Assign form submit handler
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
                            location.reload();
                        } else {
                            Toast("error", response.message);
                        }
                    },
                    error: function(err) {
                        Toast("error", "An unexpected error occurred. Please try again.");
                    }
                });
            });

            // Delete order click handler
            $(document).on("click", ".delete-order", function() {
                const id = $(this).data('id');
                $('#deleteOrderId').val(id);
                $('#Orders').modal('show');
            });

            // Delete form submit handler
            $(document).on("submit", '#deleteOrderForm', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('admin.delete.order') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        $('#deleteOrderForm')[0].reset();
                        if (response.success) {
                            Toast("success", response.message);
                            $('#Orders').modal('hide');
                            location.reload();
                        } else {
                            Toast("error", response.message);
                        }
                    },
                    error: function(err) {
                        Toast("error", "An unexpected error occurred.");
                    }
                });
            });
        });
    </script>
@endpush
@push('modals')
    <!-- Modal for Order Status/Assign -->
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
                        <input type="hidden" name="type" id="type" value="">
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

    <!-- Modal for Delete Confirmation -->
    <div class="modal fade" id="Orders" tabindex="-1" role="dialog" aria-labelledby="deleteOrderLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteOrderLabel">Delete Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="deleteOrderForm">
                        <input type="hidden" name="id" id="deleteOrderId" value="">
                        <p>Are you sure you want to delete this order?</p>
                        <div class="card-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush
