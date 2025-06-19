@extends('admin.layout.main')
@push('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Bootstrap Datepicker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
@endpush
@section('main')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Wallet Recharge History</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/seller-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Wallet Recharge History</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Single Date Filter -->
                    <div class="col-md-12 mb-3">
                        <form method="GET" action="{{ route('admin.todayWallet') }}">
                            <div class="form-group row">
                                <label for="date" class="col-sm-2 col-form-label">Select Date:</label>
                                <div class="col-sm-4">
                                    <input type="text" name="date" id="date" class="form-control" value="{{ request('date') }}" placeholder="Select date" readonly>
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('admin.todayWallet') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Table -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title font-weight-bold">Total Amount</h3>
                                <button type="button" class="btn btn-primary btn-sm float-right">
                                    @php
                                        $totalAmount = 0.0;
                                    @endphp
                                    @foreach ($todayWallet as $walletHistory)
                                        @php
                                            $totalAmount += $walletHistory->c_amount;
                                        @endphp
                                    @endforeach
                                    ₹ {{ number_format($totalAmount, 2) }}
                                    <span class="badge badge-light"></span>
                                </button>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Date Time</th>
                                            <th>Credit Amount</th>
                                            <th>Total</th>
                                            <th>Narration</th>
                                            <th>Branch Name</th>
                                            <th>Branch Type</th>
                                            <th>Status</th>
                                            <th>Ref.No.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $sr = 1;
                                        @endphp
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $sr++ }}</td>
                                                <td>{{ $item->datetime }}</td>
                                                <td>{{ $item->c_amount ?? '-' }}</td>
                                                <td>{{ $item->total }}</td>
                                                <td class="text-uppercase">
                                                    @if (!empty($item->adminid))
                                                        {{ $item->users->type . '/' . $item->msg }}
                                                    @else
                                                        @if ($item->msg == 'credit')
                                                            Credit
                                                        @elseif($item->msg == 'debit')
                                                            Debit
                                                        @else
                                                            {{ $item->msg }}
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>{{ $item->branch->fullname ?? '-' }}</td>
                                                <td>{{ $item->branch->type ?? '-' }}</td>
                                                <td>
                                                    @if ($item->status == 'success')
                                                        <span class="font-weight-bold text-success">{{ $item->status }}</span>
                                                    @elseif ($item->status == 'pending')
                                                        <span class="font-weight-bold text-warning">{{ $item->status }}</span>
                                                    @else
                                                        <span class="font-weight-bold text-danger">{{ $item->status }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->refno ?? '-' }}</td>
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
    <!-- Bootstrap Datepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <!-- Page specific script -->
    <script>
        $(function() {
            // Initialize DataTable
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            // Initialize Datepicker
            $('#date').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });
        });
    </script>
@endpush