
@extends('delivery.layout.main')

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
                                <div class="row align-items-center">
                                    <div class="col-lg-4">
                                        @if (request()->segment(2) == 'toDayOrder' ||
                                             request()->segment(2) == 'toDayPendingOrder' ||
                                             request()->segment(2) == 'toDayOrderPicUp' ||
                                             request()->segment(2) == 'toDayCancelledOrder')
                                            <h3 class="card-title">Today Orders</h3>
                                        @elseif(request()->segment(2) == 'totalOrder' ||
                                                request()->segment(2) == 'totalPendingOrder' ||
                                                request()->segment(2) == 'totalOrderPicUp' ||
                                                request()->segment(2) == 'totalCompleteOrder' ||
                                                request()->segment(2) == 'totalCancelledOrder')
                                            <h3 class="card-title">Total Orders</h3>
                                        @elseif(request()->segment(2) == 'todayDeliveredToNearbyBranchOrders')
                                            <h3 class="card-title">Today Delivered to Nearby Branch Orders</h3>
                                        @elseif(request()->segment(2) == 'toDayMyBranchOrder')
                                            <h3 class="card-title">Today My Branch Orders</h3>
                                        @else
                                            <h3 class="card-title">Orders</h3>
                                        @endif
                                    </div>
                                    <div class="col-lg-4">
                                        @if (request()->segment(2) == 'todayDeliveredToNearbyBranchOrders' || request()->segment(2) == 'toDayMyBranchOrder')
                                            <div class="form-group mb-0">
                                                <select id="deliveryBoyFilter" class="form-control form-control-sm">
                                                    <option value="">All Delivery Boys</option>
                                                </select>
                                            </div>
                                        @else
                                            <div class="form-group mb-0"></div> <!-- Empty div to maintain layout -->
                                        @endif
                                    </div>
                                    <div class="col-lg-4 text-right">
                                        <button class="btn btn-sm btn-light d-none" id="assignOrder" 
                                                data-branch-pincodes="{{ Session::get('branch_pincodes') }}">Assign Order</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Time Elapsed</th>
                                            <th><input type="checkbox" id="select-all" onclick="toggleCheckboxes(this)"></th>
                                            <th>Label</th>
                                            <th>Order Id</th>
                                            <th>Order Date</th>
                                            <th>Order Type</th>
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
                                        @forelse ($data as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td class="timer {{ $item->service_type == 'SuperExpress' && $item->order_status != 'Delivered' ? 'running' : ($item->order_status == 'Delivered' && $item->service_type == 'SuperExpress' ? 'stopped' : '') }}"
                                                    data-created-at="{{ $item->service_type == 'SuperExpress' ? $item->created_at : '' }}"
                                                    data-updated-at="{{ $item->service_type == 'SuperExpress' ? $item->updated_at : '' }}"
                                                    data-service-type="{{ $item->service_type }}"
                                                    data-order-status="{{ $item->order_status }}"
                                                    data-final-time="">
                                                    {{ $item->service_type == 'SuperExpress' ? 'Loading...' : '-' }}
                                                </td>
                                                <td>
                                                    @if (empty($item->assign_to))
                                                        @if (!($item->transfer_other_branch == 'false' && !in_array($item->receiver_pincode, explode(',', trim(Session::get('branch_pincodes'), ',')))))
                                                            <input type="checkbox" class="row-checkbox" name="assign_order[]"
                                                                data-id="{{ $item->id }}">
                                                        @else
                                                            -
                                                        @endif
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ url('/delivery-label/' . $item->order_id) }}"
                                                        title="Order Label" target="_blank"
                                                        class="btn btn-sm btn-success">
                                                        <i class="fas fa-solid fa-file-invoice"></i>
                                                    </a>
                                                    <br/>
                                                    @if (request()->segment(2) == 'totaltodaytransferOrder')
                                                        <span style="display:none"> {{ $item->dlyBoy->name ?? '-' }}</span>
                                                    @else
                                                        <span> {{ $item->dlyBoy->name ?? '-' }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->order_id }}
                                                    <br>
                                                    @if ($item->order_status !== 'Delivered' && $item->order_status !== 'Cancelled')
                                                        <span class="badge badge-warning"
                                                              title="{{ $item->status_message ?? '' }}">{{ $item->order_status }}</span>
                                                    @elseif($item->order_status == 'Delivered')
                                                        <span class="badge badge-success"
                                                              title="{{ $item->status_message ?? '' }}">{{ $item->order_status }}</span>
                                                    @elseif($item->order_status == 'Cancelled')
                                                        <span class="badge badge-danger"
                                                              title="{{ $item->status_message ?? '' }}">{{ $item->order_status }}</span>
                                                    @else
                                                        <span class="badge badge-danger"
                                                              title="{{ $item->status_message ?? '' }}">{{ $item->order_status }}</span>
                                                    @endif
                                                    @if($item->status_message)
                                                        | <span style="font-size:14px">{{ $item->status_message }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->datetime }}</td>
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
                                                <td>{{ $item->codAmount ?? '0.00' }}</td>
                                                <td>{{ $item->payment_mode }}</td>
                                                <td>{{ $item->sender_pincode }}</td>
                                                <td>{{ $item->receiver_pincode }}</td>
                                                <td class="text-capitalize">{{ $item->parcel_type }}</td>
                                                <td>
                                                    @if ($item->order_status !== 'Delivered' && $item->order_status !== 'Cancelled')
                                                        <span class="badge badge-warning"
                                                              title="{{ $item->status_message ?? '' }}">{{ $item->order_status }}</span>
                                                    @elseif($item->order_status == 'Delivered')
                                                        <span class="badge badge-success"
                                                              title="{{ $item->status_message ?? '' }}">{{ $item->order_status }}</span>
                                                    @elseif($item->order_status == 'Cancelled')
                                                        <span class="badge badge-danger"
                                                              title="{{ $item->status_message ?? '' }}">{{ $item->order_status }}</span>
                                                    @else
                                                        <span class="badge badge-danger"
                                                              title="{{ $item->status_message ?? '' }}">{{ $item->order_status }}</span>
                                                    @endif
                                                    @if($item->status_message)
                                                        | <span style="font-size:14px">{{ $item->status_message }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($item->order_status == 'Delivered' || $item->order_status == 'Cancelled')
                                                        @if ($item->order_status == 'Cancelled')
                                                            <span class="badge badge-danger"
                                                                  title="{{ $item->status_message ?? '' }}">NA</span>
                                                        @else
                                                            <span class="badge badge-success"
                                                                  title="{{ $item->status_message ?? '' }}">{{ $item->dlyBoy->name ?? '-' }}</span>
                                                        @endif
                                                    @elseif ($item->order_status == 'Booked' || $item->order_status == 'Item Not Picked Up')
                                                        <button class="btn btn-sm btn-secondary assign"
                                                                data-id="{{ $item->id }}"
                                                                data-other-branch="{{ $item->transfer_other_branch }}"
                                                                title="{{ $item->status_message ?? '' }}">
                                                            @if ($item->assign_to)
                                                                <span class="font-weight-bold font-weight-light">
                                                                    {{ $item->dlyBoy->name ?? '-' }}
                                                                </span>
                                                            @else
                                                                Assign To
                                                            @endif
                                                        </button>
                                                    @elseif ($item->order_status == 'Item Picked Up')
                                                        <button class="btn btn-sm btn-secondary assign" disabled
                                                                data-id="{{ $item->id }}"
                                                                data-other-branch="{{ $item->transfer_other_branch }}"
                                                                title="{{ $item->status_message ?? '' }}">
                                                            @if ($item->assign_to)
                                                                <span class="font-weight-bold font-weight-light">
                                                                    {{ $item->dlyBoy->name ?? '-' }}
                                                                </span>
                                                            @else
                                                                Assign To
                                                            @endif
                                                        </button>
                                                    @else
                                                        @php
                                                            $branchPincodes = explode(',', trim(Session::get('branch_pincodes'), ','));
                                                            $isSenderBranch = in_array($item->sender_pincode, $branchPincodes);
                                                            $isReceiverBranch = in_array($item->receiver_pincode, $branchPincodes);
                                                            $isSameBranch = $isSenderBranch && $isReceiverBranch;
                                                        @endphp
                                                        @if ($isSameBranch || ($item->transfer_other_branch == 'true' && $isReceiverBranch))
                                                            <button class="btn btn-sm btn-secondary assign"
                                                                    data-id="{{ $item->id }}"
                                                                    data-other-branch="{{ $item->transfer_other_branch }}"
                                                                    title="{{ $item->status_message ?? '' }}">
                                                                @if ($item->assign_to)
                                                                    <span class="font-weight-bold font-weight-light">
                                                                        {{ $item->dlyBoy->name ?? '-' }}
                                                                    </span>
                                                                @else
                                                                    Assign To
                                                                @endif
                                                            </button>
                                                        @elseif ($isSenderBranch && !$isReceiverBranch)
                                                            <span class="badge badge-info">Transferred to other branch</span>
                                                        @else
                                                            <span class="badge badge-secondary">Not for this branch</span>
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="16" class="text-center">No orders found</td>
                                            </tr>
                                        @endforelse
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

        // Toggle checkboxes for selecting all rows
        function toggleCheckboxes(source) {
            document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = source.checked);
            toggleButton();
        }

        // Show/hide and enable/disable assign button based on checkbox selection
        function toggleButton() {
            const assignButton = document.querySelector('#assignOrder');
            const checkboxes = document.querySelectorAll('.row-checkbox:checked');
            const branchPincodes = assignButton.getAttribute('data-branch-pincodes')
                .split(',')
                .map(pin => pin.trim())
                .filter(pin => pin !== '');

            let shouldDisable = false;

            // Check each selected order
            checkboxes.forEach(checkbox => {
                const row = checkbox.closest('tr');
                const senderPincode = row.querySelector('td:nth-child(12)')?.textContent.trim(); // Pickup PinCode
                const receiverPincode = row.querySelector('td:nth-child(13)')?.textContent.trim(); // Delivery PinCode

                const isSenderBranch = branchPincodes.includes(senderPincode);
                const isReceiverBranch = branchPincodes.includes(receiverPincode);

                // Disable if any selected order is in sender branch but not in receiver branch
                if (isSenderBranch && !isReceiverBranch) {
                    shouldDisable = true;
                }
            });

            // Show/hide button based on checkbox selection
            assignButton.classList.toggle('d-none', checkboxes.length === 0);
            // Enable/disable button based on branch condition
            assignButton.disabled = shouldDisable;
        }

        // Initialize DataTable
        $(function () {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        // Populate delivery boy dropdown
        function populateDeliveryBoyDropdown() {
            $.ajax({
                url: "{{ route('delivery.boy.get') }}",
                type: "GET",
                dataType: 'json',
                success: function (response) {
                    if (response.success && response.data.length > 0) {
                        const select = $('#deliveryBoyFilter');
                        select.empty();
                        select.append('<option value="">All Delivery Boys</option>');
                        response.data.forEach(boy => {
                            select.append(`<option value="${boy.id}">${boy.name}</option>`);
                        });
                    } else {
                        $('#deliveryBoyFilter').html('<option value="">No delivery boys available</option>');
                    }
                },
                error: function (err) {
                    Toast("error", "Failed to load delivery boys.");
                }
            });
        }

        // Filter orders by delivery boy
        function filterOrdersByDeliveryBoy(deliveryBoyId) {
            $.ajax({
                url: "{{ route('delivery.orders.by.boy') }}",
                type: "POST",
                data: {
                    deliveryBoyId: deliveryBoyId,
                    segment: "{{ request()->segment(2) }}",
                    _token: "{{ csrf_token() }}"
                },
                dataType: 'json',
                success: function (response) {
                    // Log response for debugging
                    console.log('AJAX Response:', response);

                    if (response.success) {
                        // Destroy existing DataTable to prevent conflicts
                        if ($.fn.DataTable.isDataTable('#example1')) {
                            $('#example1').DataTable().destroy();
                        }

                        // Clear the table body
                        $('#tbody').empty();

                        if (response.data_count === 0 || !response.html || response.html.trim() === '') {
                            // No orders found
                            $('#tbody').html('<tr><td colspan="16" class="text-center">No orders found</td></tr>');
                            Toast("info", `No orders found for this delivery boy. (${response.data_count} orders)`);
                        } else {
                            // Parse the HTML response
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = response.html;

                            // Extract tbody content
                            const tbodyContent = tempDiv.querySelector('#tbody')?.innerHTML;
                            console.log('Extracted tbodyContent:', tbodyContent);

                            // Update table body
                            if (tbodyContent && tbodyContent.trim() !== '') {
                                $('#tbody').html(tbodyContent);
                                Toast("success", "Orders filtered successfully.");
                            } else {
                                $('#tbody').html('<tr><td colspan="16" class="text-center">No orders found</td></tr>');
                                Toast("info", `No orders found for this delivery boy. (${response.data_count} orders)`);
                            }
                        }

                        // Update card title based on segment
                        const segment = "{{ request()->segment(2) }}";
                        const cardTitle = $('.card-title');
                        if (segment === 'todayDeliveredToNearbyBranchOrders') {
                            cardTitle.text('Today Delivered to Nearby Branch Orders');
                        } else if (segment === 'toDayMyBranchOrder') {
                            cardTitle.text('Today My Branch Orders');
                        }

                        // Reinitialize DataTable
                        $("#example1").DataTable({
                            "responsive": true,
                            "lengthChange": false,
                            "autoWidth": false,
                            "buttons": ["copy", "excel", "pdf", "print"]
                        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

                        // Initialize timers
                        updateTimers();
                    } else {
                        $('#tbody').html('<tr><td colspan="16" class="text-center">No orders found</td></tr>');
                        Toast("error", response.message || "No orders found for this delivery boy.");
                    }
                },
                error: function (err) {
                    console.error('AJAX Error:', err);
                    $('#tbody').html('<tr><td colspan="16" class="text-center">No orders found</td></tr>');
                    Toast("error", "Failed to load orders.");
                }
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
            // Populate dropdown only for allowed segments
            const segment = "{{ request()->segment(2) }}";
            if (segment === 'todayDeliveredToNearbyBranchOrders' || segment === 'toDayMyBranchOrder') {
                populateDeliveryBoyDropdown();
            }

            // Initialize timers and update every second
            updateTimers();
            setInterval(updateTimers, 1000);

            // Handle dropdown change only for allowed segments
            $('#deliveryBoyFilter').on('change', function () {
                const segment = "{{ request()->segment(2) }}";
                if (segment === 'todayDeliveredToNearbyBranchOrders' || segment === 'toDayMyBranchOrder') {
                    const deliveryBoyId = $(this).val();
                    filterOrdersByDeliveryBoy(deliveryBoyId);
                }
            });

            // Checkbox change event
            $(document).on("change", ".row-checkbox", function () {
                toggleButton();
            });

            $(document).on("click", ".status", function () {
                const id = $(this).data('id');
                $.ajax({
                    url: "{{ route('delivery.status.get') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
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
                            $('#deliverBoy').append(response.status);
                        } else {
                            Toast("error", response.message);
                        }
                    },
                    error: function (err) {
                        Toast("error", "An unexpected error occurred. Please try again.");
                    }
                });
            });

            $(document).on("click", ".assign", function () {
                const id = $(this).data('id');
                const isOtherBranch = $(this).data('other-branch') === 'true';
                $.ajax({
                    url: "{{ route('delivery.assign.get') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            $('#dBoy_orderStatus').modal('show');
                            $('#dBoyLabel').html(isOtherBranch ? 'Transfer & Assign Order' : 'Order Assign');
                            $('#orderIdedit').val(response.orderId);
                            $('#optionId').html('Select Delivery Boy');
                            $('#btn').html('Assign Now');
                            $('#deliverBoy').empty();
                            if (response.data.length > 0) {
                                response.data.forEach(function (boy) {
                                    $('#deliverBoy').append('<option value="' + boy.id + '">' + boy.name + '</option>');
                                });
                            } else {
                                $('#deliverBoy').append('<option value="">No active delivery boys available</option>');
                            }
                        } else {
                            Toast("error", response.message);
                        }
                    },
                    error: function (err) {
                        Toast("error", "An unexpected error occurred. Please try again.");
                    }
                });
            });

            $(document).on("submit", "#assignForm", function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('delivery.assign.add') }}",
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
                        Toast("error", "An unexpected error occurred. Please try again.");
                    }
                });
            });

            $(document).on("click", "#assignOrder", function () {
                let selectedOrders = $(".row-checkbox:checked").map(function () {
                    return $(this).data("id");
                }).get();
                $.ajax({
                    url: "{{ route('delivery.boy.get') }}",
                    type: "GET",
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            $('#assignOrderData').modal('show');
                            $('#assignOrderLabel').html('Order Assign');
                            $('#orderId').val(selectedOrders);
                            $('#assignOrderOptionId').html('Select Delivery Boy');
                            $('#assignOrderBtn').html('Assign Now');
                            $('#deliverBoyData').empty();
                            if (response.data.length > 0) {
                                response.data.forEach(function (boy) {
                                    $('#deliverBoyData').append('<option value="' + boy.id + '">' + boy.name + '</option>');
                                });
                            } else {
                                $('#deliverBoyData').append('<option value="">No active delivery boys available</option>');
                            }
                        } else {
                            Toast("error", response.message);
                        }
                    },
                    error: function (err) {
                        Toast("error", "An unexpected error occurred. Please try again.");
                    }
                });
            });

            $(document).on("submit", "#assignOrderForm", function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('delivery.assign.order.new') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function (response) {
                        $('#assignOrderForm')[0].reset();
                        if (response.success) {
                            Toast("success", response.message);
                            $('#assignOrderData').modal('hide');
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        } else {
                            Toast("error", response.message);
                        }
                    },
                    error: function (err) {
                        Toast("error", "An unexpected error occurred. Please try again.");
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
                            '<option selected disabled>Select</option><option value="Parcel not ready">Parcel not ready</option><option value="Address not found">Address not found</option><option value="Call not picked">Call not picked</option><option value="Incorrect mobile no">Incorrect mobile no</option>'
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
                                <input type="text" class="form-control" id="orderIdedit" name="orderIdedit"
                                    value="" readonly>
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

    <!-- Modal for Assigning Multiple Orders -->
    <div class="modal fade" id="assignOrderData" tabindex="-1" role="dialog" aria-labelledby="assignOrderLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignOrderLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
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
                                <textarea class="form-control" id="status_message" name="status_message"
                                    placeholder="Enter Message"></textarea>
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
@endpush
