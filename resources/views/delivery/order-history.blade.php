@extends('delivery.layout.main')
@push('style')
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
@endpush
@section('main')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>COD Order History</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/delivery-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item">COD Order History</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Today COD History  -->
                    <div class="col-md-12">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <div class="row">
                                    <h3 class="card-title text-center">Today COD History</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Date</th>
                                            <th>Order Id</th>
                                            <th>Order Status</th>
                                            <th>Delivery Boy</th>
                                            <th>Payment Type</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $sr = 1;
                                            $totalAmount = 0.0;
                                        @endphp
                                        @foreach ($todayOrders as $codHistory)
                                            <tr>
                                                <td>{{ $sr++ }}</td>
                                                <td>{{ $codHistory->datetime }}</td>
                                                <td>{{ $codHistory->order->order_id }}</td>
                                                <td>{{ $codHistory->order->order_status }}</td>
                                                <td>{{ $codHistory->deliveryBoy->name }}</td>
                                                <td>{{ $codHistory->pyment_method }}</td>
                                                <td>{{ $codHistory->order->price . ' ₹' }}</td>
                                            </tr>
                                            @php
                                                $totalAmount += $codHistory->order->price;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" class="text-center">Total Amount</th>
                                            <th>{{ $totalAmount . ' ₹' }}</th>
                                        </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Total COD History  -->
                    <div class="col-md-12">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 my-auto">
                                        <h3 class="card-title text-center">This Month COD</h3>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="far fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" name="monthYear"
                                                id="datepicker" data-id="{{ request()->segment(2) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" id="bodyData">
                                @include('admin.inc.monthOrderHistory')
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

    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <script>
        $(function() {
            // Page specific script 
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $("#example2").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
        $(function() {
            // Get current month and year
            var currentDate = new Date();
            var currentMonth = $.datepicker.formatDate('MM yy', currentDate);

            // Set default value to current month
            $("#datepicker").val(currentMonth);

            $("#datepicker").datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: "MM yy",
                maxDate: '0Y',
                minDate: '-1Y',
            });
        });

        $(document).on('change', '#datepicker', function() {
            var monthYear = $(this).val();
            var id = $(this).data('id');
            $.ajax({
                url: "{{ route('delivery.monthOrderHistory') }}",
                type: 'POST',
                data: {
                    monthYear: monthYear,
                    id: id,
                },
                success: function(response) {
                    if (response.success) {
                        $('#bodyData').html(response.html);
                    } else {
                        alert('No data found');
                    }
                }
            });
        });
    </script>
@endpush
