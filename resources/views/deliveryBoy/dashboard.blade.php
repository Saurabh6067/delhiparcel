@extends('deliveryBoy.layout.main')
@section('main')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            {{-- <li class="breadcrumb-item active">Delivery Boy Dashboard</li> --}}
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
                {{-- today orders --}}
                <h5 class="mt-3 font-weight-bold"></h5>
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
                                    <span class="info-box-number"
                                        id="SuperExpressOrder">{{ $PendingSuperExpressOrder }} / Time: <span class="info-box-number text-danger" id="SuperExpressOrderTime">00:00:00</span></span>
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function() {
                                            let superExpressOrder = parseInt(document.getElementById("SuperExpressOrder").innerText.trim(), 10);
                                            let superExpressOrderTimeEl = document.getElementById("SuperExpressOrderTime");

                                            if (superExpressOrder > 0) {
                                                let remainingTime = 3 * 60 * 60; // 3 hours in seconds

                                                function updateTimer() {
                                                    let hours = Math.floor(remainingTime / 3600);
                                                    let minutes = Math.floor((remainingTime % 3600) / 60);
                                                    let seconds = remainingTime % 60;

                                                    superExpressOrderTimeEl.innerText =
                                                        String(hours).padStart(2, '0') + ":" +
                                                        String(minutes).padStart(2, '0') + ":" +
                                                        String(seconds).padStart(2, '0');

                                                    if (remainingTime > 0) {
                                                        remainingTime--;
                                                        setTimeout(updateTimer, 1000);
                                                    }
                                                }

                                                updateTimer();
                                            }
                                        });
                                    </script>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <a href="{{ url('/delivery-boy-order-details/' . 'PendingOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-secondary"><i class="fas fa-hourglass"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Pending orders</strong></span>
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
                        <a href="{{ url('/delivery-boy-order-details/' . 'totalCompleteOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-dark"><i class="fas fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Total Delivery Orders</strong></span>
                                    <span class="info-box-number">{{ $totalCompleteOrder ?? 0.0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    {{-- <div class="col-md-4 col-sm-6 col-12">
                        <a href="{{ url('/delivery-boy-order-details/' . 'DirectOrders') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-dark"><i class="fas fa-box"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Direct Orders</strong></span>
                                    <span class="info-box-number">{{ $DirectOrders ?? 0.0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div> --}}
                    <div class="col-md-6 col-sm-6 col-12">
                        <a href="{{ url('/delivery-boy-transfer-order-details/' . 'DirectOrders') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-box"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Pending Other PinCode Orders</strong></span>
                                    <span class="info-box-number">{{ $PendingPinCodeOrders ?? 0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-sm-6 col-12">
                        <a href="{{ url('/delivery-boy-transfer-order-details/' . 'CompleteOrders') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-box"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>All Other PinCode Orders</strong></span>
                                    <span class="info-box-number">{{ $CompleteOtherPinCodeOrders ?? 0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
