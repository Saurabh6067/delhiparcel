@extends('delivery.layout.main')
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
                            <li class="breadcrumb-item active">{{ $delivery->fullname }} / Delivery Dashboard</li>
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
                <h5 class="mt-3 font-weight-bold">Today Order Details</h5>
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/delivery-order-details/' . 'toDayOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-box"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Today Order</strong></span>
                                    <span class="info-box-number">{{ $toDayOrder ?? 0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/delivery-order-details/' . 'toDayPendingOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-hourglass"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Today Pending-Handover</strong></span>
                                    <span class="info-box-number">{{ $toDayPendingOrder ?? 0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/delivery-order-details/' . 'toDayOrderPicUp') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-hourglass"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Today Picked-Up Order</strong></span>
                                    <span class="info-box-number">{{ $toDayOrderPicUp ?? 0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                     <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/delivery-order-details/' . 'todayDeliveredToNearbyBranchOrders') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-solid fa-dolly"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Pending Delivery</strong></span>
                                    <span class="info-box-number">{{ $todayDeliveredToNearbyBranchOrders ?? 0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/delivery-order-details/' . 'toDayCompleteOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-secondary"><i class="far fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Today Complete</strong></span>
                                    <span class="info-box-number">{{ $toDayCompleteOrder ?? 0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/delivery-order-details/' . 'toDayCancelledOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-solid fa-dolly"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Today Cancelled</strong></span>
                                    <span class="info-box-number">{{ $toDayCancelledOrder ?? 0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/delivery-order-details/' . 'toDayOtherBranchOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-solid fa-dolly"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Today Other Branch Pending Order</strong></span>
                                    <span class="info-box-number">{{ $toDayOtherBranchOrder ?? 0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/delivery-order-details/' . 'toDayMyBranchOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-solid fa-dolly"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Today My Branch Order</strong></span>
                                    <span class="info-box-number">{{ $toDayMyBranchOrder ?? 0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/delivery-order-details/' . 'todaysuper') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-solid fa-dolly"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Today Pending Super Express</strong></span>
                                    <span class="info-box-number">{{ $todaysuper ?? 0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    
                    
                    <!-- Delivert to other brach -->
                     <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/delivery-order-details/' . 'todayPendingToReceiveOtherBranchOrders') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-solid fa-dolly"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Today Pending Other Branch Order</strong></span>
                                    <span class="info-box-number">{{ $todayPendingToReceiveOtherBranchOrders ?? 0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/delivery-order-details/' . 'totaltodaytransferOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-solid fa-dolly"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Today Transfered Order to Other Branch</strong></span>
                                    <span class="info-box-number">{{ $totaltodaytransferOrder ?? 0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    
                    
                    
                </div>
                {{-- total orders --}}
                <h5 class="mt-3 font-weight-bold">Total Order Details</h5>
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/delivery-order-details/' . 'totalOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-box"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Total Order</strong></span>
                                    <span class="info-box-number">{{ $totalOrder ?? 0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/delivery-order-details/' . 'totalPendingOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-hourglass"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Total Pending</strong></span>
                                    <span class="info-box-number">{{ $totalPendingOrder ?? 0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/delivery-order-details/' . 'totalOrderPicUp') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-hourglass"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Total Picked-Up Order</strong></span>
                                    <span class="info-box-number">{{ $totalOrderPicUp }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/delivery-order-details/' . 'totalCompleteOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-secondary"><i class="far fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Total Complete</strong></span>
                                    <span class="info-box-number">{{ $totalCompleteOrder ?? 0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/delivery-order-details/' . 'totalCancelledOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-solid fa-dolly"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Total Cancelled</strong></span>
                                    <span class="info-box-number">{{ $totalCanceledOrder ?? 0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                     <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/delivery-order-details/' . 'totalOtherBranchOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-solid fa-dolly"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Total Other Branch Panding Order</strong></span>
                                    <span class="info-box-number">{{ $totalOtherBranchOrder ?? 0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                      <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/delivery-order-details/' . 'totalMyBranchOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-solid fa-dolly"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Total My Branch Panding Order</strong></span>
                                    <span class="info-box-number">{{ $totalMyBranchOrder ?? 0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>
                {{-- branch orders --}}
                <!--<h5 class="mt-3 font-weight-bold">Branch Order Details</h5>-->
                <!--<div class="row">-->
                <!--    <div class="col-md-3 col-sm-6 col-12">-->
                <!--        <a href="{{ route('delivery.other.branch.order') }}" class="text-dark">-->
                <!--            <div class="info-box">-->
                <!--                <span class="info-box-icon bg-primary"><i class="fas fa-box"></i></span>-->
                <!--                <div class="info-box-content">-->
                <!--                    <span class="info-box-text"><strong>All Order</strong></span>-->
                <!--                    <span class="info-box-number">{{ $allOrderDetail ?? 0 }}</span>-->
                <!--                </div>-->
                <!--            </div>-->
                <!--        </a>-->
                <!--    </div>-->
                <!--    <div class="col-md-3 col-sm-6 col-12">-->
                <!--        <a href="{{ route('delivery.order-pin-code-orders') }}" class="text-dark">-->
                <!--            <div class="info-box">-->
                <!--                <span class="info-box-icon bg-primary"><i class="fas fa-box"></i></span>-->
                <!--                <div class="info-box-content">-->
                <!--                    <span class="info-box-text"><strong>My Order</strong></span>-->
                <!--                    <span class="info-box-number">{{ $myOrderDetail ?? 0 }}</span>-->
                <!--                </div>-->
                <!--            </div>-->
                <!--        </a>-->
                <!--    </div>-->
                <!--</div>-->
            </div>
        </section>
    </div>
@endsection