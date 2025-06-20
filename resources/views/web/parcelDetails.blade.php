@extends('web.layout.main')
@section('main')
    <div class="container-fluid pt-5">
        <section class="services-sec bg-light mt-5 pt-5">
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-8">
                        <div class="sec-title">
                            <h2><span>Parcel Details</span></h2>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="container mt-5 mb-5">
            <form id="parcelForm">
                @csrf
                <div class="container">
                    <input type="hidden" name="service_type" id="service_type" value="{{ $data['service_type'] }}">
                    <input type="hidden" name="service_id" id="title" value="{{ $data['service_id'] }}">
                    <input type="hidden" name="pickupAddress" id="pickupAddress" value="{{ $data['pickupAddress'] }}">
                    <input type="hidden" name="deliveryAddress" id="deliveryAddress" value="{{ $data['deliveryAddress'] }}">

                    <!-- Sender and Receiver Details (unchanged) -->
                    <div class="row">
                        <div class="col-sm-12 col-lg-6">
                            <h2 class="text-center mb-4">Sender Details</h2>
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label fw-bold" for="name">Sender Name</label>
                                    <input type="text" id="name" name="sender_name" class="form-control" placeholder="Enter Sender Name" required>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label fw-bold" for="number">Sender Contact Number</label>
                                    <input type="tel" id="sender_number" name="number" pattern="[6789][0-9]{9}" class="form-control" placeholder="Enter Sender Contact Number" required>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label fw-bold" for="email">Sender Email</label>
                                    <input type="email" id="sender_email" name="email" class="form-control" placeholder="Enter Sender Email" required>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label fw-bold" for="address">Sender Full Address</label>
                                    <textarea type="text" id="address" name="sender_address" class="form-control" placeholder="Enter Sender Address" {{ $data['service_type'] === 'SuperExpress' ? 'readonly' : '' }} required>{{ ($data['service_type'] !== 'SuperExpress' && $data['pickupAddress'] === 'NA') ? '' : $data['pickupAddress'] }}</textarea>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label fw-bold" for="senderPinCode">Sender Pin Code</label>
                                    <input type="text" id="senderPinCode" name="senderPinCode" class="form-control" placeholder="Enter Sender Pin Code" value="{{ $data['pickupPincode'] }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <h2 class="text-center mb-4">Receiver Details</h2>
                            <div class="row">
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label fw-bold" for="namer">Receiver Name</label>
                                    <input type="text" id="namer" name="receiver_name" class="form-control" placeholder="Enter Receiver Name" required>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label fw-bold" for="number">Receiver Contact Number</label>
                                    <input type="tel" id="receiver_number" name="receiver_number" pattern="[6789][0-9]{9}" class="form-control" placeholder="Enter Receiver Contact Number" required>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label fw-bold" for="email">Receiver Email</label>
                                    <input type="email" id="receiver_email" name="receiver_email" class="form-control" placeholder="Enter Receiver Email" required>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label fw-bold" for="address">Receiver Full Address</label>
                                    <textarea type="text" id="address" name="receiver_address" class="form-control" placeholder="Enter Receiver Address" {{ $data['service_type'] === 'SuperExpress' ? 'readonly' : '' }} required>{{ ($data['service_type'] !== 'SuperExpress' && $data['deliveryAddress'] === 'NA') ? '' : $data['deliveryAddress'] }}</textarea>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label fw-bold" for="receiverPinCode">Receiver Pin Code</label>
                                    <input type="text" id="receiverPinCode" name="receiverPinCode" class="form-control" placeholder="Enter Receiver Pin Code" value="{{ $data['deliveryPincode'] }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Options -->
                    <div class="row mt-3">
                        <div class="col-12 col-sm-12 d-flex justify-content-center">
                            <h2 class="text-danger">Payment Method</h2>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 col-lg-6 d-flex">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="cod" name="payment_methods" value="COD">
                                <label class="form-check-label" for="cod">Cash On Delivery <em class="text-success text-center justify-content-center" style="font-size: 12px;">(COD charges ₹ 30 or 2 % whichever is higher)</em></label>
                                <div class="input-group input-group-sm d-none" id="codInputGroup">
                                    <input type="number" name="codAmount" id="codInput" class="form-control" aria-describedby="codChargeValue" placeholder="Enter COD ₹ 0">
                                    <span class="input-group-text" id="codChargeValue">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6 d-flex">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" id="online" name="payment_methods" value="online">
                                <label class="form-check-label" for="online">Prepaid Order</label>
                            </div>
                        </div>
                    </div>

                    <!-- Insurance Option -->
                    <div class="row mb-3">
                        <div class="col-12 d-flex">
                            <div class="mb-3 form-check">
                                <input class="form-check-input" type="checkbox" name="insurance" id="insurance" value="insurance">
                                <label class="form-check-label" for="insurance">
                                    Do you want to insure your order?
                                    <em class="text-success" style="font-size: 12px;">
                                        (Insurance charges ₹ 50 or 1 % whichever is higher)
                                    </em>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <div class="row">
                        <div class="col-sm-12">
                            <span id="paymentError" class="text-danger" style="display: none; font-size: 20px; text-align: center; margin-bottom: 10px;">Please choose a payment method.</span>
                        </div>
                    </div>

                    <!-- Total Cost and Submit Button -->
                    <div class="row mb-3">
                        <div class="col-lg-6 my-auto">
                            <input type="hidden" id="amount" name="price" value="{{ $data['price'] }}" data-amount="{{ $data['price'] }}">
                            <h4>Total Cost - <span id="amounts">{{ !empty($data['price']) ? $data['price'] : '₹ 0.0' }}</span></h4>
                        </div>
                        <div class="col-lg-6">
                            <button type="submit" class="btn rainbow-hover mb-3 w-100">
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
    <!-- Include Razorpay Checkout Script -->
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        $(document).ready(function() {
            const codRadio = document.getElementById('cod');
            const onlineRadio = document.getElementById('online');
            const codInputGroup = document.getElementById('codInputGroup');
            const codInput = document.getElementById('codInput');
            const paymentError = document.getElementById('paymentError');

            // Show/hide COD input
            codRadio.addEventListener('change', function() {
                codInputGroup.classList.remove('d-none');
                codInput.setAttribute('required', 'required');
                paymentError.style.display = 'none';
                updateTotalAmount();
            });

            onlineRadio.addEventListener('change', function() {
                codInputGroup.classList.add('d-none');
                codInput.removeAttribute('required');
                codInput.value = '';
                paymentError.style.display = 'none';
                updateTotalAmount();
            });

            // Update total amount
            function updateTotalAmount() {
                let basePrice = parseFloat($('#amount').data('amount').replace(/[^0-9.]/g, '')) || 0;
                let codCharge = parseFloat($('#codChargeValue').text()) || 0;
                let insuranceCharge = $('#insurance').is(':checked') ? Math.max(50, basePrice * 0.01) : 0;

                let totalPrice = basePrice + codCharge + insuranceCharge;

                $('#amount').val('₹ ' + totalPrice.toFixed(2));
                $('#amounts').text('₹ ' + totalPrice.toFixed(2));
                return totalPrice;
            }

            // COD charge calculation
            $('#codInput').on('change', function() {
                let amount = parseFloat($(this).val()) || 0;
                let charge = Math.max(30, amount * 0.02);
                $('#codChargeValue').text(charge.toFixed(2));
                updateTotalAmount();
            });

            $('#insurance').on('change', function() {
                updateTotalAmount();
            });

            // Initialize total price
            updateTotalAmount();

            // Form submission
            $('#parcelForm').on('submit', function(e) {
                e.preventDefault();
                const selectedPayment = document.querySelector('input[name="payment_methods"]:checked');

                if (!selectedPayment) {
                    paymentError.style.display = 'block';
                    return;
                }

                paymentError.style.display = 'none';
                let totalPrice = updateTotalAmount();
                let formData = new FormData(this);

                if (selectedPayment.value === 'online') {
                    // Create Razorpay order
                    $.ajax({
                        url: "{{ route('web.createRazorpayOrder') }}",
                        type: "POST",
                        data: {
                            amount: totalPrice,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                let rzp = new Razorpay({
                                    key: "{{ env('RAZORPAY_KEY', 'rzp_test_BCqQIjZcNVZHVw') }}",
                                    amount: response.order.amount * 100, // Amount in paisa
                                    currency: "INR",
                                    name: "Delhi Parcel",
                                    description: "Parcel Booking Payment",
                                    order_id: response.order.id,
                                    handler: function(response) {
                                        // On successful payment
                                        formData.append('razorpay_payment_id', response.razorpay_payment_id);
                                        formData.append('razorpay_order_id', response.razorpay_order_id);
                                        formData.append('status', 'success');

                                        $.ajax({
                                            url: "{{ route('web.storeParcelDetails') }}",
                                            type: "POST",
                                            data: formData,
                                            processData: false,
                                            contentType: false,
                                            success: function(res) {
                                                if (res.success) {
                                                    $('#parcelForm')[0].reset();
                                                    swal(res.msg, "Order ID: " + res.data, "success");
                                                    setTimeout(() => {
                                                        window.location.href = "{{ url('order-Label') }}/" + res.data;
                                                    }, 1500);
                                                } else {
                                                    swal("Error", res.message, "error");
                                                }
                                            },
                                            error: function(xhr) {
                                                swal("Error", "Failed to book parcel", "error");
                                            }
                                        });
                                    },
                                    prefill: {
                                        name: $('#name').val(),
                                        email: $('#sender_email').val(),
                                        contact: $('#sender_number').val()
                                    },
                                    theme: {
                                        color: "#28a745"
                                    },
                                    modal: {
                                        ondismiss: function() {
                                            // On payment cancellation
                                            $.ajax({
                                                url: "{{ route('web.storeParcelDetails') }}",
                                                type: "POST",
                                                data: {
                                                    amount: totalPrice,
                                                    status: 'cancelled',
                                                    _token: "{{ csrf_token() }}"
                                                },
                                                success: function(res) {
                                                    swal("Cancelled", res.message, "info");
                                                },
                                                error: function(xhr) {
                                                    swal("Error", "Failed to process cancellation", "error");
                                                }
                                            });
                                        }
                                    }
                                });

                                rzp.on('payment.failed', function(response) {
                                    // On payment failure
                                    $.ajax({
                                        url: "{{ route('web.storeParcelDetails') }}",
                                        type: "POST",
                                        data: {
                                            amount: totalPrice,
                                            status: 'failed',
                                            reason: response.error.description,
                                            _token: "{{ csrf_token() }}"
                                        },
                                        success: function(res) {
                                            swal("Failed", res.message, "error");
                                        },
                                        error: function(xhr) {
                                            swal("Error", "Failed to process payment failure", "error");
                                        }
                                    });
                                });

                                rzp.open();
                            } else {
                                swal("Error", response.message, "error");
                            }
                        },
                        error: function(xhr) {
                            swal("Error", "Failed to create payment order", "error");
                        }
                    });
                } else {
                    // COD: Directly store parcel details
                    formData.append('status', 'success'); // COD is considered successful by default
                    $.ajax({
                        url: "{{ route('web.storeParcelDetails') }}",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                $('#parcelForm')[0].reset();
                                swal(response.msg, "Order ID: " + response.data, "success");
                                setTimeout(() => {
                                    window.location.href = "{{ url('order-Label') }}/" + response.data;
                                }, 1500);
                            } else {
                                swal("Error", response.message, "error");
                            }
                        },
                        error: function(xhr) {
                            swal("Error", "Failed to book parcel", "error");
                        }
                    });
                }
            });
        });
    </script>
@endpush