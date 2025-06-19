@extends('admin.layout.main')
@push('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <style>
        .total {
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .card {
            margin-bottom: 30px;
        }
    </style>
@endpush
@section('main')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="pl-2 text-bold">{{$deliveryBoyName}} - Earnings</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/delivery-boy-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">My Earnings</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Today's Earnings -->
                    <div class="col-lg-12">
                        <div class="card card-light">
                            <div class="card-header">
                                <h3 class="card-title">Today's Earnings ({{ \Carbon\Carbon::now('Asia/Kolkata')->format('d-m-Y') }})</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped earnings-table" id="todayEarnings">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Amount</th>
                                            <th>Date Time</th>
                                            <th>Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($earningsData['today']['orders'] as $index => $order)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>₹{{ $orderRate }}</td>
                                                <td>{{ $order->datetime }}</td>
                                                <td>{{ $order->order_status }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">No earnings for today.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="total">Total Earnings (Today): ₹{{ $earningsData['today']['total'] }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- This Week's Earnings -->
                    <div class="col-lg-12">
                        <div class="card card-light">
                            <div class="card-header">
                                <h3 class="card-title">This Week's Earnings ({{ \Carbon\Carbon::now('Asia/Kolkata')->startOfWeek()->format('d-m-Y') }} to {{ \Carbon\Carbon::now('Asia/Kolkata')->endOfWeek()->format('d-m-Y') }})</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped earnings-table" id="weekEarnings">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Amount</th>
                                            <th>Date Time</th>
                                            <th>Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($earningsData['this_week']['orders'] as $index => $order)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>₹{{ $orderRate }}</td>
                                                <td>{{ $order->datetime }}</td>
                                                <td>{{ $order->order_status }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">No earnings for this week.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="total">Total Earnings (This Week): ₹{{ $earningsData['this_week']['total'] }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- This Month's Earnings -->
                    <div class="col-lg-12">
                        <div class="card card-light">
                            <div class="card-header">
                                <h3 class="card-title">This Month's Earnings ({{ \Carbon\Carbon::now('Asia/Kolkata')->startOfMonth()->format('d-m-Y') }} to {{ \Carbon\Carbon::now('Asia/Kolkata')->endOfMonth()->format('d-m-Y') }})</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped earnings-table" id="monthEarnings">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Amount</th>
                                            <th>Date Time</th>
                                            <th>Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($earningsData['this_month']['orders'] as $index => $order)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>₹{{ $orderRate }}</td>
                                                <td>{{ $order->datetime }}</td>
                                                <td>{{ $order->order_status }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">No earnings for this month.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="total">Total Earnings (This Month): ₹{{ $earningsData['this_month']['total'] }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Last Month's Earnings -->
                    <div class="col-lg-12">
                        <div class="card card-light">
                            <div class="card-header">
                                <h3 class="card-title">Last Month's Earnings ({{ \Carbon\Carbon::now('Asia/Kolkata')->subMonthNoOverflow()->startOfMonth()->format('d-m-Y') }} to {{ \Carbon\Carbon::now('Asia/Kolkata')->subMonthNoOverflow()->endOfMonth()->format('d-m-Y') }})</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped earnings-table" id="lastMonthEarnings">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Amount</th>
                                            <th>Date Time</th>
                                            <th>Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($earningsData['last_month']['orders'] as $index => $order)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>₹{{ $orderRate }}</td>
                                                <td>{{ $order->datetime }}</td>
                                                <td>{{ $order->order_status }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">No earnings for last month.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="total">Total Earnings (Last Month): ₹{{ $earningsData['last_month']['total'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal for Submitting COD to Branch -->
    <div class="modal fade" id="submit-cod-modal" tabindex="-1" role="dialog" aria-labelledby="submitCodModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="submitCodModalLabel">Submit COD to Branch</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="codSubmitForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="submit_amount">Amount to Submit</label>
                            <input type="number" class="form-control" id="submit_amount" name="amount"
                                placeholder="Enter amount" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="remarks">Remarks (Optional)</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="3"
                                placeholder="Any additional notes"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit Amount</button>
                    </div>
                </form>
            </div>
        </div>
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
    <script>
        $(document).ready(function () {
            // Initialize DataTables for all tables with class 'earnings-table'
            $('.earnings-table').each(function () {
                $(this).DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
                    "ordering": true,
                    "paging": true,
                    "searching": true,
                    "destroy": true
                }).buttons().container().appendTo($(this).closest('.card-body').find('.card-header'));
            });

            // Handle COD form submission
            $('#codSubmitForm').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('delivery.boy.cod.submit') }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            Toast("success", response.message);
                            $('#submit-cod-modal').modal('hide');
                            location.reload();
                        } else {
                            Toast("error", response.message);
                        }
                    },
                    error: function (xhr) {
                        Toast("error", "Error occurred. Please try again.");
                    }
                });
            });

            // Toast notification function
            function Toast(icon, message) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });

                Toast.fire({
                    icon: icon,
                    title: message
                });
            }
        });
    </script>
@endpush