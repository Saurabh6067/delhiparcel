

@extends('web.layout.main')

@section('main')
<style>
        @media (max-width: 768px) {
            body{
                height: 0px !important;
            }

            .btn-mobile_section{
                font-size: 10px;
            }
        }
</style>
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
                            <input type="text" class="form-control" id="orderId" name="orderId" placeholder="Enter order id..."
                                required>
                            <button type="submit" class="btn btn-dark btn-mobile_section">Track now</button>
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

                        <!-- Order History Timeline Section -->
                        @if($orderDetails && count($orderDetails) > 0)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h3 class="text-center mb-4">Order History Timeline</h3>
                                    <div class="vertical-timeline">
                                        <div class="timeline-line"></div>
                                        @foreach($orderDetails as $index => $history)
                                            <div class="timeline-item {{ $index % 2 == 0 ? 'left' : 'right' }}">
                                                <div class="timeline-dot"></div>
                                                <div class="timeline-content">
                                                    <h4 class="text-center">{{ $history->status ?? 'Status Update' }}</h4>
                                                    <p class="timeline-date text-center">{{ $history->datetime }}</p>
                                                    @if(isset($history->description))
                                                        <p class="timeline-description text-center">{{ $history->description }}</p>
                                                    @endif
                                                </div>
                                                <div class="timeline-arrow"></div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row mt-4">
                                <div class="col-12 text-center">
                                    <p>No order history available.</p>
                                </div>
                            </div>
                        @endif
                        <h1 class="text-center mt-4">
                        @if($orders->status === 'Not Delivered')
                            {{ $orders->status_message ?? '' }}
                        @else
                        @endif
                    </h1>
                        <!-- End of Order History Timeline Section -->
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#viewOrderDetails').click(function () {
                $('#orderDetails').toggleClass('d-none');
            });

            $('#trackNow').on('submit', function (e) {
                e.preventDefault();

                let orderId = $('#orderId').val(); // Get orderId input value

                $.ajax({
                    type: "POST",
                    url: "{{ route('web.trackOrderDetails') }}",
                    data: {
                        orderId: orderId,
                        _token: "{{ csrf_token() }}" // Add CSRF token for security
                    },
                    dataType: "json",
                    success: function (response) {
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
                    error: function () {
                        swal("Error", "Something went wrong. Please try again.", "error");
                    }
                });
            });
        });
    </script>
@endpush

<!-- Add this CSS to your stylesheet or in a style tag in your layout header -->
<style>
    .vertical-timeline {
        position: relative;
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px 0;
    }

    .timeline-line {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 50%;
        width: 4px;
        background: #2196F3;
        transform: translateX(-50%);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 60px;
        width: 100%;
    }

    .timeline-dot {
        position: absolute;
        top: 20px;
        left: 50%;
        width: 16px;
        height: 16px;
        background: #2196F3;
        border-radius: 50%;
        transform: translateX(-50%);
        z-index: 10;
    }

    .timeline-content {
        position: relative;
        width: 45%;
        padding: 15px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        border: 1px solid #e0e0e0;
    }

    .timeline-item.left .timeline-content {
        margin-right: auto;
        margin-left: 0;
    }

    .timeline-item.right .timeline-content {
        margin-left: auto;
        margin-right: 0;
    }

    .timeline-arrow {
        position: absolute;
        top: 22px;
        width: 12px;
        height: 12px;
    }

    .timeline-item.left .timeline-arrow {
        right: 53%;
        border-top: 8px solid transparent;
        border-bottom: 8px solid transparent;
        border-left: 8px solid #e0e0e0;
    }

    .timeline-item.right .timeline-arrow {
        left: 53%;
        border-top: 8px solid transparent;
        border-bottom: 8px solid transparent;
        border-right: 8px solid #e0e0e0;
    }

    .timeline-content h4 {
        margin-top: 0;
        font-weight: 600;
        color: #333;
    }

    .timeline-date {
        color: #6c757d;
        font-size: 0.85em;
        margin: 5px 0;
    }

    /* For mobile screens */
    @media (max-width: 767px) {
        .timeline-line {
            left: 30px;
        }

        .timeline-dot {
            left: 30px;
        }

        .timeline-content {
            width: calc(100% - 60px);
            margin-left: 60px !important;
        }

        .timeline-item.left .timeline-arrow,
        .timeline-item.right .timeline-arrow {
            left: 44px;
            right: auto;
            border-left: none;
            border-right: 8px solid #e0e0e0;
        }
    }
</style>