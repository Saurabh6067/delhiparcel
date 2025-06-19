<style>
    .selected {
        background-color: #ff5d01 !important;
        /* Orange background for selected button */
        color: #fff !important;
        /* White text color for contrast */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.8) !important;
        /* Shadow effect */
    }

    @media(max-width:768px) {
        .radio-item {
            font-size: 28px;
            display: inline;
        }

        .radio-item button {
            font-size: 14px;
        }

        .hero {
            background-image: none;
        }
    }
</style>
<div class="hero" style="background-image: url('{{ asset('web/images/background.png') }}')">
    <div class="hero-content">
        <div class="container-form">


            <h1 class="mb-4">Book your parcel</h1>



            <form id="bookParcel" action="{{ route('web.parcelDetails') }}" method="POST">
                <div class="form-group mb-3">
                    <label for="pickupPincode"><strong>Pick-up Pincode</strong></label><br>
                    <input type="text" class="form-control" id="pickupPincode" name="pickupPincode"
                        placeholder="Pick-up Pincode" required>
                    <span id="pickupPincodeMessage" style="color: red;"></span>
                </div>
                <div class="form-group mb-3">
                    <label for="deliveryPincode"><strong>Delivery Pincode</strong></label><br>
                    <input type="text" class="form-control" id="deliveryPincode" name="deliveryPincode"
                        placeholder="Delivery Pincode" required disabled>
                    <span id="deliveryPincodeMessage" style="color: red;"></span>
                </div>

                <div class="form-group mb-3">
                    <div id="serviceSection" style="display: none;">
                        <label><strong>Choose Delivery Service</strong></label>
                        <div class="row">
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="optionSuperExpress" name="options"
                                        value="SuperExpress" onclick="showAdditionalOptions('superExpress')">
                                    <label class="form-check-label" for="optionSuperExpress">
                                        Super Express
                                        <i title="Guaranteed Delivery Within 4 Hours"
                                            style="background: blue; padding: 6px; color: white; font-weight: bold; border-radius: 50%;">i</i>
                                    </label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="optionExpress" name="options"
                                        value="Express" onclick="showAdditionalOptions('express')">
                                    <label class="form-check-label" for="optionExpress">
                                        Express
                                        <i title="Delivery Within Same Days"
                                            style="background: blue; padding: 6px; color: white; font-weight: bold; border-radius: 50%;">i</i>
                                    </label>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="optionStandard" name="options"
                                        value="Standard" onclick="showAdditionalOptions('standard')">
                                    <label class="form-check-label" for="optionStandard">
                                        Standard
                                        <span title="Delivery Within 2 Days"
                                            style="background: blue; padding: 6px; color: white; font-weight: bold; border-radius: 50%;">i</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- New section to handle distance options for Super Express and weight options for others -->
                <div class="form-group mb-3" id="weightSection" style="display: none;">
                    <!-- Super Express distance options (in kilometers) -->
                    <div id="superExpressDistanceOptions" class="item-weight-options" style="display: none;">
                        <div id="addressSection">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group mb-3">
                                        <label for="address1"><strong>Pick-up Address</strong></label><br>
                                        <input type="text" id="address1" name="pickupAddress" class="form-control"
                                            placeholder="Enter pick-up address">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group mb-2">
                                        <label for="address2"><strong>Delivery Address</strong></label><br>
                                        <input type="text" id="address2" name="deliveryAddress" class="form-control"
                                            placeholder="Enter delivery address">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <button type="button" onclick="calculateDistance()"
                                            class="btn btn-sm btn-primary float-start calculate-distance-btn">
                                            Calculate Price
                                        </button>
                                        <div id="distance"></div>
                                        @foreach ($se as $key => $data)
                                            @if ($data->type == 'se' && $data->title == '3km')
                                                <input type="hidden" id="km3" value="{{ $data->price }}">
                                            @endif
                                            @if ($data->type == 'se' && $data->title == '1km')
                                                <input type="hidden" id="km1" value="{{ $data->price }}">
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p style="color:#000; margin-bottom:5px">Parcel weight</p>
                    <div id="expressWeightOptions" class="item-weight-options" style="display: none;">
                        <div class="radio-item">
                            @forelse ($ex as $data)
                                <button type="button" class="btn btn-sm btn-dark express-btn me-3 p-1"
                                    data-service_type="{{ $data->type }}" data-id="{{ $data->id }}"
                                    data-price="{{ $data->price }}" onclick="get_price(this)">{{ $data->title }}</button>
                            @empty
                                <p class="text-center">No express services available at the moment.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Standard weight options (in kg) -->
                    <div id="standardWeightOptions" class="item-weight-options" style="display: none;">
                        <div class="radio-item">
                            @forelse ($ss as $data)
                                <button type="button" class="btn btn-sm btn-dark standard-btn me-3 p-1"
                                    data-service_type="{{ $data->type }}" data-id="{{ $data->id }}"
                                    data-price="{{ $data->price }}" onclick="get_price(this)">{{ $data->title }}</button>
                            @empty
                                <p class="text-center">No standard services available at the moment.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="estimatePrice"><strong>Estimate Price</strong></label><br>
                    <input type="text" class="form-control" name="price" id="estimatePrice" placeholder="₹ 0" readonly>
                </div>

                <div class="form-group mb-3">
                    <button class="btn btn-sm rainbow-hover d-none" type="submit" id="bookParcelBtn" disabled>
                        <span class="sp">Book Parcel</span>
                    </button>
                </div>
                <input type="hidden" name="service_type" id="service_type" value="">
                <input type="hidden" name="service_id" id="service_id" value="">
                <input type="hidden" name="service_price" id="service_price" value="">
            </form>
        </div>
    </div>
</div>
@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAx_5V0k3AP2ZxGMNZ7TSy0LnhwChWuDoE&libraries=places">
    </script>
    <script>
        $(document).ready(function () {
            function validatePincodeFormat(pinCode, messageElement) {
                const pinCodeValue = pinCode.trim();
                const pinCodeRegex = /^\d{6}$/; // Regular expression for exactly 6 digits
                const setMsg = $(messageElement);

                if (!pinCodeRegex.test(pinCodeValue)) {
                    setMsg.text("Pincode must be exactly 6 digits.");
                    setMsg.css('color', 'red');
                    return false;
                }
                return true;
            }

            function updateBookParcelButton() {
                const pickupMessage = $('#pickupPincodeMessage').text();
                const deliveryMessage = $('#deliveryPincodeMessage').text();

                // Enable bookParcelBtn only if both messages are exactly "Available"
                if (pickupMessage === 'Available' && deliveryMessage === 'Available') {
                    $('#bookParcelBtn').prop('disabled', false);
                } else {
                    $('#bookParcelBtn').prop('disabled', true);
                }
            }

            function checkPincode(inputElement, messageElement) {
                const pinCode = $(inputElement).val();
                const setMsg = $(messageElement);

                // Validate pincode format first
                if (!validatePincodeFormat(pinCode, messageElement)) {
                    if (inputElement.id === 'pickupPincode') {
                        $('#deliveryPincode').prop('disabled', true); // Disable deliveryPincode if pickupPincode is invalid
                        $('#serviceSection, #expressWeightOptions, #standardWeightOptions, #receiverSection').hide();
                        $('#estimatePrice').val('₹ 0');
                    }
                    updateBookParcelButton(); // Update button state on invalid format
                    return;
                }

                setMsg.text("Checking...");

                $.ajax({
                    url: "{{ route('web.checkPinCode') }}",
                    type: 'POST',
                    data: {
                        pin: pinCode
                    },
                    dataType: 'json',
                    success: function (response) {
                        setMsg.text(response.message);
                        if (response.success) {
                            setMsg.css('color', 'green');
                            if (inputElement.id === 'pickupPincode') {
                                $('#deliveryPincode').prop('disabled', false); // Enable deliveryPincode field
                                $('#serviceSection').show();
                                $('#receiverPincode').val(pinCode);
                            }
                        } else {
                            setMsg.css('color', 'red');
                            if (inputElement.id === 'pickupPincode') {
                                $('#deliveryPincode').prop('disabled', true); // Disable deliveryPincode field
                                $('#serviceSection, #expressWeightOptions, #standardWeightOptions, #receiverSection').hide();
                                $('#estimatePrice').val('₹ 0');
                            }
                        }
                        updateBookParcelButton(); // Update button state after AJAX response
                    },
                    error: function (err) {
                        setMsg.text("An unexpected error occurred. Please try again.");
                        setMsg.css('color', 'red');
                        if (inputElement.id === 'pickupPincode') {
                            $('#deliveryPincode').prop('disabled', true); // Disable deliveryPincode field on error
                            $('#serviceSection, #expressWeightOptions, #standardWeightOptions, #receiverSection').hide();
                            $('#estimatePrice').val('₹ 0');
                        }
                        Toast("error", "An unexpected error occurred. Please try again.");
                        updateBookParcelButton(); // Update button state on error
                    }
                });
            }

            $('#pickupPincode').on('input', function () {
                checkPincode(this, '#pickupPincodeMessage');
            });

            $('#deliveryPincode').on('input', function () {
                checkPincode(this, '#deliveryPincodeMessage');
            });

            // Initialize button as disabled
            $('#bookParcelBtn').prop('disabled', true);
        });

        function showAdditionalOptions(option) {
            // Reset the price and selected state when switching between services
            document.getElementById('estimatePrice').value = "₹ 0";
            $('.express-btn, .standard-btn, .calculate-distance-btn').removeClass('selected'); // Remove selected class from all buttons

            // Hide all weight/distance options by default
            document.getElementById('superExpressDistanceOptions').style.display = 'none';
            document.getElementById('expressWeightOptions').style.display = 'none';
            document.getElementById('standardWeightOptions').style.display = 'none';
            document.getElementById('addressSection').style.display = 'none';

            // Show distance options for Super Express and weight options for others
            if (option === 'superExpress') {
                document.getElementById('superExpressDistanceOptions').style.display = 'flex';
                document.getElementById('addressSection').style.display = 'block';
            } else if (option === 'express') {
                document.getElementById('expressWeightOptions').style.display = 'flex';
            } else if (option === 'standard') {
                document.getElementById('standardWeightOptions').style.display = 'flex';
            }

            // Show the weight/distance section
            document.getElementById('weightSection').style.display = 'block';

            // Hide delivery message when switching options
            $('#deliveryMessage').remove();
        }

        function get_price(button) {
            document.querySelectorAll('.express-btn, .standard-btn').forEach(btn => {
                btn.classList.remove('selected');
            });
            button.classList.add('selected');

            // Get data attributes from the button
            var serviceName = button.getAttribute('data-service_type');
            var title = button.getAttribute('data-id');
            var price = button.getAttribute('data-price');

            // Update the estimated price input field
            $('#estimatePrice').val('₹ ' + price);

            // Save data to localStorage
            localStorage.setItem('serviceName', serviceName);
            localStorage.setItem('title', title);
            localStorage.setItem('price', price);

            // Set the service type and title in the hidden input fields
            $('#service_type').val(serviceName);
            $('#service_id').val(title);
            $('#service_price').val(price);

            $("#bookParcelBtn").removeClass('d-none');

            if (price > 0) {
                $('#receiverSection').show();
            }

            // Remove any existing delivery message
            $('#deliveryMessage').remove();

            // Check delivery time for Express or Standard service
            if (serviceName === 'ex' || serviceName === 'ss') {
                // Get service times from PHP (passed from controller)
                const serviceTimes = @json($service_time);

                // Find matching service time for the selected service
                const selectedService = serviceTimes.find(service => service.service_type === serviceName);

                if (selectedService) {
                    const serviceTime = selectedService.time; // e.g., "18:00"
                    const currentTime = new Date();
                    const currentHours = currentTime.getHours();
                    const currentMinutes = currentTime.getMinutes();
                    const currentTimeInMinutes = currentHours * 60 + currentMinutes;

                    // Parse service time (assuming format like "HH:MM")
                    const [serviceHours, serviceMinutes] = serviceTime.split(':').map(Number);
                    const serviceTimeInMinutes = serviceHours * 60 + serviceMinutes;

                    let deliveryMessage = '';

                    // Set specific messages for each service type
                    if (serviceName === 'ex') {
                        // Express service messages
                        deliveryMessage = currentTimeInMinutes > serviceTimeInMinutes
                            ? 'Estimated Time :- Your Express order will be delivered by tomorrow evening.'
                            : 'Estimated Time :- Your Express order will be delivered by end of the day.';
                    } else if (serviceName === 'ss') {
                        // Standard service messages
                        deliveryMessage = currentTimeInMinutes > serviceTimeInMinutes
                            ? 'Estimated Time :- Your Standard order will be delivered within 2 days.'
                            : 'Estimated Time :- Your Standard order will be delivered by tomorrow.';
                    }

                    // Append delivery message below the estimate price
                    $('#estimatePrice').after(
                        `<div id="deliveryMessage" style="color: red; margin-top: 10px;">${deliveryMessage}</div>`
                    );
                } else {
                    // If no service time found for the selected service
                    $('#estimatePrice').after(
                        `<div id="deliveryMessage" style="color: red; margin-top: 10px;">Delivery time information not available for ${serviceName === 'ex' ? 'Express' : 'Standard'} service.</div>`
                    );
                }
            }
        }

        // Google Maps Autocomplete
        function initializeAutocomplete() {
            const address1 = document.getElementById('address1');
            const address2 = document.getElementById('address2');

            const autocomplete1 = new google.maps.places.Autocomplete(address1);
            const autocomplete2 = new google.maps.places.Autocomplete(address2);

            autocomplete1.addListener('place_changed', function () {
                const place = autocomplete1.getPlace();
                if (place.geometry) {
                    address1.dataset.lat = place.geometry.location.lat();
                    address1.dataset.lon = place.geometry.location.lng();
                }
            });

            autocomplete2.addListener('place_changed', function () {
                const place = autocomplete2.getPlace();
                if (place.geometry) {
                    address2.dataset.lat = place.geometry.location.lat();
                    address2.dataset.lon = place.geometry.location.lng();
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function () {
            initializeAutocomplete();
        });

        // Google Maps Distance Calculation
        function calculateDistance() {
            const address1 = document.getElementById('address1');
            const address2 = document.getElementById('address2');

            const lat1 = address1.dataset.lat;
            const lon1 = address1.dataset.lon;
            const lat2 = address2.dataset.lat;
            const lon2 = address2.dataset.lon;

            const km3Element = document.getElementById('km3');
            const km1Element = document.getElementById('km1');

            const km3 = km3Element ? parseFloat(km3Element.value) || 50 : 50;
            const km1 = km1Element ? parseFloat(km1Element.value) || 5 : 5;

            // document.getElementById('distance').innerHTML = 'Calculating...';

            if (lat1 && lon1 && lat2 && lon2) {
                const service = new google.maps.DistanceMatrixService();

                service.getDistanceMatrix(
                    {
                        origins: [new google.maps.LatLng(lat1, lon1)],
                        destinations: [new google.maps.LatLng(lat2, lon2)],
                        travelMode: google.maps.TravelMode.DRIVING,
                        unitSystem: google.maps.UnitSystem.METRIC
                    },
                    function (response, status) {
                        if (status === 'OK' && response.rows[0].elements[0].status === 'OK') {
                            const distanceValue = response.rows[0].elements[0].distance.value;

                            const distanceKm = Math.ceil(distanceValue / 1000);

                            // document.getElementById('distance').textContent = 'Distance Approx: ' + distanceKm + ' km';

                            let price = 0;
                            if (distanceKm <= 3) {
                                price = km3;
                            } else {
                                price = km3 + (distanceKm - 3) * km1;
                            }

                            document.getElementById('estimatePrice').value = "₹ " + price;

                            localStorage.setItem('title', "SuperExpress");
                            localStorage.setItem('price', price);

                            $('#service_type').val('SuperExpress');
                            $('#service_id').val(distanceKm + ' km');
                            $('#service_price').val(price);

                            // Highlight the "Calculate Distance" button
                            document.querySelector('.calculate-distance-btn').classList.add('selected');

                            $("#bookParcelBtn").removeClass('d-none');

                            if (price > 0) {
                                $('#receiverSection').show();
                            }

                            // Check delivery time for SuperExpress service
                            $('#deliveryMessage').remove();
                            const serviceTimes = @json($service_time);
                            const selectedService = serviceTimes.find(service => service.service_type === 'SuperExpress');

                            if (selectedService) {
                                const serviceTime = selectedService.time; // e.g., "18:00"
                                const currentTime = new Date();
                                const currentHours = currentTime.getHours();
                                const currentMinutes = currentTime.getMinutes();
                                const currentTimeInMinutes = currentHours * 60 + currentMinutes;

                                // Parse service time (assuming format like "HH:MM")
                                const [serviceHours, serviceMinutes] = serviceTime.split(':').map(Number);
                                const serviceTimeInMinutes = serviceHours * 60 + serviceMinutes;

                                // Compare current time with service time
                                const deliveryMessage = currentTimeInMinutes > serviceTimeInMinutes
                                    // ? 'Your order will be delivered the next day.'
                                    // : 'Your order will be delivered the same day.';

                                    ? 'Estimated Time :- Order will be delivered by 2pm Tomorrow'
                                    : 'Estimated Time :- Order will be delivered within 4 Hours.';

                                // Append delivery message below the estimate price
                                $('#estimatePrice').after(
                                    `<div id="deliveryMessage" style="color: red; margin-top: 10px;">${deliveryMessage}</div>`
                                );
                            } else {
                                // If no service time found for SuperExpress
                                $('#estimatePrice').after(
                                    `<div id="deliveryMessage" style="color: red; margin-top: 10px;">Delivery time information not available.</div>`
                                );
                            }
                        } else {
                            document.getElementById('distance').textContent = 'Unable to calculate distance. Please check the addresses.';
                            console.error('Distance Matrix Error:', status);
                        }
                    }
                );
            } else {
                document.getElementById('distance').textContent = 'Please select valid addresses from the suggestions.';
            }
        }
    </script>
@endpush