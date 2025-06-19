@extends('delivery.layout.main')
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
                        <h1>Order Details</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/delivery-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Order Details</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h3 class="card-title">All Orders</h3>
                                    </div>
                                    <div class="col-lg-6 text-lg-right">
                                        @foreach ($data as $value)
                                            @if (empty($value->assign_to))
                                                <button class="btn btn-sm btn-light font-weight-bold btn-assign d-none"
                                                    id="assignOrder">Assign
                                                    Now</button>
                                            @endif
                                            @if (empty($value->sender_order_pin_by))
                                                <button class="btn btn-sm btn-light font-weight-bold btn-transfer d-none"
                                                    id="transferOrder">Transfer
                                                    Now</button>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped table-sm">
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
                                            <th>Assign Order</th>
                                            <th>Transfer Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $sr = 1;
                                        @endphp
                                        @foreach ($data as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $item->order_id }}</td>
                                                <td>{{ $item->datetime }}</td>
                                                @if ($item->parcel_type == 'Direct')
                                                    <td>
                                                        <span><b>Name: </b>{{ $item->sender_name }}</span> <br>
                                                        <span><b>Email: </b>{{ $item->sender_email }}</span> <br>
                                                        <span><b>Number: </b>{{ $item->sender_number }}</span> <br>
                                                        <span><b>Address: </b>{{ $item->sender_address }}</span> <br>
                                                    </td>
                                                @else
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
                                                @endif
                                                <td>
                                                    <span><b>Name: </b>{{ $item->receiver_name }}</span> <br>
                                                    <span><b>Email: </b>{{ $item->receiver_email }}</span> <br>
                                                    <span><b>Number: </b>{{ $item->receiver_cnumber }}</span> <br>
                                                    <span><b>Address: </b>{{ $item->receiver_add }}</span> <br>
                                                </td>
                                                <td>{{ $item->price }}</td>
                                                <td>{{ $item->payment_mode }}</td>
                                                @if ($item->parcel_type == 'Direct')
                                                    <td>{{ $item->sender_pincode }}</td>
                                                @else
                                                    <td>{{ $item->order->pincode }}</td>
                                                @endif
                                                <td>{{ $item->receiver_pincode }}</td>
                                                <td class="text-capitalize">{{ $item->parcel_type }}</td>
                                                <td>
                                                    @if ($item->order_status !== 'Delivered' && $item->order_status !== 'Cancelled')
                                                        <button class="btn btn-sm btn-warning status"
                                                            data-id="{{ $item->id }}"
                                                            title="{{ $item->status_message }}">
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
                                                <td>
                                                    @if ($item->order_status == 'Delivered' || $item->order_status == 'Cancelled')
                                                        @if ($item->order_status == 'Cancelled')
                                                            <span class="badge badge-danger"
                                                                title="{{ $item->status_message }}">NA</span>
                                                        @else
                                                            <span class="badge badge-success"
                                                                title="{{ $item->status_message }}">{{ $item->dlyBoy->name ?? '-' }}</span>
                                                        @endif
                                                    @else
                                                        <button class="btn btn-sm btn-secondary assign"
                                                            data-id="{{ $item->id }}"
                                                            title="{{ $item->status_message }}">
                                                            @if ($item->assign_to)
                                                                <span class="font-weight-bold font-weight-light">
                                                                    {{ $item->dlyBoy->name ?? '-' }}
                                                                </span>
                                                            @else
                                                                Assign To
                                                            @endif
                                                        </button>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $item->dlyBoy1->name ?? '-' }} |
                                                    {{ $item->sender_order_status ?? '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>
@endpush
