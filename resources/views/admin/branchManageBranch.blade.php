@extends('admin.layout.main')
@push('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush
@section('main')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Branch Details</h1>
                        <b><h1 class="font-weight-bold">Branch Name : {{$branchdata->fullname ?? ''}}</h1></b>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/admin-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/all-branch') }}">All Branch</a></li>
                            <li class="breadcrumb-item active">Branch Details</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                {{-- today orders --}}
                <h5 class="mt-3 font-weight-bold">Today Order Details</h5>
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/admin-order-details/' . request()->segment(2) . '/toDayOrder') }}" class="text-dark">
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
                        <a href="{{ url('/admin-order-details/' . request()->segment(2) . '/toDayPendingOrder') }}"
                            class="text-dark">
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
                        <a href="{{ url('/admin-order-details/' . request()->segment(2) . '/toDayOrderPicUp') }}"
                            class="text-dark">
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
                        <a href="{{ url('/admin-order-details/' . request()->segment(2) . '/toDayCompleteOrder') }}"
                            class="text-dark">
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
                        <a href="{{ url('/admin-order-details/' . request()->segment(2) . '/toDayCancelledOrder') }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-solid fa-dolly"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Today Cancelled</strong></span>
                                    <span class="info-box-number">{{ $toDayCancelledOrder ?? 0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- total orders --}}
                <h5 class="mt-3 font-weight-bold">Total Order Details</h5>
                <div class="row">
                    <div class="col-md-3 col-sm-6 col-12">
                        <a href="{{ url('/admin-order-details/' . request()->segment(2) . '/totalOrder') }}" class="text-dark">
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
                        <a href="{{ url('/admin-order-details/' . request()->segment(2) . '/totalPendingOrder') }}"
                            class="text-dark">
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
                        <a href="{{ url('/admin-order-details/' . request()->segment(2) . '/totalOrderPicUp') }}" class="text-dark">
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
                        <a href="{{ url('/admin-order-details/' . request()->segment(2) . '/totalCompleteOrder') }}"
                            class="text-dark">
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
                        <a href="{{ url('/admin-order-details/' . request()->segment(2) . '/totalCanceledOrder') }}"
                            class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger"><i class="fas fa-solid fa-dolly"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Total Cancelled</strong></span>
                                    <span class="info-box-number">{{ $totalCanceledOrder ?? 0 }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                {{-- Wallet & Delivery Boy--}}
                <h5 class="mt-3 font-weight-bold">Manage Wallet</h5>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-12">
                        <a href="{{ url('/wallet-details/' . request()->segment(2)) }}" class="text-dark">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-money-bill"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><strong>Wallet</strong></span>
                                    <span class="info-box-number">₹ {{ $amount->total ?? '0.0' }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    {{-- <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text"><strong>Total Delivery Boy</strong></span>
                                <span class="info-box-number">{{ $totalDlyBoy ?? 0 }}</span>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </section>
    </div>
@endsection
