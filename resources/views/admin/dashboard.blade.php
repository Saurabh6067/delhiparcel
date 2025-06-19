@extends('admin.layout.main')
@section('main')
<style>
    .info-box {
    box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
    border-radius: .25rem;
    background-color: #fff;
    display: -ms-flexbox;
    display: flex;
    margin-bottom: 1rem;
    min-height: 140px;
    padding: 1.1rem;
    position: relative;
    width: 100%;
}
</style>
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
                  
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $toDayOrder ?? '' }}</h3>
                                <p>Today Order</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-store"></i>
                            </div>
                            <a href="{{ url('admin-order-details/toDayOrder') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $toDayPendingOrder ?? '' }}</h3>
                                <p>Today Pending-Handover</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-store"></i>
                            </div>
                            <a href="{{ url('admin-order-details/toDayPendingOrder') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $toDayOrderPicUp ?? '' }}</h3>
                                <p>Today Picked-Up Order</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-store"></i>
                            </div>
                            <a href="{{ url('admin-order-details/toDayOrderPicUp') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    
                      <div class="col-lg-3 col-sm-6 col-12">
                        <div class="small-box bg-secondary">
                            <div class="inner">
                                <h3>{{ $todayOtherBranchOrders ?? '' }}</h3>
                                <p>Within Branch</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-store"></i>
                            </div>
                            <a href="{{ url('admin-order-details/todayOtherBranchOrders') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                   
                </div>
                <div class="row">
                    
                     <div class="col-lg-3 col-sm-6 col-12">
                        <div class="small-box bg-secondary">
                            <div class="inner">
                                <h3>{{ $toDayCompleteOrder ?? '' }}</h3>
                                <p>Today Complete</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-store"></i>
                            </div>
                            <a href="{{ url('admin-order-details/toDayCompleteOrder') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                    
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $toDayCancelledOrder ?? '' }}</h3>
                                <p>Today Cancelled</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-store"></i>
                            </div>
                            <a href="{{ url('admin-order-details/toDayCancelledOrder') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    
                   

                    
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $toDayRevenueOrder ?? '' }}</h3>
                                <p>Today's Revenue</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-store"></i>
                            </div>
                            <a href="{{ url('admin/toDayRevenueOrder') }}" class="small-box-footer">More info <i
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
                   
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ '₹ ' . number_format($todayAmount, 2) }}</h3>
                                <p>Today COD</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-money-bill"></i>
                            </div>
                            <a href="{{ route('admin.TodayCodHistory') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    
                     <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ '₹ ' . number_format($totalCodAmount, 2) }}</h3>
                                <p>Total COD</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-money-bill"></i>
                            </div>
                            <a href="{{ route('admin.allCodHistory') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                      <div class="col-lg-3 col-sm-6 col-12">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $totalPendingSuperExpress ?? '' }}</h3>
                                <p>Total Pending SuperExpress Order</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-store"></i>
                            </div>
                            <a href="{{ url('admin-order-details/totalPendingSuperExpress') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    
                    
                </div>
                
                <div class="row">
                   <h2>Total Order Details</h2>
                </div>
                <hr>
                
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('admin-order-details/totalOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-box"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Total Order</strong></span>
                                    <span class="info-box-number">{{ $totalOrders ?? '' }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('admin-order-details/TotalPendingOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-secondary"><i class="fas fa-box"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Total Pending Order</strong></span>
                                    <span class="info-box-number">{{ $totalPendingOrder ?? '' }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                   
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('admin-order-details/TotalPickedupOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-box"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Total Picked-Up Order</strong></span>
                                    <span class="info-box-number">{{ $totalOrderPicUp ?? '' }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('admin-order-details/totalOtherBranchOrders') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-box"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Within Branch</strong></span>
                                    <span class="info-box-number">{{ $totalOtherBranchOrders ?? '' }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('admin-order-details/TotalCompleteOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-box"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Total Complete Order</strong></span>
                                    <span class="info-box-number">{{ $totalCompleteOrder ?? '' }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('admin-order-details/TotalCancelledOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-box"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Total Cancelled Order</strong></span>
                                    <span class="info-box-number">{{ $totalCancelledOrder ?? '' }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- <div class="col-md-3 col-sm-6 col-12">-->
                    <!--    <a href="{{ url('admin-order-details/totalPendingSuperExpress') }}" class="text-dark">-->
                    <!--        <div class="info-box">-->
                    <!--            <span class="info-box-icon bg-secondary"><i class="fas fa-box"></i></span>-->
                    <!--            <div class="info-box-content">-->
                    <!--                <span class="info-box-text"><strong>Total Pending SuperExpress Order</strong></span>-->
                    <!--                <span class="info-box-number">{{ $totalPendingSuperExpress ?? '0' }}</span>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </a>-->
                    <!--</div>-->
                </div>

                
                

                <!-- Total Orders -->
                <div class="row">
                    <!--<div class="col-lg-3 col-6">-->
                    <!--    <div class="small-box bg-danger">-->
                    <!--        <div class="inner">-->
                    <!--            <h3>{{ $totalOrders }}</h3>-->
                    <!--            <p>Total Orders</p>-->
                    <!--        </div>-->
                    <!--        <div class="icon">-->
                    <!--            <i class="fas fa-store"></i>-->
                    <!--        </div>-->
                    <!--        <a href="{{ url('admin-order-details/totalOrder') }}" class="small-box-footer">More info <i-->
                    <!--                class="fas fa-arrow-circle-right"></i></a>-->
                    <!--    </div>-->
                    <!--</div>-->
                    
                     <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $branch }}</h3>
                                <p>All Delivery Branches</p>
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
                                <h3>{{ $bookingbranch }}</h3>
                                <p>All Booking</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-store"></i>
                            </div>
                            <!--<a href="{{ url('/all-booking-branchs') }}" class="small-box-footer">More info <i-->
                             <a href="{{ url('/all-booking-branchs') }}" class="small-box-footer">More info 
                             <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-secondary">
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
                    
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
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
                        <div class="small-box bg-success">
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
                   
                   
                </div>


                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection