@extends('delivery.layout.main')

@push('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endpush

@section('main')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Other Branch Order Details</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/delivery-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Other Branch Order Details</li>
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
                                <div class="form-group">
                                    <label>Select Delivery Boy</label>
                                    <select class="form-control" name="deliverBoyData" id="header_delivery_boy_id" required>
                                        <option selected="true" disabled="true">Select Delivery Boy</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h3 class="card-title">All Orders</h3>
                                    </div>
                                    <div class="col-lg-6 text-right">
                                        <button class="btn btn-primary btn-sm" id="assignOrder" style="display: none;">
                                            Assign Orders
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="select-all">
                                            </th>
                                            <th>#</th>
                                            <th>Pin Code</th>
                                            <th>Order ID</th>
                                            <th>Receiver Details</th>
                                            <th>Sender Details</th>
                                            <th>Payment Info</th>
                                            <th>Created At</th>
                                            <th>Assignment Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $key => $orderData)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="order-checkbox"
                                                        data-id="{{ $orderData->id }}" name="order_ids[]">
                                                </td>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $orderData->receiver_pincode ?? 'N/A' }}</td>
                                                <td>{{ $orderData->order_id ?? 'N/A' }}</td>
                                                <td>
                                                    <strong>Name:</strong>
                                                    {{ $orderData->receiver_name ?? $orderData->sender_name ?? 'N/A' }}<br>
                                                    <strong>Phone:</strong>
                                                    {{ $orderData->receiver_cnumber ?? $orderData->sender_number ?? 'N/A' }}<br>
                                                    <strong>Email:</strong>
                                                    {{ $orderData->receiver_email ?? $orderData->sender_email ?? 'N/A' }}<br>
                                                    <strong>Address:</strong>
                                                    {{ $orderData->receiver_add ?? $orderData->sender_address ?? 'N/A' }}
                                                </td>
                                                 <td>
                                                    <strong>Name:</strong>
                                                    {{ $orderData->sender_name ?? $orderData->sender_name ?? 'N/A' }}<br>
                                                    <strong>Phone:</strong>
                                                    {{ $orderData->sender_number ?? $orderData->sender_number ?? 'N/A' }}<br>
                                                    <strong>Email:</strong>
                                                    {{ $orderData->sender_email ?? $orderData->sender_email ?? 'N/A' }}<br>
                                                    <strong>Address:</strong>
                                                    {{ $orderData->sender_add ?? $orderData->sender_address ?? 'N/A' }}
                                                </td>
                                                <td>
                                                    <strong>Mode:</strong> {{ $orderData->payment_mode ?? 'N/A' }}<br>
                                                    <strong>COD:</strong> ₹{{ $orderData->codAmount ?? '0' }}<br>
                                                    <strong>Insurance:</strong>
                                                    @if(isset($orderData->insurance))
                                                        {{ $orderData->insurance ? 'Yes' : 'No' }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(isset($orderData->created_at))
                                                        {{ \Carbon\Carbon::parse($orderData->created_at)->format('d-m-Y H:i:s') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (!empty($orderData->assign_to))
                                                        <span class="badge badge-success">Assigned</span>
                                                        <br>
                                                        <small><strong>To:</strong> {{ $orderData->deliveryBoy->name ?? 'Unknown' }}</small>
                                                        <br>
                                                        <button class="btn btn-warning btn-sm mt-1 reassign-btn"
                                                            data-id="{{ $orderData->id }}"
                                                            data-current="{{ $orderData->assign_to }}">
                                                            Reassign
                                                        </button>
                                                    @else
                                                        <span class="badge badge-secondary">Unassigned</span>
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

    <!-- Modal for Assigning Delivery Boy -->
    <div class="modal fade" id="assignOrderModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Orders to Delivery Boy</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="assignOrderForm">
                        <input type="hidden" name="orderId" id="selected_order_ids">
                        <div class="form-group">
                            <label>Select Delivery Boy</label>
                            <select class="form-control" name="deliverBoyData" id="modal_delivery_boy_id" required>
                                <option value="">Select Delivery Boy</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="confirmAssign">Assign</button>
                </div>
            </div>
        </div>
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
    <!-- SweetAlert2 -->
    <script src="{{ asset('admin/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(function () {
            // Initialize DataTable
            let table = $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            // Initialize Toast
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            // Load Delivery Boys into Card Header Dropdown
            function loadHeaderDeliveryBoys() {
                $.ajax({
                    url: "{{ route('delivery.boy.get') }}",
                    type: "GET",
                    success: function (response) {
                        if (response.success) {
                            let options = '<option value="">Select Delivery Boy</option>';
                            response.data.forEach(function (boy) {
                                options += `<option value="${boy.id}">${boy.name}</option>`;
                            });
                            $('#header_delivery_boy_id').html(options);
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: "Failed to load delivery boys"
                            });
                        }
                    },
                    error: function () {
                        Toast.fire({
                            icon: 'error',
                            title: "Server error while loading delivery boys"
                        });
                    }
                });
            }

            // Load Delivery Boys into Modal Dropdown
            function loadModalDeliveryBoys(selectedIds, preSelectedDeliveryBoy = null) {
                $('#selected_order_ids').val(selectedIds.join(','));

                $.ajax({
                    url: "{{ route('delivery.boy.get') }}",
                    type: "GET",
                    success: function (response) {
                        if (response.success) {
                            let options = '<option value="">Select Delivery Boy</option>';
                            response.data.forEach(function (boy) {
                                let selected = (preSelectedDeliveryBoy && preSelectedDeliveryBoy == boy.id) ? 'selected' : '';
                                options += `<option value="${boy.id}" ${selected}>${boy.name}</option>`;
                            });
                            $('#modal_delivery_boy_id').html(options);
                            $('#assignOrderModal').modal('show');
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: "Failed to load delivery boys"
                            });
                        }
                    },
                    error: function () {
                        Toast.fire({
                            icon: 'error',
                            title: "Server error while loading delivery boys"
                        });
                    }
                });
            }

            // Load Orders by Delivery Boy Pincode Delivery Boy Filter Function 2 june 
            function loadOrdersByDeliveryBoy(deliveryBoyId) {
                $.ajax({
                    url: "{{ route('delivery.orders.by.delivery.boy', ['branchId' => $delivery->id]) }}",
                    type: "GET",
                    data: { deliveryBoyId: deliveryBoyId || '' },
                    success: function (response) {
                        console.log(response);              
                        // Check if response.data is an array
                        if (response && response.data && Array.isArray(response.data)) {
                            let tableRows = '';
                            response.data.forEach((orderData, index) => {
                                // Build the table row HTML
                                tableRows += `
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="order-checkbox" data-id="${orderData.id || ''}" name="order_ids[]">
                                        </td>
                                        <td>${index + 1}</td>
                                        <td>${orderData.receiver_pincode || 'N/A'}</td>
                                        <td>${orderData.order_id || 'N/A'}</td>
                                        <td>
                                            <strong>Name:</strong> ${orderData.receiver_name || orderData.sender_name || 'N/A'}<br>
                                            <strong>Phone:</strong> ${orderData.receiver_cnumber || orderData.sender_number || 'N/A'}<br>
                                            <strong>Email:</strong> ${orderData.receiver_email || orderData.sender_email || 'N/A'}<br>
                                            <strong>Address:</strong> ${orderData.receiver_add || orderData.sender_address || 'N/A'}
                                        </td>
                                        <td>
                                            <strong>Name:</strong> ${orderData.sender_name || 'N/A'}<br>
                                            <strong>Phone:</strong> ${orderData.sender_number || 'N/A'}<br>
                                            <strong>Email:</strong> ${orderData.sender_email || 'N/A'}<br>
                                            <strong>Address:</strong> ${orderData.sender_add || orderData.sender_address || 'N/A'}
                                        </td>
                                        <td>
                                            <strong>Mode:</strong> ${orderData.payment_mode || 'N/A'}<br>
                                            <strong>COD:</strong> ₹${orderData.cod_amount || '0'}<br>
                                            <strong>Insurance:</strong> ${orderData.insurance !== undefined ? (orderData.insurance ? 'Yes' : 'No') : 'N/A'}
                                        </td>
                                        <td>
                                            ${orderData.created_at ? new Date(orderData.created_at).toLocaleString('en-IN', {
                                                day: '2-digit',
                                                month: '2-digit',
                                                year: 'numeric',
                                                hour: '2-digit',
                                                minute: '2-digit',
                                                second: '2-digit'
                                            }) : 'N/A'}
                                        </td>
                                        <td>
                                            ${orderData.assign_to ? `
                                                <span class="badge badge-success">Assigned</span><br>
                                                <small><strong>To:</strong> ${orderData.delivery_boy_name || 'Unknown'}</small><br>
                                                <button class="btn btn-warning btn-sm mt-1 reassign-btn" 
                                                        data-id="${orderData.id || ''}" 
                                                        data-current="${orderData.assign_to || ''}">
                                                    Reassign
                                                </button>
                                            ` : `
                                                <span class="badge badge-secondary">Unassigned</span>
                                            `}
                                        </td>
                                    </tr>
                                `;
                            });

                            // Insert the generated rows into the table body
                            $('#example1 tbody').html(tableRows);

                            // Destroy and reinitialize DataTable
                            table.destroy();
                            table = $("#example1").DataTable({
                                "responsive": true,
                                "lengthChange": false,
                                "autoWidth": false,
                                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: "Invalid response format"
                            });
                        }
                    },
                    error: function () {
                        Toast.fire({
                            icon: 'error',
                            title: "Failed to load orders"
                        });
                    }
                });
            }

            // Call the function to load delivery boys into card header dropdown
            loadHeaderDeliveryBoys();

            // Handle Delivery Boy Selection Change
            $('#header_delivery_boy_id').on('change', function () {
                let deliveryBoyId = $(this).val();
                loadOrdersByDeliveryBoy(deliveryBoyId);
            });

            // Select All Checkbox
            $('#select-all').on('click', function () {
                $('.order-checkbox').prop('checked', $(this).prop('checked'));
                toggleAssignButton();
            });

            // Individual Checkbox
            $(document).on('click', '.order-checkbox', function () {
                toggleAssignButton();
            });

            // Reassign button click handler
            $(document).on('click', '.reassign-btn', function () {
                let orderId = $(this).data('id');
                $(this).closest('tr').find('.order-checkbox').prop('checked', true);
                toggleAssignButton();
                let currentDeliveryBoy = $(this).data('current');
                loadModalDeliveryBoys([orderId], currentDeliveryBoy);
            });

            // Toggle Assign Button
            function toggleAssignButton() {
                if ($('.order-checkbox:checked').length > 0) {
                    $('#assignOrder').show();
                } else {
                    $('#assignOrder').hide();
                }
            }

            // Assign Order Button Click
            $('#assignOrder').on('click', function () {
                let selectedIds = $('.order-checkbox:checked').map(function () {
                    return $(this).data('id');
                }).get();
                loadModalDeliveryBoys(selectedIds);
            });

            // Confirm Assignment
            $('#confirmAssign').on('click', function () {
                let deliveryBoyId = $('#modal_delivery_boy_id').val();
                let orderIds = $('#selected_order_ids').val();

                if (!deliveryBoyId) {
                    Toast.fire({
                        icon: 'warning',
                        title: 'Please select a delivery boy'
                    });
                    return;
                }

                $.ajax({
                    url: "{{ route('delivery.assign.order') }}",
                    type: "POST",
                    data: {
                        deliverBoyData: deliveryBoyId,
                        orderId: orderIds,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#assignOrderModal').modal('hide');
                            Toast.fire({
                                icon: 'success',
                                title: response.message
                            });
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: response.message || "Failed to assign orders"
                            });
                        }
                    },
                    error: function () {
                        Toast.fire({
                            icon: 'error',
                            title: "Server error while assigning orders"
                        });
                    }
                });
            });

            // Check for flash messages
            @if(session('message'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('message') }}"
                });
            @endif
        });
    </script>
@endpush