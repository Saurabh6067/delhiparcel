@extends('seller.layout.main')
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
                            <li class="breadcrumb-item active">{{ $branch->fullname ?? ''}} / Seller Dashboard</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                
                <h5 class="mt-3 font-weight-bold">Today Order Details</h5>
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{ $toDayOrder ?? 0 }}</h3>
                                <p>Today Order</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <a href="{{ url('/order-details/' . 'toDayOrder') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $toDayPendingOrder ?? 0 }}</h3>
                                <p>Today Pending-Handover</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-hourglass"></i>
                            </div>
                            <a href="{{ url('/order-details/' . 'toDayPendingOrder') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $toDayOrderPicUp ?? 0 }}</h3>
                                <p>Today Picked-Up Order</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-hourglass"></i>
                            </div>
                            <a href="{{ url('/order-details/' . 'toDayOrderPicUp') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-secondary">
                            <div class="inner">
                                <h3>{{ $toDayCompleteOrder ?? 0 }}</h3>
                                <p>Today Complete</p>
                            </div>
                            <div class="icon">
                                <i class="far fa-check-circle"></i>
                            </div>
                            <a href="{{ url('/order-details/' . 'toDayCompleteOrder') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $toDayCancelledOrder ?? 0 }}</h3>
                                <p>Today Cancelled</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-solid fa-dolly"></i>
                            </div>
                            <a href="{{ url('/order-details/' . 'toDayCancelledOrder') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- <div class="col-lg-3 col-6">-->
                    <!--    <div class="small-box bg-success">-->
                    <!--        <div class="inner">-->
                    <!--            <h3></h3>-->
                    <!--            <p>Wallet</p>-->
                    <!--        </div>-->
                    <!--        <div class="icon">-->
                    <!--            <i class="fas fa-solid fa-wallet"></i>-->
                    <!--        </div>-->
                    <!--        <a href="{{ route('seller.wallet') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                    <!--    </div>-->
                    <!--</div>-->
                    <div class="col-md-2 col-sm-6 col-12">
                        <a href="{{ url('/seller-wallet') }}" class="text-dark">
                            <div class="info-box" style="min-height:143px">
                                <span class="info-box-icon bg-danger"><i class="fas fa-solid fa-wallet"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Wallet</strong></span>
                                    <span class="info-box-number">{{ $branchcodamount ?? 0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <h5 class="mt-3 font-weight-bold">Total Order Details</h5>
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{ $totalOrder ?? 0 }}</h3>
                                <p>Total Order</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <a href="{{ url('/order-details/' . 'totalOrder') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $totalPendingOrder ?? 0 }}</h3>
                                <p>Total Pending-Handover</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-hourglass"></i>
                            </div>
                            <a href="{{ url('/order-details/' . 'totalPendingOrder') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $totalOrderPicUp ?? 0 }}</h3>
                                <p>Total Picked-Up Order</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-hourglass"></i>
                            </div>
                            <a href="{{ url('/order-details/' . 'totalOrderPicUp') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-secondary">
                            <div class="inner">
                                <h3>{{ $totalCompleteOrder ?? 0 }}</h3>
                                <p>Total Complete</p>
                            </div>
                            <div class="icon">
                                <i class="far fa-check-circle"></i>
                            </div>
                            <a href="{{ url('/order-details/' . 'totalCompleteOrder') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $totalCanceledOrder ?? 0 }}</h3>
                                <p>Total Cancelled</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-solid fa-dolly"></i>
                            </div>
                            <a href="{{ url('/order-details/' . 'totalCancelledOrder') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <!-- Add here card -->
                <!--<div class="row">-->
                <!--    <div class="col-lg-3 col-6">-->
                <!--        <div class="small-box bg-danger">-->
                <!--            <div class="inner">-->
                <!--                <h3></h3>-->
                <!--                <p>All Orders</p>-->
                <!--            </div>-->
                <!--            <div class="icon">-->
                <!--                <i class="fas fa-list"></i>-->
                <!--            </div>-->
                <!--            <a href="{{ route('seller.allOrders') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                <!--        </div>-->
                <!--    </div>-->
                   
                <!--    <div class="col-lg-3 col-6">-->
                <!--        <div class="small-box bg-secondary">-->
                <!--            <div class="inner">-->
                <!--                <h3></h3>-->
                <!--                <p>All COD History</p>-->
                <!--            </div>-->
                <!--            <div class="icon">-->
                <!--                <i class="fas fa-solid fa-money-bill"></i>-->
                <!--            </div>-->
                <!--            <a href="{{ route('seller.allCodHistory') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
            </div>
        </section>
    </div>
@endsection