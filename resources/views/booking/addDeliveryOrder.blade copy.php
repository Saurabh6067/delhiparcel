@extends('booking.layout.main')

@push('style')
    <style>
        /* General Styling for the Form Layout */
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

/* Button Styles for Wizard Steps */
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

/* Custom Buttons for Weight Options and Distance Calculation */
.distance-btn,
.express-btn,
.standard-btn {
    padding: 9px 10px;
    background-color: #000;
    color: #fff;
    border: 2px solid #ccc; /* Default light gray border for unselected state */
    border-radius: 5px;
    cursor: pointer;
    margin-right: 10px;
    transition: all 0.3s, transform 0.2s; /* Smooth transition for all properties and scaling */
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
    border: 2px solid red; /* Red border for selected state */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.8);
    transform: scale(1.05); /* Slightly scale up the button when selected */
    font-weight: bold; /* Make text bold for better visibility */
}

/* Styling for Additional Options Sections */
.additional-options {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}

.item-weight-options,
.item-type-options {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    align-items: center;
}

.item-weight-options .radio-item,
.item-type-options .radio-item {
    display: flex;
    align-items: center;
}

.item-weight-options input[type="radio"],
.item-type-options input[type="radio"] {
    margin-right: 5px;
}

/* Default Border for All Radio Labels */
.radio-item label {
    border: 2px solid #ccc; /* Light gray border for unselected state */
    padding: 5px 10px;
    border-radius: 5px;
    transition: all 0.3s; /* Smooth transition for border, background, etc. */
}

/* Highlight for Selected Radio Buttons (Item Type, Delivery Service) */
.radio-item input[type="radio"]:checked + label {
    background-color: blue !important; /* Updated to blue background for selected state */
    color: #fff !important;
    border: 2px solid red !important; /* Red border for selected state */
    padding: 5px 10px;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.8);
    transition: all 0.3s;
    transform: scale(1.05); /* Add scale effect for consistency */
}

/* Suggestions Box for Google Maps Autocomplete */
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

/* Loader Styling for Distance Calculation */
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
    <!-- Loader for Distance Calculation -->
    <div id="loader">
        <svg viewBox="25 25 50 50">
            <circle r="20" cy="50" cx="50"></circle>
        </svg>
    </div>

    <!-- Main Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header Section -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add Delivery Order</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/booking-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Add Delivery Order</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content Section -->
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
                                    <input type="hidden" name="parcel_type" value="delivery">

                                    <!-- Delivery Pincode Input -->
                                    <div class="form-group">
                                        <label class="control-label">Delivery Pincode</label>
                                        <input maxlength="6" type="text" required="required" name="deliveryPincode"
                                            id="deliveryPincode" class="form-control"
                                            placeholder="Enter Delivery Pincode" />
                                        <span id="deliveryPincodeMessage"></span>
                                    </div>

                                    <!-- Item Type Selection Section -->
                                    <div class="form-group">
                                        <div id="itemTypeSection" style="display: none;">
                                            <label><strong>Choose Item Type</strong></label><br>
                                            <span id="itemTypeMessage" style="color: red;"></span>
                                            <div class="item-type">
                                                <div class="item-type-options" style="display: flex; gap: 10px;">
                                                    <!-- Document Option -->
                                                    <div class="radio-item">
                                                        <input type="radio" id="itemDocument" name="itemType"
                                                            value="Document" onclick="selectItemType('Document')">
                                                        <label for="itemDocument">Document</label>
                                                    </div>
                                                    <!-- Parcel Option -->
                                                    <div class="radio-item">
                                                        <input type="radio" id="itemParcel" name="itemType"
                                                            value="Parcel" onclick="selectItemType('Parcel')">
                                                        <label for="itemParcel">Parcel</label>
                                                    </div>
                                                    <!-- Fragile Item Option -->
                                                    <div class="radio-item">
                                                        <input type="radio" id="itemFragile" name="itemType"
                                                            value="Fragile Item" onclick="selectItemType('Fragile Item')">
                                                        <label for="itemFragile">Fragile Item</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delivery Service Selection Section -->
                                    <div class="form-group">
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
                                                        value="{{ $id }}">{{ $item->title }}</button>
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
                                                            <button type="button" class="standard-btn"
                                                                data-service_type="{{ $type }}"
                                                                data-title="{{ $title }}"
                                                                data-price="{{ $price }}" onclick="get_price(this)"
                                                                value="{{ $id }}">{{ $title }}</button>
                                                        @endforeach
                                                    @endif
                                                @else
                                                    <button type="button" class="standard-btn"
                                                        data-service_type="{{ $item->type }}"
                                                        data-title="{{ $item->title }}" data-price="{{ $item->price }}"
                                                        onclick="get_price(this)"
                                                        value="{{ $id }}">{{ $title }}</button>
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

                                    <!-- Hidden Inputs for Form Submission -->
                                    <input type="hidden" name="service_type" id="service_type" value="">
                                    <input type="hidden" name="service_title" id="service_title" value="">
                                    <input type="hidden" name="service_price" id="service_price" value="">
                                    <input type="hidden" name="item_type" id="item_type" value="">

                                    <!-- Receiver Details Section -->
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

                                            <!-- Payment Options and Total Cost -->
                                            <div class="row">
                                                <div class="col-12 col-lg-8 d-flex">
                                                    <div>
                                                        <input class="m-1 me-1" type="radio" id="cod"
                                                            name="payment_methods" value="cod"
                                                            onchange="updatePrice()" required>
                                                    </div>
                                                    <div>
                                                        <label for="cod">Cash On Delivery <em
                                                                class="text-success text-center justify-content-center"
                                                                style="font-size: 12px;">(COD charges ₹ 30 or 2 % which
                                                                ever is higher)</em></label>
                                                        <input type="number" id="cod_amount" name="cod_amount" class="form-control mt-2" placeholder="Enter COD ₹" value="0" onchange="updatePrice()">
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
                                            <div class="row">
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
                                                    <!-- Total Cost -->
                                                    <input type="hidden" id="amount" name="price" value="0">
                                                    <h4>Total Cost - ₹ <span id="amounts">0</span></h4>
                                                </div>
                                            </div>
                                            <!-- Submit Button -->
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
    <!-- Google Maps API for Autocomplete and Distance Calculation -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAx_5V0k3AP2ZxGMNZ7TSy0LnhwChWuDoE&libraries=places">
    </script>
    <script>
        $(document).ready(function() {
            // Clear localStorage on page load to forget previous values
            localStorage.clear();

            // Pincode Validation on Change
            $('#deliveryPincode').on('change', function() {
                var pinCode = $(this).val();
                var setMsg = $('#deliveryPincodeMessage');
                setMsg.text("Checking...");

                $.ajax({
                    url: "{{ route('booking.checkPinCode') }}",
                    type: 'POST',
                    data: {
                        pin: pinCode,
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            setMsg.text(response.message);
                            setMsg.css('color', 'green');
                            $('#itemTypeSection').show();
                            $('#receiverPincode').val(pinCode);
                        } else {
                            setMsg.text(response.message);
                            setMsg.css('color', 'red');
                            $('#itemTypeSection').hide();
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

            // Form Submission Handler
            $("#orderForm").on("submit", function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('booking.addOrderParcel') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        $('#orderForm')[0].reset();
                        Toast(response.success ? "success" : "error", response.message);
                        $('#itemTypeSection').hide();
                        $('#serviceSection').hide();
                        $('#expressWeightOptions').hide();
                        $('#standardWeightOptions').hide();
                        $('#receiverSection').hide();
                        $('#estimatePrice').val('₹ 0.0');
                        localStorage.clear(); // Clear localStorage after form submission
                    },
                    error: function(err) {
                        Toast("error", "An unexpected error occurred. Please try again.");
                    }
                });
            });

            // Initialize Total Cost on Page Load
            updateTotalCost();
        });

        // Handle Item Type Selection
        function selectItemType(itemType) {
            $('#item_type').val(itemType);
            $('#serviceSection').show();
        }

        // Handle Delivery Service Option Selection
        function showAdditionalOptions(option) {
            document.getElementById('estimatePrice').value = "₹ 0";
            const expressWeightOptions = document.getElementById('expressWeightOptions');
            const standardWeightOptions = document.getElementById('standardWeightOptions');
            const addressSection = document.getElementById('addressSection');

            expressWeightOptions.style.display = 'none';
            standardWeightOptions.style.display = 'none';
            addressSection.style.display = 'none';

            document.querySelectorAll('.express-btn, .standard-btn').forEach(btn => {
                btn.removeAttribute('id', 'active');
            });

            if (option === 'superExpress') {
                addressSection.style.display = 'block';
            } else if (option === 'express') {
                expressWeightOptions.style.display = 'flex';
            } else if (option === 'standard') {
                standardWeightOptions.style.display = 'flex';
            }

            updateTotalCost();
        }

        // Handle Weight Option Selection
        function get_price(button) {
            document.querySelectorAll('.express-btn, .standard-btn').forEach(btn => {
                btn.removeAttribute('id', 'active');
            });

            button.setAttribute('id', 'active');

            var serviceName = button.getAttribute('data-service_type');
            var title = button.getAttribute('data-title');
            var price = button.getAttribute('data-price');

            $('#estimatePrice').val('₹ ' + price);
            $('#service_type').val(serviceName);
            $('#service_title').val(title);
            $('#service_price').val(price);

            var itemType = $('#item_type').val();
            if (price > 0 && itemType) {
                $('#receiverSection').show();
            } else {
                $('#receiverSection').hide();
            }

            updateTotalCost();
        }

        // Update Price When Payment Method or Insurance Changes
        function updatePrice() {
            updateTotalCost();
        }

        // Calculate Price Based on Distance
        function calculatePrice(distance) {
            let price = 0;
            if (distance <= 3) {
                price = 50;
            } else {
                price = 50 + (distance - 3) * 5;
            }
            document.getElementById('estimatePrice').value = "₹ " + price;

            $('#service_type').val('SuperExpress');
            $('#service_title').val(distance + ' km');
            $('#service_price').val(price);

            var itemType = $('#item_type').val();
            if (price > 0 && itemType) {
                $('#receiverSection').show();
            } else {
                $('#receiverSection').hide();
            }

            updateTotalCost();
        }

        // Initialize Google Maps Autocomplete
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

        // Calculate Distance Using Google Maps API
        function calculateDistance() {
            const address1 = document.getElementById('address1');
            const address2 = document.getElementById('address2');
            const lat1 = address1.dataset.lat;
            const lon1 = address1.dataset.lon;
            const lat2 = address2.dataset.lat;
            const lon2 = address2.dataset.lon;
            const km3 = parseFloat(document.getElementById('km3').value) || 0;
            const km1 = parseFloat(document.getElementById('km1').value) || 0;

            if (lat1 && lon1 && lat2 && lon2) {
                const origin = `${lat1},${lon1}`;
                const destination = `${lat2},${lon2}`;
                const proxyUrl = `/proxy?origin=${origin}&destination=${destination}`;

                document.getElementById('loader').style.display = 'flex';

                fetch(proxyUrl)
                    .then(response => response.json())
                    .then(data => {
                        var distance = data.routes[0].legs[0].distance.text;
                        if (data) {
                            document.getElementById('distance').textContent = 'Distance: ' + distance;

                            const distanceNumber = parseFloat(distance.replace(' km', '').replace(',', '.'));
                            const approxDis = Math.ceil(distanceNumber);
                            document.getElementById('distance').textContent = 'Distance Approx: ' + approxDis + ' km';
                            let price = 0;
                            if (approxDis <= 3) {
                                price = km3;
                            } else {
                                price = km3 + (approxDis - 3) * km1;
                            }

                            document.getElementById('estimatePrice').value = "₹ " + price;

                            $('#service_type').val('SuperExpress');
                            $('#service_title').val(approxDis + ' km');
                            $('#service_price').val(price);

                            var itemType = $('#item_type').val();
                            if (price > 0 && itemType) {
                                $('#receiverSection').show();
                            } else {
                                $('#receiverSection').hide();
                            }

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

        // Update Total Cost Including COD and Insurance Charges
        function updateTotalCost() {
            // Get the base price directly from the estimatePrice input
            var estimatePrice = document.getElementById('estimatePrice').value;
            var basePrice = parseFloat(estimatePrice.replace('₹ ', '')) || 0;
            var totalPrice = basePrice;

            // Apply COD charges if selected, using the user-entered COD amount
            var paymentMethod = document.querySelector('input[name="payment_methods"]:checked');
            if (paymentMethod && paymentMethod.value === 'cod') {
                var codAmount = parseFloat(document.getElementById('cod_amount').value) || 0;
                if (codAmount > 0) {
                    totalPrice += codAmount; // Add the user-entered COD amount
                }
                // If codAmount is 0, do not add any COD charges
            }

            // Apply insurance charges if selected
            var insuranceChecked = document.getElementById('insurance').checked;
            if (insuranceChecked) {
                var insuranceCharges = Math.max(50, basePrice * 0.01); // ₹50 or 1% of base price, whichever is higher
                totalPrice += insuranceCharges;
            }

            // Update the total cost display and hidden input
            document.getElementById('amount').value = totalPrice.toFixed(2);
            document.getElementById('amounts').textContent = totalPrice.toFixed(2);

            // Ensure the estimate price remains unchanged
            document.getElementById('estimatePrice').value = "₹ " + basePrice.toFixed(2);
        }
    </script>
@endpush