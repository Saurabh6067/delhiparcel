@extends('web.layout.main')
@section('main')
    <div class="container-fluid pt-5">
        <section class="services-sec bg-light mt-5 pt-5">
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-8 ">
                        <div class="sec-title">
                            <h2><span>Track Order</span></h2>
                            <br>
                            <img src="{{ asset('web/images/logo.png') }}" class="whychoose">
                            <p class="text-center"></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="container contact-form-container">
            @if (!$order)
                <h2 class="contact-form-title mb-4">Track Your Order</h2>
                <div class="row">
                    <form id="trackNow">
                        <div class="input-group">
                            <input type="text" class="form-control" id="orderId" name="orderId"
                                placeholder="Enter order id..." required>
                            <button type="submit" class="btn btn-dark">Track now</button>
                        </div>
                    </form>
                </div>
            @else
                <h2 class="text-center contact-form-title mb-4 sp">Thanks for your order!</h2>
                <div class="row mt-2">
                    <div class="col-12 col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>To</th>
                                        <th>Date</th>
                                        <th>View Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $order->order_id ?? '-' }}</td>
                                        <td class="text-capitalize">{{ $order->receiver_name ?? '-' }}</td>
                                        <td>{{ $order->datetime ?? '-' }}</td>
                                        <td><button class="btn btn-dark btn-sm" id="viewOrderDetails">View</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-12 col-lg-12 d-none" id="orderDetails">
                        <h2 class="text-center contact-form-title mb-4 sp">Order Details</h2>
                        <div class="row">
                            <div class="col-12 col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Order Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $order->order_id ?? '-' }}</td>
                                                <td>{{ $order->datetime ?? '-' }}</td>
                                                <td class="text-capitalize">{{ $order->order_status ?? '-' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#viewOrderDetails').click(function() {
                $('#orderDetails').toggleClass('d-none');
            });

            $('#trackNow').on('submit', function(e) {
                e.preventDefault();

                let orderId = $('#orderId').val(); // Get orderId input value

                $.ajax({
                    type: "POST",
                    url: "{{ route('web.trackOrderDetails') }}",
                    data: {
                        orderId: orderId,
                    },
                    dataType: "json",
                    success: function(response) {
                        $('#trackNow')[0].reset();
                        if (response.success) {
                            swal("Success", response.message, "success");
                            setTimeout(() => {
                                window.location.href = "{{ url('trackOrders') }}/" +
                                    response.data.order_id;
                            }, 1500);
                        } else {
                            swal("Error", response.message, "error");
                        }
                    },
                    error: function() {
                        swal("Error", "Something went wrong. Please try again.", "error");
                    }
                });
            });
        });
    </script>
@endpush
