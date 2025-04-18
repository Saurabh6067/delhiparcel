@extends('web.layout.main')
@section('main')
    <div class="container-fluid pt-5">
        <section class="services-sec bg-light mt-5 pt-5">
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-8 ">
                        <div class="sec-title">
                            <h2><span>Parcel Details</span></h2>
                            {{-- <img src="{{ asset('web/images/logo.png') }}" class="whychoose">
                                <p class="text-center"></p> --}}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="container mt-5 mb-5">
            <form id="parcelForm">
                <div class="container">
                    <input type="hidden" name="service_type" id="service_type" value="{{ $data['service_type'] }}">
                    <input type="hidden" name="service_id" id="title" value="{{ $data['service_id'] }}">
                    <input type="hidden" name="pickupAddress" id="pickupAddress" value="{{ $data['pickupAddress'] }}">
                    <input type="hidden" name="deliveryAddress" id="deliveryAddress"
                        value="{{ $data['deliveryAddress'] }}">

                    <div class="row">
                        <div class="col-sm-12 col-lg-6">
                            <h2 class="text-center mb-4">Sender Details</h2>
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label fw-bold" for="name">Sender Name</label>
                                    <input type="text" id="name" name="sender_name" class="form-control"
                                        placeholder="Enter Sender Name" required>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label fw-bold" for="number">Sender Contact Number</label>
                                    <input type="tel" id="number" name="sender_number" pattern="[6789][0-9]{9}"
                                        class="form-control" placeholder="Enter Sender Contact Number" required>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label fw-bold" for="email">Sender Email</label>
                                    <input type="email" id="email" name="sender_email" class="form-control"
                                        placeholder="Enter Sender Email" required>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label fw-bold" for="address">Sender Full Address</label>
                                    <textarea type="text" id="address" name="sender_address" class="form-control" placeholder="Enter Sender Address"
                                        required> </textarea>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label fw-bold" for="senderPinCode">Sender Pin Code</label>
                                    <input type="text" id="senderPinCode" name="senderPinCode" class="form-control"
                                        placeholder="Enter Sender Pin Code" value="{{ $data['pickupPincode'] }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <h2 class="text-center mb-4">Receiver Details</h2>
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label fw-bold" for="namer">Receiver Name</label>
                                    <input type="text" id="namer" name="receiver_name" class="form-control"
                                        placeholder="Enter Receiver Name" required>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label fw-bold" for="number">Receiver Contact Number</label>
                                    <input type="tel" id="number" name="receiver_number" pattern="[6789][0-9]{9}"
                                        class="form-control" placeholder="Enter Receiver Contact Number" required>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label fw-bold" for="email">Receiver Email</label>
                                    <input type="email" id="email" name="receiver_email" class="form-control"
                                        placeholder="Enter Receiver Email" required>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label fw-bold" for="address">Receiver Full Address</label>
                                    <textarea type="text" id="address" name="receiver_address" class="form-control"
                                        placeholder="Enter Receiver Address" required> </textarea>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label fw-bold" for="receiverPinCode">Receiver Pin Code</label>
                                    <input type="text" id="receiverPinCode" name="receiverPinCode"
                                        class="form-control" placeholder="Enter Receiver Pin Code"
                                        value="{{ $data['deliveryPincode'] }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment options and total cost -->
                    <div class="row mt-3">
                        <div class="col-12 col-lg-6 d-flex ">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="cod" name="payment_methods"
                                    value="COD">
                                <label class="form-check-label" for="cod">Cash On Delivery <em
                                        class="text-success text-center justify-content-center "
                                        style="font-size: 12px;">(COD charges ₹ 30 or 2 % which ever is
                                        higher)</em></label>
                                <div class="input-group input-group-sm d-none" id="codInputGroup">
                                    <input type="number" name="codAmount" id="codInput" class="form-control"
                                        aria-describedby="codChargeValue" placeholder="Enter COD ₹ 0">
                                    <span class="input-group-text" id="codChargeValue">0</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6 d-flex">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="online" name="payment_methods"
                                    value="online">
                                <label class="form-check-label" for="online">Prepaid Order</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12 d-flex">
                            <div class="mb-3 form-check">
                                <input class="form-check-input" type="checkbox" name="insurance" id="insurance"
                                    value="insurance">
                                <label class="form-check-label" for="insurance">
                                    Do you want to insurance your order?
                                    <em class="text-success" style="font-size: 12px;">
                                        (Insurance charges 50 or 1 % which ever is higher)
                                    </em>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-lg-6 my-auto">
                            <input type="hidden" id="amount" name="price" value="{{ $data['price'] }}"
                                data-amount="{{ $data['price'] }}">
                            <h4>Total Cost - <span
                                    id="amounts">{{ !empty($data['price']) ? $data['price'] : '₹ 0.0' }}</span></h4>
                        </div>
                        <div class="col-lg-6">
                            <button class="btn rainbow-hover mb-3 w-100">
                                <span class="sp">Submit</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('input[name="payment_methods"]').on('change', function() {
                if ($('#cod').is(':checked')) {
                    $('#codInputGroup').removeClass('d-none');
                } else {
                    $('#codInputGroup').addClass('d-none');
                    $('#codChargeValue').text('0.00'); // Reset COD charge
                    $('#codInput').val(''); // Clear COD input field
                }
                updateTotalAmount();
            });

            $('#codInput').on('change', function() {
                let price = parseFloat($('#amount').data('amount').replace(/[^0-9.]/g, '')) || 0;
                let amount = parseFloat($(this).val()) || 0;
                let charge = Math.max(30, amount * 0.02);

                $('#codChargeValue').text(charge.toFixed(2));
                updateTotalAmount();
            });

            $('#insurance').on('change', function() {
                updateTotalAmount();
            });

            function updateTotalAmount() {
                let basePrice = parseFloat($('#amount').data('amount').replace(/[^0-9.]/g, '')) || 0;
                let codCharge = parseFloat($('#codChargeValue').text()) || 0;
                let insuranceCharge = $('#insurance').is(':checked') ? Math.max(50, basePrice * 0.01) : 0;

                let totalPrice = basePrice + codCharge + insuranceCharge;

                $('#amount').val('₹ ' + totalPrice.toFixed(2));
                $('#amounts').text('₹ ' + totalPrice.toFixed(2));
            }

            // Initialize the total price
            updateTotalAmount();

            // store details
            $('#parcelForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('web.storeParcelDetails') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function(response) {
                        $('#parcelForm')[0].reset();
                        swal(response.msg, "Order ID: " + response.data, "success");
                        setTimeout(() => {
                            window.location.href = "{{ url('order-Label') }}" + '/' +
                                response.data;
                        }, 1500);
                    }
                });
            });
        });
    </script>
@endpush
