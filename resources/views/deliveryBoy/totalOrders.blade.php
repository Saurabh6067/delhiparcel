@extends('deliveryBoy.layout.main')
@section('main')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Total Orders</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/delivery-boy-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item">Total Orders</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title font-weight-bold">All Order</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0" style="height: 300px;">
                                <table class="table table-head-fixed text-nowrap table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Order Id</th>
                                            <th>Order Date</th>
                                            <th>Sender Name</th>
                                            <th>Receiver Name</th>
                                            <th>Amount</th>
                                            <th>Payment Status</th>
                                            <th>Pickup PinCode</th>
                                            <th>Delivery PinCode</th>
                                            <th>Order Type</th>
                                            <th>Order Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($totalOrders as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $item->order_id }}</td>
                                                <td>{{ $item->datetime }}</td>
                                                @if ($item->parcel_type == 'delivery')
                                                    <td>
                                                        <span><b>Name: </b>{{ $item->order->fullname }}</span> <br>
                                                        <span><b>Email: </b>{{ $item->order->email }}</span> <br>
                                                        <span><b>Number: </b>{{ $item->order->phoneno }}</span> <br>
                                                        <span><b>Address: </b>{{ $item->order->fulladdress }}</span>
                                                    </td>
                                                    <td>
                                                        <span><b>Name: </b>{{ $item->receiver_name }}</span> <br>
                                                        <span><b>Email: </b>{{ $item->receiver_email }}</span> <br>
                                                        <span><b>Number: </b>{{ $item->receiver_cnumber }}</span> <br>
                                                        <span><b>Address: </b>{{ $item->receiver_add }}</span> <br>
                                                    </td>
                                                @else
                                                    @if ($item->parcel_type == 'Pickup')
                                                        <td>
                                                            <span><b>Name: </b>{{ $item->receiver_name }}</span> <br>
                                                            <span><b>Email: </b>{{ $item->receiver_email }}</span> <br>
                                                            <span><b>Number: </b>{{ $item->receiver_cnumber }}</span> <br>
                                                            <span><b>Address: </b>{{ $item->receiver_add }}</span> <br>
                                                        </td>
                                                        <td>
                                                            <span><b>Name:
                                                                </b>{{ $item->order->fullname ?? $item->sender_name }}</span>
                                                            <br>
                                                            <span><b>Email:
                                                                </b>{{ $item->order->email ?? $item->sender_email }}</span>
                                                            <br>
                                                            <span><b>Number:
                                                                </b>{{ $item->order->phoneno ?? $item->sender_number }}</span>
                                                            <br>
                                                            <span><b>Address:
                                                                </b>{{ $item->order->fulladdress ?? $item->sender_address }}</span>
                                                        </td>
                                                    @else
                                                    @endif
                                                @endif
                                                <td>{{ $item->price }}</td>
                                                <td class="text-uppercase">
                                                    {{ $item->payment_mode ?? $item->payment_methods }}</td>
                                                <td>{{ $item->order->pincode ?? $item->senderPinCode }}</td>
                                                <td>{{ $item->receiver_pincode ?? $item->receiverPinCode }}</td>
                                                <td class="text-capitalize">{{ $item->parcel_type ?? 'Direct' }}</td>
                                                <td>
                                                    @if ($item->order_status !== 'Delivered' && $item->order_status !== 'Cancelled')
                                                        <button class="btn btn-sm btn-warning status"
                                                            data-id="{{ $item->id }}"
                                                            title="{{ $item->status_message }}"
                                                            data-action="{{ request()->segment(2) }}">
                                                            <span class="font-weight-bold font-weight-light ">
                                                                {{ $item->order_status }}
                                                            </span>
                                                        </button>
                                                    @elseif($item->order_status == 'Delivered')
                                                        <span class="badge badge-success"
                                                            title="{{ $item->status_message }}">{{ $item->order_status }}</span>
                                                    @elseif($item->order_status == 'Cancelled')
                                                        <span class="badge badge-danger"
                                                            title="{{ $item->status_message }}">{{ $item->order_status }}</span>
                                                    @else
                                                        <span class="badge badge-danger"
                                                            title="{{ $item->status_message }}">{{ $item->order_status }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title font-weight-bold">Total Direct Order</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0" style="height: 300px;">
                                <table class="table table-head-fixed text-nowrap table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Order Id</th>
                                            <th>Order Date</th>
                                            <th>Sender Name</th>
                                            <th>Receiver Name</th>
                                            <th>Amount</th>
                                            <th>Payment Status</th>
                                            <th>Pickup PinCode</th>
                                            <th>Delivery PinCode</th>
                                            <th>Order Type</th>
                                            <th>Order Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($directOrders as $key => $item)
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item->order_id }}</td>
                                            <td>{{ $item->datetime }}</td>
                                            <td>
                                                <span><b>Name: </b>{{ $item->sender_name }}</span> <br>
                                                <span><b>Number: </b>{{ $item->sender_number }}</span> <br>
                                                <span><b>Email: </b>{{ $item->sender_email }}</span> <br>
                                                <span><b>Address: </b>{{ $item->sender_address }}</span> <br>
                                            </td>
                                            <td>
                                                <span><b>Name: </b>{{ $item->receiver_name }}</span> <br>
                                                <span><b>Number: </b>{{ $item->receiver_number }}</span> <br>
                                                <span><b>Email: </b>{{ $item->receiver_email }}</span> <br>
                                                <span><b>Address: </b>{{ $item->receiver_address }}</span> <br>
                                            </td>
                                            <td>{{ $item->price }}</td>
                                            <td class="text-uppercase">
                                                {{ $item->payment_mode ?? $item->payment_methods }}</td>
                                            <td>{{ $item->order->pincode ?? $item->senderPinCode }}</td>
                                            <td>{{ $item->receiver_pincode ?? $item->receiverPinCode }}</td>
                                            <td class="text-capitalize">{{ $item->parcel_type ?? 'Direct' }}</td>
                                            <td>
                                                @if ($item->order_status !== 'Delivered' && $item->order_status !== 'Cancelled')
                                                    <button class="btn btn-sm btn-warning status"
                                                        data-id="{{ $item->id }}" title="{{ $item->status_message }}"
                                                        data-action="{{ request()->segment(2) }}">
                                                        <span class="font-weight-bold font-weight-light ">
                                                            {{ $item->order_status }}
                                                        </span>
                                                    </button>
                                                @elseif($item->order_status == 'Delivered')
                                                    <span class="badge badge-success"
                                                        title="{{ $item->status_message }}">{{ $item->order_status }}</span>
                                                @elseif($item->order_status == 'Cancelled')
                                                    <span class="badge badge-danger"
                                                        title="{{ $item->status_message }}">{{ $item->order_status }}</span>
                                                @else
                                                    <span class="badge badge-danger"
                                                        title="{{ $item->status_message }}">{{ $item->order_status }}</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
@endpush
