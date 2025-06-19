@extends('seller.layout.main')
@push('style')
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush
@section('main')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>All Order History</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/booking-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item">All Order History</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-6 my-auto">
                                        <h3 class="card-title text-center">Order History</h3>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group float-right">
                                            <input type="text" name="daterange" id="daterange" class="form-control" placeholder="Select Date Range" />
                                            <button type="button" id="addFilter" class="btn btn-danger mt-2 mr-2">Add Filter</button>
                                            <button type="button" id="removeFilter" class="btn btn-secondary mt-2">Remove Filter</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                @if ($data->isEmpty())
                                    <div class="alert alert-info text-center">
                                        No orders found.
                                    </div>
                                @else
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Sr No</th>
                                                <th>Date</th>
                                                <th>Order ID</th>
                                                <th>Sender Name</th>
                                                <th>Receiver Name</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bodyData">
                                            @php
                                                $sr = 1;
                                                $totalAmount = 0.0;
                                            @endphp
                                            @foreach ($data as $order)
                                                <tr>
                                                    <td>{{ $sr++ }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($order->updated_at)->format('d-m-Y H:i:s') }}</td>
                                                    <td>{{ $order->order_id }}</td>
                                                    <td>{{ $order->sender_name }}</td>
                                                    <td>{{ $order->receiver_name }}</td>
                                                    <td>{{ '₹ ' . number_format($order->codAmount, 2) }}</td>
                                                </tr>
                                                @php
                                                    $totalAmount += $order->codAmount;
                                                @endphp
                                            @endforeach
                                            <input type="hidden" id="totalAmount" value="{{ $totalAmount }}">
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5" class="text-center"><b>Total Amount</b></td>
                                                <td id="amount"><b>{{ '₹ ' . number_format($totalAmount, 2) }}</b></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <!-- DataTables & Plugins -->
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
    <!-- Moment.js for date handling -->
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script>
        $(function() {
            // Initialize DateRangePicker
            $('#daterange').daterangepicker({
                locale: {
                    format: 'DD-MM-YYYY'
                }
            });

            // Initialize DataTable only if table exists
            let table = null;
            if ($('#example1').length) {
                table = $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "excel", "pdf", "print"]
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            }

            // Add Filter Button Click
            $('#addFilter').on('click', function() {
                let dateRange = $('#daterange').val();
                if (dateRange) {
                    let dates = dateRange.split(' - ');
                    let startDate = moment(dates[0], 'DD-MM-YYYY').format('YYYY-MM-DD');
                    let endDate = moment(dates[1], 'DD-MM-YYYY').format('YYYY-MM-DD');

                    // AJAX request to filter data
                    $.ajax({
                        url: '{{ route("seller.allCodHistory") }}',
                        type: 'GET',
                        data: {
                            start_date: startDate,
                            end_date: endDate
                        },
                        success: function(response) {
                            console.log('Filter Response:', response); // Debug response
                            updateTable(response.data);
                        },
                        error: function(xhr) {
                            console.error('Filter Error:', xhr.responseText);
                            alert('Error fetching filtered data');
                        }
                    });
                } else {
                    alert('Please select a date range');
                }
            });

            // Remove Filter Button Click
            $('#removeFilter').on('click', function() {
               window.location.reload();
            });

            // Function to update table content
            function updateTable(data) {
                console.log('Updating table with data:', data); // Debug data
                // If no data, show message
                if (data.length === 0) {
                    $('.card-body').html('<div class="alert alert-info text-center">No orders found.</div>');
                    if (table) {
                        table.destroy();
                        table = null;
                    }
                    return;
                }

                // If table doesn't exist, create it
                if (!table) {
                    $('.card-body').html(`
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sr No</th>
                                    <th>Date</th>
                                    <th>Order ID</th>
                                    <th>Sender Name</th>
                                    <th>Receiver Name</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody id="bodyData"></tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-center"><b>Total Amount</b></td>
                                    <td id="amount"></td>
                                </tr>
                            </tfoot>
                        </table>
                    `);
                    table = $("#example1").DataTable({
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "buttons": ["copy", "excel", "pdf", "print"]
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                }

                // Clear existing table data
                table.clear().draw();

                // Update table with new data
                let sr = 1;
                let totalAmount = 0.0;
                data.forEach(function(order) {
                    table.row.add([
                        sr++,
                        moment(order.updated_at).format('DD-MM-YYYY HH:mm:ss'),
                        order.order_id,
                        order.sender_name,
                        order.receiver_name,
                        '₹ ' + parseFloat(order.codAmount).toFixed(2)
                    ]).draw(false); // Use draw(false) to prevent full redraw
                    totalAmount += parseFloat(order.codAmount);
                });

                // Force table refresh
                table.draw();

                // Update total amount
                $('#totalAmount').val(totalAmount);
                $('#amount').html('<b>₹ ' + totalAmount.toFixed(2) + '</b>');
            }

            // Update total amount on page load if data exists
            let total = document.getElementById("totalAmount");
            if (total) {
                document.getElementById("amount").innerText = `₹ ${parseFloat(total.value).toFixed(2)}`;
            }
        });
    </script>
@endpush