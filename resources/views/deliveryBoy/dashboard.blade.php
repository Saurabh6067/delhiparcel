@extends('deliveryBoy.layout.main')
@section('main')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6 col-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div>
                    <div class="col-sm-2 col-6 text-end" style="display:grid">
                        <a class="btn btn-warning" href="{{ url('/delivery-boy-qrscanner') }}">Scan</a>
                    </div>
                    
                    <!-- <div class="col-sm-2 col-6">-->
                    <!--    <h1 class="m-0">Dashboard</h1>-->
                    <!--</div>-->
                    <div class="col-sm-4 col-12">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active">{{ $delivery->name }} / Delivery Boy Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <h5 class="mt-3 font-weight-bold">Order Status</h5>
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-12">
                        <a href="{{ url('/delivery-boy-order-details/' . 'toDayOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-box"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Today Orders</strong></span>
                                    <span class="info-box-number">{{ $toDayOrder ?? 0.0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <a href="{{ url('/delivery-boy-order-details/' . 'PendingSuperExpressOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-hourglass"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Pending Super Express</strong></span>
                                    <span class="info-box-number" id="SuperExpressOrder"
                                        data-order-ids="{{ implode(',', $pendingSuperExpressOrderIds ?? []) }}">
                                        {{ $PendingSuperExpressOrder }}
                                        <!--<span class="info-box-number text-danger" id="SuperExpressOrderTime">00:00:00</span>-->
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <a href="{{ url('/delivery-boy-order-details/' . 'PendingOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-secondary"><i class="fas fa-hourglass"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Pending Pickup orders</strong></span>
                                    <span class="info-box-number">{{ $PendingOrder ?? 0.0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <a href="{{ url('/delivery-boy-order-details/' . 'PendingDeliveryOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-hourglass"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Pending Delivery Order</strong></span>
                                    <span class="info-box-number">{{ $PendingDeliveryOrder }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                     <div class="col-md-4 col-sm-6 col-12">
                        <a href="{{ url('/delivery-boy-order-details/' . 'toDayCompleteOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="far fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Today Complete</strong></span>
                                    <span class="info-box-number"> {{ $toDayCompleteOrder ?? 0.0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                     <div class="col-md-4 col-sm-6 col-12">
                        <a href="{{ url('/delivery-boy-order-details/' . 'transfertoMyBranchOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-hourglass"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Transfer to My Branch</strong></span>
                                    <span class="info-box-number">{{ $transfertoMyBranchOrder }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                     <div class="col-md-4 col-sm-6 col-12">
                        <a href="{{ url('/delivery-boy-order-details/' . 'transfertoOtherBranchOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-hourglass"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Transfer to Other Branch</strong></span>
                                    <span class="info-box-number">{{ $transfertoOtherBranchOrder }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    
                    
                   
                    <!--  <div class="col-md-4 col-sm-6 col-12">-->
                    <!--    <a href="{{ url('/delivery-boy-qrscanner') }}" class="text-dark">-->
                    <!--        <div class="info-box">-->
                    <!--            <span class="info-box-icon bg-success"><i class="fa fa-qrcode"></i></span>-->
                    <!--            <div class="info-box-content">-->
                    <!--                <span class="info-box-text"><strong>QR Scanner</strong></span>-->
                                    <!--<span class="info-box-number"> {{ $toDayCompleteOrder ?? 0.0 }}</span>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </a>-->
                    <!--</div>-->
                    <!--<div class="col-md-4 col-sm-6 col-12">-->
                    <!--    <a href="{{ url('/delivery-boy-order-details/' . 'totalCompleteOrder') }}" class="text-dark">-->
                    <!--        <div class="info-box">-->
                    <!--            <span class="info-box-icon bg-dark"><i class="fas fa-check-circle"></i></span>-->
                    <!--            <div class="info-box-content">-->
                    <!--                <span class="info-box-text"><strong>Total Delivery Orders</strong></span>-->
                    <!--                <span class="info-box-number">{{ $totalCompleteOrder ?? 0.0 }}</span>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </a>-->
                    <!--</div>-->
                    <!--<div class="col-md-6 col-sm-6 col-12">-->
                    <!--    <a href="{{ url('/delivery-boy-transfer-order-details/' . 'DirectOrders') }}" class="text-dark">-->
                    <!--        <div class="info-box">-->
                    <!--            <span class="info-box-icon bg-info"><i class="fas fa-box"></i></span>-->
                    <!--            <div class="info-box-content">-->
                    <!--                <span class="info-box-text"><strong>Pending Other PinCode Orders</strong></span>-->
                    <!--                <span class="info-box-number">{{ $PendingPinCodeOrders ?? 0 }}</span>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </a>-->
                    <!--</div>-->
                    <!--<div class="col-md-6 col-sm-6 col-12">-->
                    <!--    <a href="{{ url('/delivery-boy-transfer-order-details/' . 'CompleteOrders') }}" class="text-dark">-->
                    <!--        <div class="info-box">-->
                    <!--            <span class="info-box-icon bg-info"><i class="fas fa-box"></i></span>-->
                    <!--            <div class="info-box-content">-->
                    <!--                <span class="info-box-text"><strong>All Other PinCode Orders</strong></span>-->
                    <!--                <span class="info-box-number">{{ $CompleteOtherPinCodeOrders ?? 0 }}</span>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </a>-->
                    <!--</div>-->
                </div>

            </div>
        </section>
    </div>

    <!-- CSRF Token for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Delivery Time Tracking Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            console.log("Timer script loaded");

            // Get the super express order element
            let superExpressOrderElement = document.getElementById("SuperExpressOrder");
            let superExpressOrderTimeEl = document.getElementById("SuperExpressOrderTime");

            if (!superExpressOrderElement || !superExpressOrderTimeEl) {
                console.error("Timer elements not found!");
                return;
            }

            console.log("Found timer elements");

            // Get the count of pending super express orders
            let orderText = superExpressOrderElement.childNodes[0].nodeValue.trim();
            let superExpressOrderCount = parseInt(orderText, 10);

            console.log("Pending super express orders:", superExpressOrderCount);

            // Check if we have pending super express orders
            if (superExpressOrderCount > 0) {
                console.log("Starting timer for pending orders");

                // Only initialize the timer if it hasn't been initialized yet
                if (!localStorage.getItem('timerStartTime')) {
                    initializeTimer();
                }

                startOrderTimer();
            } else {
                // If no pending orders, reset the timer
                resetTimer();
            }

            // Function to initialize timer
            function initializeTimer() {
                console.log("Initializing timer");
                // Store the current time as the start time
                localStorage.setItem('timerStartTime', new Date().getTime());
            }

            // Function to reset timer
            function resetTimer() {
                console.log("Resetting timer");
                localStorage.removeItem('timerStartTime');
                localStorage.removeItem('timerIntervalId');
                superExpressOrderTimeEl.innerText = "00:00:00";
            }

            // Function to start timer for orders
            function startOrderTimer() {
                console.log("Starting order timer");

                // Clear any existing interval
                const existingIntervalId = localStorage.getItem('timerIntervalId');
                if (existingIntervalId) {
                    clearInterval(parseInt(existingIntervalId));
                    console.log("Cleared existing timer interval");
                }

                // Update the timer every second
                let timerInterval = setInterval(function () {
                    updateElapsedTime();

                    // Check if order status has changed to "Delivered" (less frequently)
                    if (Math.floor(Date.now() / 1000) % 10 === 0) { // Check every 10 seconds
                        checkOrderStatus();
                    }
                }, 1000);

                // Store the interval ID so we can clear it later
                localStorage.setItem('timerIntervalId', timerInterval);
                console.log("Timer interval started and saved");

                // Immediately update the elapsed time display
                updateElapsedTime();
            }

            // Function to update elapsed time display
            function updateElapsedTime() {
                // Get start time from storage or use current time as fallback
                let startTime = parseInt(localStorage.getItem('timerStartTime'));

                // If no start time is stored, initialize it
                if (!startTime) {
                    startTime = new Date().getTime();
                    localStorage.setItem('timerStartTime', startTime);
                }

                let currentTime = new Date().getTime();
                let elapsedTimeMs = currentTime - startTime;

                // Convert milliseconds to hours, minutes, seconds
                let totalSeconds = Math.floor(elapsedTimeMs / 1000);
                let hours = Math.floor(totalSeconds / 3600);
                let minutes = Math.floor((totalSeconds % 3600) / 60);
                let seconds = totalSeconds % 60;

                // Format the time string
                let timeString = String(hours).padStart(2, '0') + ":" +
                    String(minutes).padStart(2, '0') + ":" +
                    String(seconds).padStart(2, '0');

                // Update the display
                superExpressOrderTimeEl.innerText = timeString;

                // Add warning class if elapsed time is more than 2.5 hours
                if (hours >= 2 && minutes >= 30) {
                    superExpressOrderTimeEl.classList.add('text-danger', 'font-weight-bold');
                } else {
                    superExpressOrderTimeEl.classList.remove('text-danger', 'font-weight-bold');
                }
            }

            // Function to check if order status has changed to "Delivered"
            function checkOrderStatus() {
                console.log("Checking order status");

                fetch("{{ route('order.check.status') }}")
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log("Order status response:", data);

                        // Process delivered orders
                        if (data.deliveredOrders && data.deliveredOrders.length > 0) {
                            console.log("Processing delivered orders:", data.deliveredOrders.length);
                            processDeliveredOrders(data.deliveredOrders);
                        }

                        // Update the order count display
                        // We need to preserve the time element while updating the count
                        let timeHTML = superExpressOrderTimeEl.outerHTML;
                        superExpressOrderElement.innerHTML = data.pendingSuperExpressCount + " " + timeHTML;

                        console.log("Updated pending count:", data.pendingSuperExpressCount);

                        // If no more pending orders, clear the interval and reset the timer
                        if (data.pendingSuperExpressCount === 0) {
                            resetTimer();
                        }
                    })
                    .catch(error => console.error('Error checking order status:', error));
            }

            // Function to process delivered orders
            function processDeliveredOrders(deliveredOrders) {
                // Get start time from storage
                let startTime = parseInt(localStorage.getItem('timerStartTime'));
                if (!startTime) {
                    console.error("No start time found");
                    return;
                }

                let currentTime = new Date().getTime();
                let elapsedTimeMs = currentTime - startTime;
                let deliveryTimeSeconds = Math.floor(elapsedTimeMs / 1000);

                // Format time for display
                const hours = Math.floor(deliveryTimeSeconds / 3600);
                const minutes = Math.floor((deliveryTimeSeconds % 3600) / 60);
                const seconds = deliveryTimeSeconds % 60;

                const formattedTime =
                    String(hours).padStart(2, '0') + ":" +
                    String(minutes).padStart(2, '0') + ":" +
                    String(seconds).padStart(2, '0');

                // For each delivered order
                deliveredOrders.forEach(order => {
                    const orderId = order.id;
                    console.log("Processing delivered order:", orderId);

                    // Save delivery time to database
                    saveDeliveryTime(orderId, deliveryTimeSeconds, formattedTime);
                });

                // Do NOT reset the timer here - only reset when all orders are done
            }

            // Function to save delivery time to database
            function saveDeliveryTime(orderId, deliveryTimeSeconds, formattedTime) {
                console.log("Saving delivery time for order:", orderId, formattedTime);

                // Get CSRF token
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                console.log("CSRF token found:", token ? "Yes" : "No");

                // Send to server
                fetch("{{ route('order.save.delivery.time') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        delivery_time: formattedTime,
                        delivery_time_seconds: deliveryTimeSeconds
                    })
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            console.log(`Delivery time saved for order ${orderId}: ${formattedTime}`);
                        } else {
                            console.error('Error saving delivery time:', data.message);
                        }
                    })
                    .catch(error => console.error('Error saving delivery time:', error));
            }
        });
    </script>
@endsection