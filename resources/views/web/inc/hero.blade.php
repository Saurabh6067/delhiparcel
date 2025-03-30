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
                        placeholder="Delivery Pincode" required>
                    <span id="deliveryPincodeMessage" style="color: red;"></span>
                </div>

                <div class="form-group mb-3">
                    <div id="serviceSection" style="display: none;">
                        <label><strong>Choose Delivery Service</strong></label>
                        <div class="row">
                            <div class="col-auto">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="optionSuperExpress"
                                        name="options" value="SuperExpress"
                                        onclick="showAdditionalOptions('superExpress')">
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
                                            class="btn btn-sm btn-primary float-end">Calculate
                                            Distance</button>
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

                    <!-- Express weight options (in kg) -->
                    <div id="expressWeightOptions" class="item-weight-options" style="display: none;">
                        <div class="radio-item">
                            @forelse ($ex as $data)
                                <button type="button" class="btn btn-sm btn-dark express-btn me-3 p-1"
                                    data-service_type="{{ $data->type }}" data-id="{{ $data->id }}"
                                    data-price="{{ $data->price }}"
                                    onclick="get_price(this)">{{ $data->title }}</button>
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
                                    data-price="{{ $data->price }}"
                                    onclick="get_price(this)">{{ $data->title }}</button>
                            @empty
                                <p class="text-center">No standard services available at the moment.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="estimatePrice"><strong>Estimate Price</strong></label><br>
                    <input type="text" class="form-control" name="price" id="estimatePrice" placeholder="₹ 0"
                        readonly>
                </div>

                <div class="form-group mb-3">
                    <button class="btn btn-sm rainbow-hover d-none" type="submit" id="bookParcelBtn">
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
        $(document).ready(function() {
            function checkPincode(inputElement, messageElement) {
                var pinCode = $(inputElement).val();
                var setMsg = $(messageElement);
                setMsg.text("Checking...");

                $.ajax({
                    url: "{{ route('web.checkPinCode') }}",
                    type: 'POST',
                    data: {
                        pin: pinCode
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
                            $('#serviceSection, #expressWeightOptions, #standardWeightOptions, #receiverSection')
                                .hide();
                            $('#estimatePrice').val('₹ 0.0');
                        }
                    },
                    error: function(err) {
                        Toast("error", "An unexpected error occurred. Please try again.");
                    }
                });
            }
            $('#pickupPincode').on('change', function() {
                checkPincode(this, '#pickupPincodeMessage');
            });
            $('#deliveryPincode').on('change', function() {
                checkPincode(this, '#deliveryPincodeMessage');
            });
        });

        function showAdditionalOptions(option) {
            // Reset the price when switching between services
            document.getElementById('estimatePrice').value = "₹ 0";

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
        }

        function get_price(button) {
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

            // updateTotalCost();
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

                document.getElementById('distance').textContent = 'Loading...';

                fetch(proxyUrl)
                    .then(response => response.json())
                    .then(data => {
                        var distance = data.routes[0].legs[0].distance.text;
                        // console.log(data.routes[0])
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
                            $('#service_id').val(approxDis + ' km');
                            $('#service_price').val(price);

                            $("#bookParcelBtn").removeClass('d-none');

                            if (price > 0) {
                                $('#receiverSection').show();
                            }

                            // Update the total cost
                            // updateTotalCost();
                        } else {
                            document.getElementById('distance').textContent = 'No route found.';
                        }
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
    </script>
@endpush
