@extends('seller.layout.main')
@push('style')
    <style>
        /* General Styling */
        .stepwizard {
            display: table;
            width: 100%;
            position: relative;
        }

        .stepwizard-row {
            display: table-row;
            position: relative;
        }

        .stepwizard-step {
            display: table-cell;
            text-align: center;
            position: relative;
        }

        .stepwizard-step p {
            margin-top: 10px;
        }

        .stepwizard-row::before {
            content: "";
            position: absolute;
            top: 14px;
            width: 100%;
            height: 1px;
            background-color: #ccc;
        }

        .stepwizard-step button[disabled] {
            opacity: 1 !important;
            filter: alpha(opacity=100) !important;
            cursor: not-allowed;
        }

        /* Button Styles */
        .btn-circle {
            width: 30px;
            height: 30px;
            text-align: center;
            padding: 6px 0;
            font-size: 12px;
            line-height: 1.428571429;
            border-radius: 15px;
        }

        button:disabled,
        button[disabled] {
            cursor: not-allowed;
        }

        /* Custom Buttons */
        .distance-btn,
        .express-btn,
        .standard-btn {
            padding: 9px 10px;
            background-color: #000;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
            transition: background-color 0.3s;
        }

        .distance-btn:hover,
        .express-btn:hover,
        .standard-btn:hover {
            background-color: green;
        }

        .distance-btn#active,
        .express-btn#active,
        .standard-btn#active {
            background-color: #ff5d01;
            color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.8);
        }

        /* Additional Options */
        .additional-options {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .item-weight-options {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            align-items: center;
        }

        .item-weight-options .radio-item {
            display: flex;
            align-items: center;
        }

        .item-weight-options input[type="radio"] {
            margin-right: 5px;
        }

        /* Suggestions Box */
        .suggestions {
            border: 1px solid #ccc;
            margin-top: 5px;
            max-height: 150px;
            overflow-y: auto;
            background: #fff;
        }

        .suggestions div {
            padding: 10px;
            cursor: pointer;
        }

        .suggestions div:hover {
            background: #f0f0f0;
        }

        /* Loader Styling */
        #loader {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.8);
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 999;
            color: #000;
            font-size: 18px;
        }

        /* Loader Animation */
        svg {
            width: 3.25em;
            transform-origin: center;
            animation: rotate4 2s linear infinite;
        }

        circle {
            fill: none;
            stroke: hsl(214, 97%, 59%);
            stroke-width: 5;
            stroke-dasharray: 1, 200;
            stroke-dashoffset: 0;
            stroke-linecap: round;
            animation: dash4 1.5s ease-in-out infinite;
        }

        @keyframes rotate4 {
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes dash4 {
            0% {
                stroke-dasharray: 1, 200;
                stroke-dashoffset: 0;
            }

            50% {
                stroke-dasharray: 90, 200;
                stroke-dashoffset: -35px;
            }

            100% {
                stroke-dashoffset: -125px;
            }
        }
    </style>
@endpush
@section('main')
    <div id="loader">
        <svg viewBox="25 25 50 50">
            <circle r="20" cy="50" cx="50"></circle>
        </svg>
    </div>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add Pickup Order</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/seller-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Add Pickup Order</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Add new order</h3>
                            </div>
                            <div class="container p-5">
                                <form id="orderForm">
                                    <input type="hidden" name="parcel_type" value="Pickup">
                                    <div class="form-group">
                                        <label class="control-label">Pickup Pincode</label>
                                        <input maxlength="6" type="text" required="required" name="deliveryPincode"
                                            id="deliveryPincode" class="form-control"
                                            placeholder="Enter Delivery Pincode" />
                                        <span id="deliveryPincodeMessage"></span>
                                    </div>

                                    <div class="form-group">
                                        <!-- Service Section -->
                                        <div id="serviceSection" style="display: none;">
                                            <label><strong>Choose Delivery Service</strong></label><br>
                                            <span id="serviceMessage" style="color: red;"></span>
                                            <div class="delivery">
                                                <div class="radio-group" style="display: flex; gap: 10px;">
                                                    <!-- Super Express Option -->
                                                    <div class="radio-item">
                                                        <input type="radio" id="optionSuperExpress" name="options"
                                                            value="SuperExpress"
                                                            onclick="showAdditionalOptions('superExpress')">
                                                        <label for="optionSuperExpress">Super Express</label>
                                                    </div>
                                                    <!-- Express Option -->
                                                    <div class="radio-item">
                                                        <input type="radio" id="optionExpress" name="options"
                                                            value="Express" onclick="showAdditionalOptions('express')">
                                                        <label for="optionExpress">Express</label>
                                                    </div>
                                                    <!-- Standard Option -->
                                                    <div class="radio-item">
                                                        <input type="radio" id="optionStandard" name="options"
                                                            value="Standard" onclick="showAdditionalOptions('standard')">
                                                        <label for="optionStandard">Standard</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Express Weight Options -->
                                    <div class="form-group" id="expressWeightOptions" style="display: none;">
                                        <div class="item-weight-options">
                                            @foreach ($expOrder as $item)
                                                @if ($item->title == null)
                                                    @php
                                                        $servicesIds = explode(',', $item->servicesId);
                                                    @endphp
                                                    @if ($item->servicesType == 'stex')
                                                        @foreach ($servicesIds as $serviceId)
                                                            @php
                                                                $serviceDetail = \App\Models\Service::find($serviceId);
                                                                $title = $serviceDetail ? $serviceDetail->title : 'N/A';
                                                                $price = $serviceDetail ? $serviceDetail->price : 0;
                                                                $type = $serviceDetail ? $serviceDetail->type : 0;
                                                                $id = $serviceDetail ? $serviceDetail->id : 0;
                                                            @endphp

                                                            <button type="button" class="express-btn"
                                                                data-service_type="{{ $type }}"
                                                                data-title="{{ $title }}"
                                                                data-price="{{ $price }}" onclick="get_price(this)"
                                                                value="{{ $id }}">{{ $title }}</button>
                                                        @endforeach
                                                    @endif
                                                @else
                                                    <button type="button" class="express-btn"
                                                        data-service_type="{{ $item->type }}"
                                                        data-title="{{ $item->title }}" data-price="{{ $item->price }}"
                                                        onclick="get_price(this)"
                                                        value="{{ $item->id }}">{{ $item->title }}</button>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Standard Weight Options -->
                                    <div class="form-group" id="standardWeightOptions" style="display: none;">
                                        <div class="item-weight-options">
                                            @foreach ($stdOrder as $item)
                                                @if ($item->title == null)
                                                    @php
                                                        $servicesIds = explode(',', $item->servicesId);
                                                    @endphp

                                                    @if ($item->servicesType == 'stss')
                                                        @foreach ($servicesIds as $serviceId)
                                                            @php
                                                                $serviceDetail = \App\Models\Service::find($serviceId);
                                                                $title = $serviceDetail ? $serviceDetail->title : 'N/A';
                                                                $price = $serviceDetail ? $serviceDetail->price : 0;
                                                                $type = $serviceDetail ? $serviceDetail->type : 0;
                                                                $id = $serviceDetail ? $serviceDetail->id : 0;
                                                            @endphp

                                                            <button type="button" class="express-btn"
                                                                data-service_type="{{ $type }}"
                                                                data-title="{{ $title }}"
                                                                data-price="{{ $price }}" onclick="get_price(this)"
                                                                value="{{ $id }}">{{ $title }}</button>
                                                        @endforeach
                                                    @endif
                                                @else
                                                    <button type="button" class="express-btn"
                                                        data-service_type="{{ $item->type }}"
                                                        data-title="{{ $item->title }}" data-price="{{ $item->price }}"
                                                        onclick="get_price(this)"
                                                        value="{{ $item->id }}">{{ $item->title }}</button>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Address Input for Super Express -->
                                    <div class="form-group" id="addressSection" style="display: none;">
                                        <label for="address1"><strong>Pick-up Address</strong></label>
                                        <input type="text" id="address1" class="form-control"
                                            placeholder="Enter pick-up address">
                                        <label for="address2"><strong>Delivery Address</strong></label>
                                        <input type="text" id="address2" class="form-control"
                                            placeholder="Enter delivery address">
                                        <button type="button" onclick="calculateDistance()"
                                            class="btn btn-primary mt-3">Calculate Distance</button>
                                        <div id="distance" class="mt-2"></div>
                                        @foreach ($seOrder as $item)
                                            @if ($item->title == null)
                                                @php
                                                    $servicesIds = explode(',', $item->servicesId);
                                                @endphp

                                                @if ($item->servicesType == 'stse')
                                                    @foreach ($servicesIds as $serviceId)
                                                        @php
                                                            $serviceDetail = \App\Models\Service::find($serviceId);
                                                            $price = $serviceDetail ? $serviceDetail->price : 0;
                                                            $type = $serviceDetail ? $serviceDetail->type : 0;
                                                            $id = $serviceDetail ? $serviceDetail->id : 0;
                                                            $title = $serviceDetail ? $serviceDetail->title : 'NA';
                                                        @endphp
                                                        @if ($type == 'stse' && $title == '3km')
                                                            <input type="hidden" id="km3"
                                                                value="{{ $price }}">
                                                        @endif
                                                        @if ($type == 'stse' && $title == '1km')
                                                            <input type="hidden" id="km1"
                                                                value="{{ $price }}">
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @else
                                                @if ($item->type == 'se' && $item->title == '3km')
                                                    <input type="hidden" id="km3" value="{{ $item->price }}">
                                                @endif
                                                @if ($item->type == 'se' && $item->title == '1km')
                                                    <input type="hidden" id="km1" value="{{ $item->price }}">
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>

                                    <!-- Estimate Price Section -->
                                    <div class="form-group">
                                        <label for="estimatePrice"><strong>Estimate Price</strong></label>
                                        <input type="text" class="form-control" id="estimatePrice" placeholder="₹ 0"
                                            readonly>
                                    </div>

                                    <input type="hidden" name="service_type" id="service_type" value="">
                                    <input type="hidden" name="service_title" id="service_title" value="">
                                    <input type="hidden" name="service_price" id="service_price" value="">


                                    <div id="receiverSection" style="display: none;">
                                        <div class="container">

                                            <div class="row">
                                                <div class="col-md-12" style="margin-left: 10px;">
                                                    <h2 style="text-align:left">Receiver Details</h2>
                                                    <div class="row">
                                                        <label for="namer">Receiver Name</label>
                                                        <input type="text" id="namer" name="receiver_name"
                                                            class="form-control mb-3" placeholder="Enter Receiver Name"
                                                            required>
                                                        <label for="numberr">Receiver Contact Number</label>
                                                        <input type="text" id="numberr" name="receiver_number"
                                                            pattern="[6789][0-9]{9}" class="form-control mb-3"
                                                            placeholder="Enter Receiver Contact Number" required>
                                                        <label for="emailr">Receiver Email</label>
                                                        <input type="email" id="emailr" name="receiver_email"
                                                            class="form-control mb-3" placeholder="Enter Receiver Email"
                                                            required>
                                                        <label for="addressr">Receiver Full Address</label>
                                                        <input type="text" id="addressr" name="receiver_address"
                                                            class="form-control mb-3" placeholder="Enter Receiver Address"
                                                            required>
                                                        <label for="receiverPincode">Receiver Pincode</label>
                                                        <input type="text" id="receiverPincode" name="receiverPincode"
                                                            class="form-control mb-3" placeholder="Enter Receiver Pincode"
                                                            required readonly>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Payment options and total cost -->
                                            <div class="row">
                                                <div class="col-12 col-lg-8 d-flex ">
                                                    <div>
                                                        <input class="m-1 me-1" type="radio" id="cod"
                                                            name="payment_methods" value="cod"
                                                            onchange="updatePrice()" required>
                                                    </div>
                                                    <div>
                                                        <label for="cod">Cash On Delivery <em
                                                                class="text-success text-center justify-content-center "
                                                                style="font-size: 12px;">(COD charges ₹ 30 or 2 % which
                                                                ever is higher)</em></label>
                                                    </div>
                                                </div>

                                                <div class="col-12 col-lg-4 d-flex">
                                                    <div>
                                                        <input class="m-1" type="radio" id="online"
                                                            name="payment_methods" value="online"
                                                            onchange="updatePrice()" required>
                                                    </div>
                                                    <div>
                                                        <label for="online">Prepaid Order</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row ">
                                                <div class="col-12 d-flex">
                                                    <div>
                                                        <input class="m-1" type="checkbox" name="insurance"
                                                            id="insurance" value="Yes" onchange="updatePrice()">
                                                    </div>
                                                    <div>
                                                        <label for="insurance">Do you want to insurance your order? <em
                                                                class="text-success" style="font-size: 12px;">(Insurance
                                                                charges 50 or 1 % which ever is higher)</em></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row w-100">
                                                <div class="col-12">
                                                    <!-- Total cost -->
                                                    <input type="hidden" id="amount" name="price" value="0">
                                                    <h4>Total Cost - ₹ <span id="amounts">0</span></h4>
                                                </div>
                                            </div>
                                            <!-- Submit button -->
                                            <button class="btn btn-primary nextBtn pull-right">Order Now</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAx_5V0k3AP2ZxGMNZ7TSy0LnhwChWuDoE&libraries=places">
    </script>
    <script>
        $(document).ready(function() {
            $('#deliveryPincode').on('change', function() {
                var pinCode = $(this).val();
                var setMsg = $('#deliveryPincodeMessage');
                setMsg.text("Checking...");

                $.ajax({
                    url: "{{ route('seller.checkPinCode') }}",
                    type: 'POST',
                    data: {
                        pin: pinCode,
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            setMsg.text(response.message);
                            setMsg.css('color', 'green');
                            $('#serviceSection').show();
                            $('#receiverPincode').val(pinCode);
                        } else {
                            setMsg.text(response.message);
                            setMsg.css('color', 'red');
                            $('#serviceSection').hide();
                            $('#expressWeightOptions').hide();
                            $('#standardWeightOptions').hide();
                            $('#receiverSection').hide();
                            $('#estimatePrice').val('₹ 0.0');
                        }
                    },
                    error: function(err) {
                        Toast("error", "An unexpected error occurred. Please try again.");
                    }
                });
            });

            $("#orderForm").on("submit", function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                // let formData = $(this).serialize();
                $.ajax({
                    url: "{{ route('seller.addOrderParcel') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        $('#orderForm')[0].reset();
                        Toast(response.success ? "success" : "error", response.message);
                        $('#serviceSection').hide();
                        $('#expressWeightOptions').hide();
                        $('#standardWeightOptions').hide();
                        $('#receiverSection').hide();
                        $('#estimatePrice').val('₹ 0.0');
                    },
                    error: function(err) {
                        Toast("error",
                            "An unexpected error occurred. Please try again.");
                    }
                });
            });
            updateTotalCost();
        });

        function showAdditionalOptions(option) {
            // Reset the estimate price
            document.getElementById('estimatePrice').value = "₹ 0";

            // Hide all options initially
            const expressWeightOptions = document.getElementById('expressWeightOptions');
            const standardWeightOptions = document.getElementById('standardWeightOptions');
            const addressSection = document.getElementById('addressSection');

            expressWeightOptions.style.display = 'none';
            standardWeightOptions.style.display = 'none';
            addressSection.style.display = 'none';

            // Show the relevant section based on the selected option
            if (option === 'superExpress') {
                addressSection.style.display = 'block';
            } else if (option === 'express') {
                expressWeightOptions.style.display = 'flex';
            } else if (option === 'standard') {
                standardWeightOptions.style.display = 'flex';
            }
        }

        function get_price(button) {
            // Get data attributes from the button
            var serviceName = button.getAttribute('data-service_type');
            var title = button.getAttribute('data-title');
            var price = button.getAttribute('data-price');

            // Update the estimated price input field
            $('#estimatePrice').val('₹ ' + price);

            // Save data to localStorage
            localStorage.setItem('serviceName', serviceName);
            localStorage.setItem('title', title);
            localStorage.setItem('price', price);

            // Set the service type and title in the hidden input fields
            $('#service_type').val(serviceName);
            $('#service_title').val(title);
            $('#service_price').val(price);

            if (price > 0) {
                $('#receiverSection').show();
            }

            updateTotalCost();
        }

        // updatePrice
        function updatePrice() {
            var paymentMethod = document.querySelector('input[name="payment_methods"]:checked');
            var insuranceChecked = document.getElementById('insurance').checked;
            var price = parseFloat(localStorage.getItem('price'));
            var totalPrice = price;

            if (paymentMethod && paymentMethod.value === 'cod') {
                var codCharges = Math.max(30, price * 0.02);
                totalPrice += codCharges;
            }

            if (insuranceChecked) {
                var insuranceCharges = Math.max(50, price * 0.01);
                totalPrice += insuranceCharges;
            }

            document.getElementById('amount').value = totalPrice;
            document.getElementById('amounts').textContent = totalPrice;
        }

        //----------------------------------------------------------------------------------//
        //                               calculate Price Based on distance
        //----------------------------------------------------------------------------------//
        function calculatePrice(distance) {
            let price = 0;
            if (distance <= 3) {
                price = 50;
            } else {
                price = 50 + (distance - 3) * 5;
            }
            document.getElementById('estimatePrice').value = "₹ " + price;
            localStorage.setItem('serviceName', 'Super-Express');
            localStorage.setItem('title', distance + ' km');
            localStorage.setItem('price', price);

            // Update the total cost
            updateTotalCost();
        }
        //----------------------------------------------------------------------------------//
        //                               Google maps Autocomplete                           
        //----------------------------------------------------------------------------------//
        function initializeAutocomplete() {
            const address1 = document.getElementById('address1');
            const address2 = document.getElementById('address2');

            const autocomplete1 = new google.maps.places.Autocomplete(address1);
            const autocomplete2 = new google.maps.places.Autocomplete(address2);

            autocomplete1.addListener('place_changed', function() {
                const place = autocomplete1.getPlace();
                if (place.geometry) {
                    address1.dataset.lat = place.geometry.location.lat();
                    address1.dataset.lon = place.geometry.location.lng();
                }
            });

            autocomplete2.addListener('place_changed', function() {
                const place = autocomplete2.getPlace();
                if (place.geometry) {
                    address2.dataset.lat = place.geometry.location.lat();
                    address2.dataset.lon = place.geometry.location.lng();
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            initializeAutocomplete();
        });
        //----------------------------------------------------------------------------------//
        //                               Google maps Distance Calculation                          
        //----------------------------------------------------------------------------------//
        function calculateDistance() {
            const address1 = document.getElementById('address1');
            const address2 = document.getElementById('address2');
            const lat1 = address1.dataset.lat;
            const lon1 = address1.dataset.lon;
            const lat2 = address2.dataset.lat;
            const lon2 = address2.dataset.lon;
            const km3 = parseFloat(document.getElementById('km3').value) || 50;
            const km1 = parseFloat(document.getElementById('km1').value) || 5;

            if (lat1 && lon1 && lat2 && lon2) {
                const origin = `${lat1},${lon1}`;
                const destination = `${lat2},${lon2}`;
                const proxyUrl = `/proxy?origin=${origin}&destination=${destination}`;

                document.getElementById('loader').style.display = 'flex';

                fetch(proxyUrl)
                    .then(response => response.json())
                    .then(data => {
                        var distance = data.routes[0].legs[0].distance.text;
                        console.log(data.routes[0])
                        if (data) {
                            document.getElementById('distance').textContent = 'Distance: ' + distance;

                            const distanceNumber = parseFloat(distance.replace(' km', '').replace(',', '.'));
                            const approxDis = Math.ceil(distanceNumber)
                            document.getElementById('distance').textContent = 'Distance Approx: ' + approxDis + ' km';
                            let price = 0;
                            if (approxDis <= 3) {
                                price = km3;
                            } else {
                                price = km3 + (approxDis - 3) * km1;
                            }

                            // Update the estimated price input field
                            document.getElementById('estimatePrice').value = "₹ " + price;
                            localStorage.setItem('title', "SuperExpress");
                            localStorage.setItem('price', price);

                            // Set the service type and title in the hidden input fields
                            $('#service_type').val('SuperExpress');
                            $('#service_title').val(approxDis + ' km');
                            $('#service_price').val(price);

                            if (price > 0) {
                                $('#receiverSection').show();
                            }

                            // Update the total cost
                            updateTotalCost();
                        } else {
                            document.getElementById('distance').textContent = 'No route found.';
                        }
                        document.getElementById('loader').style.display = 'none';
                    })
                    .catch(error => {
                        document.getElementById('distance').textContent =
                            'Error calculating distance. Please try again.';
                        console.error('Error calculating distance:', error);
                        document.getElementById('loader').style.display = 'none';
                    });
            } else {
                document.getElementById('distance').textContent = 'Please select valid addresses from the suggestions.';
            }
        }

        //--------------- Update Total Cost ---------------//
        function updateTotalCost() {
            var price = parseFloat(localStorage.getItem('price')) || 0;
            var totalPrice = price;

            var paymentMethod = document.querySelector('input[name="payment_methods"]:checked');
            if (paymentMethod && paymentMethod.value === 'cod') {
                var codCharges = Math.max(30, price * 0.02);
                totalPrice += codCharges;
            }

            var insuranceChecked = document.getElementById('insurance').checked;
            if (insuranceChecked) {
                var insuranceCharges = Math.max(50, price * 0.01);
                totalPrice += insuranceCharges;
            }

            document.getElementById('amount').value = totalPrice;
            document.getElementById('amounts').textContent = totalPrice;
        }
    </script>
@endpush
