@extends('admin.layout.main')
@section('main')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active">Admin Dashboard</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $branch }}</h3>
                                <p>All Branchs</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-store"></i>
                            </div>
                            <a href="{{ route('admin.allBranch') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $sellerbranch }}</h3>
                                <p>All Sellers</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-store"></i>
                            </div>
                            <a href="{{ route('admin.seller.allBranch') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $cat }}</h3>
                                <p>All Categorys</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-tags"></i>
                            </div>
                            <a href="{{ route('admin.category') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-secondary">
                            <div class="inner">
                                <h3>{{ $pin }}</h3>
                                <p>All PinCodes</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <a href="{{ route('amdin.pinCodes') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    {{-- <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $directOrder ?? 0 }}</h3>
                                <p>Direct Orders</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <a href="{{ route('admin.DirectOrders') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div> --}}
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $eq ?? 0 }}</h3>
                                <p>All Enquiry</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <a href="{{ route('admin.allEnquiry') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $dBoy }}</h3>
                                <p>All Delivery Boy</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <a href="{{ route('admin.allDeliveryBoy') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-secondary">
                            <div class="inner">
                                @php
                                    $totalAmount = 0.0;
                                @endphp
                                @foreach ($data as $codHistory)
                                    @php
                                        $totalAmount += $codHistory->order->price ?? 0;
                                    @endphp
                                @endforeach
                                <h3>{{ '₹ ' . $totalAmount ?? 0.0 }}</h3>
                                <p>Today COD</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-money-bill"></i>
                            </div>
                            <a href="{{ route('admin.allCodHistory') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                @php
                                    $totalAmount = 0.0;
                                @endphp
                                @foreach ($todayWallet as $walletHistory)
                                    @php
                                        $totalAmount += $walletHistory->c_amount;
                                    @endphp
                                @endforeach
                                <h3>{{ '₹ ' . $totalAmount ?? 0.0 }}</h3>
                                <p>Today Wallet Recharge</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <a href="{{ route('admin.todayWallet') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('admin-order-details/toDayOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-box"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Today Order</strong></span>
                                    <span class="info-box-number">{{ $toDayOrder ?? '' }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('admin-order-details/toDayPendingOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-hourglass"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Today Pending-Handover</strong></span>
                                    <span class="info-box-number">{{ $toDayPendingOrder ?? '' }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('admin-order-details/toDayOrderPicUp') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-hourglass"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Today Pinked-Up Order</strong></span>
                                    <span class="info-box-number">{{ $toDayOrderPicUp ?? '' }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('admin-order-details/toDayCompleteOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-secondary"><i class="far fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Today Complete</strong></span>
                                    <span class="info-box-number">{{ $toDayCompleteOrder ?? '' }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('admin-order-details/toDayCancelledOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-solid fa-dolly"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Today Cancelled</strong></span>
                                    <span class="info-box-number">{{ $toDayCancelledOrder ?? '' }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
