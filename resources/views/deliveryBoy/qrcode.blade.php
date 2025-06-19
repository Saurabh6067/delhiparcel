@extends('deliveryBoy.layout.main')

@php
    $deliveryBoyId = session('dlyId');
@endphp

@push('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- iCheck for checkboxes -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Custom styles -->
    <style>
        #qr-reader {
            border: 2px solid #28a745;
            border-radius: 5px;
        }
        #qr-result {
            min-height: 50px;
        }
        .alert {
            margin-bottom: 0;
        }
    </style>
@endpush

@section('main')
    <!-- Same as previous qr_page.blade.php -->
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>QR Code Order Management</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/delivery-boy-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">QR Code Orders</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <!-- QR Code Scanner -->
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Scan QR Code</h3>
                    </div>
                    <div class="card-body text-center">
                        <button class="btn btn-success mb-2" onclick="startQRScanner()">📷 Start Scanner</button>
                        <button id="close-scanner-btn" class="btn btn-danger mb-2" onclick="stopQRScanner()"
                            style="display: none;">✖️ Close Scanner</button>
                        <div id="qr-reader" style="width: 300px; margin: auto; display: none;"></div>
                        <div id="qr-result" class="mt-3 font-weight-bold"></div>
                    </div>
                </div>

                <!-- Picked Up Orders Section -->
                <div class="card card-danger">
                    <div class="card-header">
                        <h3 class="card-title">Mark Picked Up Orders as Delivered to Branch</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-danger">
                            <i class="fas fa-info-circle"></i> This section shows orders you picked up today (status: Item Picked Up). Select the orders you've delivered to the branch.
                        </div>
                        <form id="pickedUpForm">
                            <div class="table-responsive">
                                <table id="pickedUpOrdersTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="5%">
                                                <div class="icheck-primary">
                                                    <input type="checkbox" id="selectAllPickedUp">
                                                    <label for="selectAllPickedUp"></label>
                                                </div>
                                            </th>
                                            <th>Order ID</th>
                                            <th>Pickup Time</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pickedUpOrdersBody">
                                        <tr>
                                            <td colspan="3" class="text-center">Loading today's picked up orders...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-danger" id="markPickedUpDeliveredBtn">
                                    <i class="fas fa-check-circle"></i> Mark Selected as Delivered to Branch
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Delivered to Nearby Branch Orders Section -->
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Mark Orders Delivered to Nearby Branch</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success">
                            <i class="fas fa-info-circle"></i> This section shows orders you delivered to a nearby branch today (status: Delivered to near by branch). Select the orders to update their status.
                        </div>
                        <form id="deliveredToNearbyBranchForm">
                            <div class="table-responsive">
                                <table id="deliveredToNearbyBranchTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="5%">
                                                <div class="icheck-primary">
                                                    <input type="checkbox" id="selectAllDeliveredToNearby">
                                                    <label for="selectAllDeliveredToNearby"></label>
                                                </div>
                                            </th>
                                            <th>Order ID</th>
                                            <th>Pickup Time</th>
                                        </tr>
                                    </thead>
                                    <tbody id="deliveredToNearbyBranchBody">
                                        <tr>
                                            <td colspan="3" class="text-center">Loading today's delivered to nearby branch orders...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <button type="submit" class="btn btn-success" id="markDeliveredToNearbyBtn">
                                    <i class="fas fa-check-circle"></i> Mark Selected as Delivered to Branch
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- jQuery -->
    <script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- DataTables -->
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
    <!-- QR Scanner -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        const deliveryBoyId = "{{ $deliveryBoyId }}";
        let html5QrCode;
        const baseURL = "https://delhiparcel.gtechs.in/trackOrders/";

        // Start QR Scanner
        function startQRScanner() {
            const qrReader = document.getElementById("qr-reader");
            const qrResult = document.getElementById("qr-result");
            const closeBtn = document.getElementById("close-scanner-btn");

            qrReader.style.display = "block";
            closeBtn.style.display = "inline-block";
            qrResult.innerHTML = "📡 Scanning...";

            html5QrCode = new Html5Qrcode("qr-reader");

            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: 250 },
                (decodedText, decodedResult) => {
                    stopQRScanner();
                    let trackingCode = decodedText;
                    if (decodedText.includes('/')) {
                        trackingCode = decodedText.split("/").pop();
                    }
                    qrResult.innerHTML = `<span class='text-info'>⏳ Sending tracking code: ${trackingCode}</span>`;

                    fetch("{{ route('delivery.boy.qrcode') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            tracking_id: trackingCode,
                            deliveryBoyId: deliveryBoyId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (data.is_scanned) {
                                const senderPincode = data.order;
                                const orderStatus = data.order_status;
                                const orderId = data.order_id;

                                if ([ "Delivered to branch", "Out for Delivery to Origin", "Not Delivered", "Item Not Picked Up"].includes(orderStatus)) {
                                    qrResult.innerHTML = `
                                        <div class="alert alert-info">
                                            <h4>📦 Order with Special Status</h4>
                                            <p>Order ID: ${orderId}</p>
                                            <p>Current Status: ${orderStatus}</p>
                                            <p>You can update this order's status.</p>
                                        </div>
                                    `;
                                    openStatusModal(orderId, orderStatus);
                                } else if (orderStatus === "Booked") {
                                    document.getElementById('orderIdedit_booked').value = orderId;
                                    qrResult.innerHTML = `
                                        <div class="alert alert-success">
                                            <h4>✅ This is your order!</h4>
                                            <p>Sender Pincode: ${senderPincode}</p>
                                            <p>Order Status: ${orderStatus}</p>
                                        </div>
                                    `;
                                    $('#statusUpdateModal').modal('show');
                                } else {
                                    qrResult.innerHTML = `
                                        <div class="alert alert-warning">
                                            <h4>✅ This is your order, but status is not actionable</h4>
                                            <p>Sender Pincode: ${senderPincode}</p>
                                            <p>Current Status: ${orderStatus}</p>
                                            <p>This order status cannot be updated via QR code.</p>
                                        </div>
                                    `;
                                }
                            } else {
                                qrResult.innerHTML = `
                                    <div class="alert alert-danger">
                                        <h4>❌ ${data.message}</h4>
                                        <p>Order ID: ${data.order_id}</p>
                                    </div>
                                `;
                            }
                        } else {
                            qrResult.innerHTML = `<span class='text-danger'>❌ ${data.message}</span>`;
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        qrResult.innerHTML = "<span class='text-danger'>❌ Failed to process response. Check console for details.</span>";
                    });
                },
                (errorMessage) => {
                    console.log("QR scanning error:", errorMessage);
                }
            ).catch(err => {
                console.error("Camera error:", err);
                qrResult.innerHTML = "<span class='text-danger'>❌ Camera access denied or not found</span>";
            });
        }

        // Open Status Modal
        function openStatusModal(orderId, currentStatus) {
            $('#assignForm')[0].reset();
            $('#type').val('OrderStatus');
            $('#dBoyLabel').html('Order Status');
            $('#orderIdedit').val(orderId);
            $('#optionId').html('Select Order Status');
            $('#btn').html('Update Order Status');
            $('#deliverBoy').empty();
            $('#reason').addClass('d-none');
            $('#message').removeClass('d-none');
            $('#status_message').val('');
            $('#reason_msg').empty();

            // if (currentStatus === 'Item Picked Up') {
            //     $('#deliverBoy').append('<option selected disabled>Select</option><option value="Delivered to branch">Delivered to branch</option>');
             if (currentStatus === 'Delivered to branch') {
                $('#deliverBoy').append('<option selected disabled>Select</option><option value="Out for Delivery to Origin">Out for Delivery to Origin</option>');
            } else if (currentStatus === 'Out for Delivery to Origin') {
                $('#deliverBoy').append('<option selected disabled>Select</option><option value="Delivered">Delivered</option><option value="Not Delivered">Not Delivered</option>');
            } else if (currentStatus === 'Item Not Picked Up') {
                $('#deliverBoy').append('<option selected disabled>Select</option><option value="Item Picked Up">Item Picked Up</option>');
            } else if (currentStatus === 'Not Delivered') {
                $('#deliverBoy').append('<option selected disabled>Select</option><option value="Delivered">Delivered</option>');
            }
            $('#dBoy_orderStatus').modal('show');
        }

        // Stop QR Scanner
        function stopQRScanner() {
            const qrReader = document.getElementById("qr-reader");
            const closeBtn = document.getElementById("close-scanner-btn");

            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    html5QrCode.clear();
                }).catch(err => {
                    console.error("Error stopping scanner", err);
                });
            }
            qrReader.style.display = "none";
            closeBtn.style.display = "none";
        }

        // Load orders for a specific table
        function loadOrders(tableId, tableBodyId, route, statusLabel) {
            const tableBody = document.getElementById(tableBodyId);
            tableBody.innerHTML = `<tr><td colspan="3" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading ${statusLabel} orders...</td></tr>`;

            fetch(route, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ deliveryBoyId: deliveryBoyId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.orders && data.orders.length > 0) {
                    tableBody.innerHTML = '';
                    data.orders.forEach(order => {
                        tableBody.innerHTML += `
                            <tr>
                                <td>
                                    <div class="icheck-primary">
                                        <input type="checkbox" name="selected_orders[]" value="${order.order_id}" id="${tableBodyId}_${order.order_id}">
                                        <label for="${tableBodyId}_${order.order_id}"></label>
                                    </div>
                                </td>
                                <td>${order.order_id}</td>
                                <td>${order.pickup_time || 'N/A'}</td>
                            </tr>
                        `;
                    });

                    if ($.fn.DataTable.isDataTable(`#${tableId}`)) {
                        $(`#${tableId}`).DataTable().destroy();
                    }

                    $(`#${tableId}`).DataTable({
                        paging: true,
                        lengthChange: false,
                        searching: true,
                        ordering: true,
                        info: true,
                        autoWidth: false,
                        responsive: true,
                        pageLength: 10
                    });
                } else {
                    tableBody.innerHTML = `<tr><td colspan="3" class="text-center">No ${statusLabel} orders found for today</td></tr>`;
                }
            })
            .catch(error => {
                console.error("Error:", error);
                tableBody.innerHTML = `<tr><td colspan="3" class="text-center text-danger">Failed to load ${statusLabel} orders. Please try again.</td></tr>`;
            });
        }

        // Load both tables
        function loadTodaysPickedUpOrders() {
            loadOrders('pickedUpOrdersTable', 'pickedUpOrdersBody', "{{ route('delivery.boy.todays.pickups') }}", "picked up");
        }

        function loadTodaysDeliveredToNearbyBranchOrders() {
            loadOrders('deliveredToNearbyBranchTable', 'deliveredToNearbyBranchBody', "{{ route('delivery.boy.todays.pickups.status') }}", "delivered to nearby branch");
        }

        function reloadTodaysOrders() {
            loadTodaysPickedUpOrders();
            loadTodaysDeliveredToNearbyBranchOrders();
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Load both tables on page load
            loadTodaysPickedUpOrders();
            loadTodaysDeliveredToNearbyBranchOrders();

            // Select All for Picked Up Orders
            document.getElementById('selectAllPickedUp').addEventListener('change', function () {
                const checkboxes = document.querySelectorAll('#pickedUpOrdersBody input[name="selected_orders[]"]');
                checkboxes.forEach(checkbox => checkbox.checked = this.checked);
            });

            // Select All for Delivered to Nearby Branch Orders
            document.getElementById('selectAllDeliveredToNearby').addEventListener('change', function () {
                const checkboxes = document.querySelectorAll('#deliveredToNearbyBranchBody input[name="selected_orders[]"]');
                checkboxes.forEach(checkbox => checkbox.checked = this.checked);
            });

            // Handle form submission for Picked Up Orders
            document.getElementById('pickedUpForm').addEventListener('submit', function (e) {
                e.preventDefault();
                handleFormSubmission('pickedUpForm', 'markPickedUpDeliveredBtn', "{{ route('delivery.boy.mark.delivered.to.branch') }}", "picked up");
            });

            // Handle form submission for Delivered to Nearby Branch Orders
            document.getElementById('deliveredToNearbyBranchForm').addEventListener('submit', function (e) {
                e.preventDefault();
                handleFormSubmission('deliveredToNearbyBranchForm', 'markDeliveredToNearbyBtn', "{{ route('delivery.boy.mark.delivered.to.branch') }}", "delivered to nearby branch");
            });

            // Generic form submission handler
            function handleFormSubmission(formId, buttonId, route, statusLabel) {
                const selectedOrders = [];
                document.querySelectorAll(`#${formId} input[name="selected_orders[]"]:checked`).forEach(checkbox => {
                    selectedOrders.push(checkbox.value);
                });

                if (selectedOrders.length === 0) {
                    alert(`Please select at least one ${statusLabel} order to mark as delivered to branch`);
                    return;
                }

                if (!confirm(`Are you sure you want to mark ${selectedOrders.length} ${statusLabel} order(s) as delivered to branch?`)) {
                    return;
                }

                const markDeliveredBtn = document.getElementById(buttonId);
                const originalBtnText = markDeliveredBtn.innerHTML;
                markDeliveredBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                markDeliveredBtn.disabled = true;

                fetch(route, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        order_ids: selectedOrders,
                        deliveryBoyId: deliveryBoyId
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    markDeliveredBtn.innerHTML = originalBtnText;
                    markDeliveredBtn.disabled = false;
                    if (data.success) {
                        if (data.requires_otp) {
                            window.currentOrdersData = {};
                            if (data.orders && data.orders.length > 0) {
                                data.orders.forEach(order => {
                                    const branchId = order.branch_id;
                                    if (!window.currentOrdersData[branchId]) {
                                        window.currentOrdersData[branchId] = {
                                            branch_id: branchId,
                                            branch_name: order.branch_name,
                                            branch_email: order.branch_email,
                                            orders: []
                                        };
                                    }
                                    window.currentOrdersData[branchId].orders.push(order.order_id);
                                });

                                const branchKeys = Object.keys(window.currentOrdersData);
                                if (!branchKeys.length) throw new Error('No branch data received');

                                const firstBranchId = branchKeys[0];
                                const firstBranch = window.currentOrdersData[firstBranchId];
                                const ordersList = firstBranch.orders.join(', ');

                                document.getElementById('otp_order_id').value = firstBranchId;
                                document.getElementById('otpMessage').innerHTML = '';
                                document.getElementById('verification_otp').value = '';

                                const infoAlert = document.querySelector('#otpVerificationModal .alert-info');
                                if (!infoAlert) throw new Error('OTP modal alert element not found');

                                infoAlert.innerHTML = `
                                    <i class="fas fa-info-circle"></i> 
                                    An OTP has been sent to the branch: <strong>${firstBranch.branch_name}</strong> 
                                    (${firstBranch.branch_email || 'No email available'}). 
                                    <p>Orders: ${ordersList}</p>
                                    <p>Please contact the branch for the OTP and enter it below to confirm delivery.</p>
                                `;
                                $('#otpVerificationModal').modal('show');
                            } else {
                                throw new Error('No orders data received from server');
                            }
                        } else {
                            alert(data.message);
                            reloadTodaysOrders();
                        }
                    } else {
                        alert(data.message || 'Operation failed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    markDeliveredBtn.innerHTML = originalBtnText;
                    markDeliveredBtn.disabled = false;
                    alert(`Error: ${error.message || 'An error occurred while processing your request'}`);
                });
            }

            // Status change handler for Booked orders
            document.getElementById('status').addEventListener('change', function () {
                const reasonContainer = document.getElementById('reasonContainer');
                const statusMessage = document.getElementById('status_message');
                if (this.value === 'Item Not Picked Up') {
                    reasonContainer.style.display = 'block';
                    statusMessage.setAttribute('required', 'required');
                } else {
                    reasonContainer.style.display = 'none';
                    statusMessage.removeAttribute('required');
                    statusMessage.value = '';
                }
            });

            // DeliverBoy status change handler
            $('#deliverBoy').change(function () {
                const value = $(this).val();
                if (value === 'Not Delivered') {
                    $('#reason').removeClass('d-none');
                    $('#message').addClass('d-none');
                    $('#reason_msg').empty();
                    $('#reason_msg').append(
                        '<option selected disabled>Select</option>' +
                        '<option value="Incorrect Address">Incorrect Address</option>' +
                        '<option value="Call Not Pickup">Call Not Pickup</option>' +
                        '<option value="Incorrect Mobile No">Incorrect Mobile No</option>' +
                        '<option value="Re Schedule">Re Schedule</option>' +
                        '<option value="Cancel">Cancel</option>' +
                        '<option value="Customer want open delivery">Customer want open delivery</option>' +
                        '<option value="Mismatch in COD Amount">Mismatch in COD Amount</option>'
                    );
                } else {
                    $('#reason').addClass('d-none');
                    $('#message').removeClass('d-none');
                    $('#reason_msg').empty();
                    $('#status_message').val('');
                }
            });

            // Update status for Booked orders
            document.getElementById('updateOrderStatusForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const orderId = document.getElementById('orderIdedit_booked').value;
                const status = document.getElementById('status').value;
                const statusMessage = document.getElementById('status_message').value;
                const qrResult = document.getElementById('qr-result');
                qrResult.innerHTML = `<span class='text-info'>⏳ Updating order status...</span>`;
                $('#statusUpdateModal').modal('hide');

                console.log('Submitting Booked order update with data:', {
                    type: 'OrderStatus',
                    action: 'qrcode',
                    orderIdedit: orderId,
                    deliverBoy: status,
                    status_message: statusMessage
                });

                $.ajax({
                    url: "{{ route('delivery.boy.status.update') }}",
                    type: "POST",
                    data: {
                        type: 'OrderStatus',
                        action: 'qrcode',
                        orderIdedit: orderId,
                        deliverBoy: status,
                        status_message: statusMessage,
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    success: function(data) {
                        console.log('Update response:', data);
                        if (data.success) {
                            qrResult.innerHTML = `
                                <div class="alert alert-success">
                                    <h4>✅ Order status updated successfully!</h4>
                                    <p>New status: ${status}</p>
                                    ${statusMessage ? `<p>Reason: ${statusMessage}</p>` : ''}
                                </div>
                            `;
                            reloadTodaysOrders();
                        } else {
                            qrResult.innerHTML = `<div class="alert alert-danger">❌ ${data.message}</div>`;
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Update error:', { xhr, status, error });
                        qrResult.innerHTML = `<div class="alert alert-danger">❌ Failed to update status: ${xhr.responseJSON?.message || 'Server error. Check console for details.'}</div>`;
                    }
                });
            });

            // Update status for special statuses
            document.getElementById('assignForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const form = $(this);
                const qrResult = document.getElementById('qr-result');
                qrResult.innerHTML = `<span class='text-info'>⏳ Updating order status...</span>`;
                $('#dBoy_orderStatus').modal('hide');

                console.log('Submitting special status update with data:', form.serializeArray());

                $.ajax({
                    url: "{{ route('delivery.boy.status.update') }}",
                    type: "POST",
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(data) {
                        console.log('Update response:', data);
                        if (data.success) {
                            qrResult.innerHTML = `
                                <div class="alert alert-success">
                                    <h4>✅ Order status updated successfully!</h4>
                                    <p>${data.message}</p>
                                </div>
                            `;
                            reloadTodaysOrders();
                        } else {
                            qrResult.innerHTML = `<div class="alert alert-danger">❌ ${data.message}</div>`;
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Update error:', { xhr, status, error });
                        qrResult.innerHTML = `<div class="alert alert-danger">❌ Failed to update status: ${xhr.responseJSON?.message || 'Server error. Check console for details.'}</div>`;
                    }
                });
            });

            // OTP Verification
            document.getElementById('otpVerificationForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const branchId = document.getElementById('otp_order_id').value;
                const otp = document.getElementById('verification_otp').value;
                const messageDiv = document.getElementById('otpMessage');
                const branchData = window.currentOrdersData[branchId];

                if (!branchData || !branchData.orders || branchData.orders.length === 0) {
                    messageDiv.innerHTML = '<div class="alert alert-danger">No orders found for this branch</div>';
                    return;
                }

                if (!otp || otp.length !== 4 || !/^\d+$/.test(otp)) {
                    messageDiv.innerHTML = '<div class="alert alert-danger">Please enter a valid 4-digit OTP</div>';
                    return;
                }

                const submitBtn = this.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';
                submitBtn.disabled = true;

                fetch("{{ route('delivery.boy.verify.otp') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        branch_id: branchId,
                        order_ids: branchData.orders,
                        otp: otp,
                        deliveryBoyId: deliveryBoyId
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                    if (data.success) {
                        messageDiv.innerHTML = `<div class="alert alert-success">OTP verified successfully! ${data.count} order(s) marked as delivered.</div>`;
                        setTimeout(() => {
                            $('#otpVerificationModal').modal('hide');
                            reloadTodaysOrders();
                        }, 1500);
                    } else {
                        messageDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    messageDiv.innerHTML = '<div class="alert alert-danger">Failed to verify OTP. Please try again.</div>';
                    submitBtn.innerHTML = originalBtnText;
                    submitBtn.disabled = false;
                });
            });
        });
    </script>
@endpush

@push('modals')
    <!-- Status Update Modal for Booked Orders -->
    <div class="modal fade" id="statusUpdateModal" tabindex="-1" role="dialog" aria-labelledby="statusUpdateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="statusUpdateModalLabel">Update Order Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="updateOrderStatusForm">
                    <div class="modal-body">
                        <input type="hidden" id="orderIdedit_booked" name="orderIdedit">
                        <input type="hidden" name="type" value="OrderStatus">
                        <input type="hidden" name="action" value="qrcode">
                        <div class="form-group">
                            <label for="status">Select Status</label>
                            <select class="form-control" id="status" name="deliverBoy" required>
                                <option value="">-- Select Status --</option>
                                <option value="Item Picked Up">Picked Up</option>
                                <option value="Item Not Picked Up">Not Picked Up</option>
                            </select>
                        </div>
                        <div class="form-group" id="reasonContainer" style="display: none;">
                            <label for="status_message">Reason for Not Picking Up</label>
                            <textarea class="form-control" id="status_message" name="status_message" rows="3"
                                placeholder="Enter reason here..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Order Status Modal -->
    <div class="modal fade" id="dBoy_orderStatus" tabindex="-1" role="dialog" aria-labelledby="dBoyLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="dBoyLabel">Order Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    
                </div>
                <div class="modal-body">
                    <form id="assignForm">
                        <input type="hidden" id="type" name="type" value="OrderStatus">
                        <input type="hidden" id="action" name="action" value="qrcode">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="orderIdedit">Order Id</label>
                                <input type="text" class="form-control" id="orderIdedit" name="orderIdedit" value=""
                                    readonly>
                            </div>
                            <div class="form-group">
                                <label for="deliverBoy" id="optionId">Select Order Status</label>
                                <select class="custom-select rounded-0" id="deliverBoy" name="deliverBoy" required>
                                    <!-- Options will be populated dynamically -->
                                </select>
                            </div>
                            <div class="form-group d-none" id="reason">
                                <label for="reason_msg">Reason</label>
                                <select class="custom-select rounded-0" id="reason_msg" name="Reason_message">
                                    <!-- Options will be populated dynamically -->
                                </select>
                            </div>
                            <div class="form-group" id="message">
                                <label for="status_message">Message</label>
                                <textarea class="form-control" id="status_message" name="status_message"
                                    placeholder="Enter Message"></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" id="btn">Update Order Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- OTP Verification Modal -->
    <div class="modal fade" id="otpVerificationModal" tabindex="-1" role="dialog"
        aria-labelledby="otpVerificationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="otpVerificationModalLabel">OTP Verification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="otpVerificationForm">
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Branch information will appear here.
                        </div>
                        <input type="hidden" id="otp_order_id" name="branch_id">
                        <div class="form-group">
                            <label for="verification_otp">Enter OTP</label>
                            <input type="text" class="form-control" id="verification_otp" name="otp"
                                placeholder="Enter 4-digit OTP" maxlength="4" required pattern="[0-9]{4}"
                                inputmode="numeric">
                        </div>
                        <div id="otpMessage"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Verify OTP</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush