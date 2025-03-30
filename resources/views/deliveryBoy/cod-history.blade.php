@extends('deliveryBoy.layout.main')
@push('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush
@section('main')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Total COD History</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/delivery-boy-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Total COD History</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-light">
                                    <div class="card-header">
                                        <h3 class="card-title">Today COD Amount</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-secondary"><i
                                                            class="fas fa-solid fa-money-bill"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text"><strong>Today COD</strong></span>
                                                        <span class="info-box-number">{{ $todayCod ?? 0.0 }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-success"><i
                                                            class="fas fa-solid fa-money-bill"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text"><strong>Submit COD</strong></span>
                                                        <span class="info-box-number">{{ $todaySubmit ?? 0.0 }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-danger"><i
                                                            class="fas fa-solid fa-money-bill"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text"><strong>Pending COD</strong></span>
                                                        <span class="info-box-number">{{ $todayPending ?? 0.0 }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card card-light">
                                    <div class="card-header">
                                        <h3 class="card-title">Total COD Amount</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-secondary"><i
                                                            class="fas fa-solid fa-money-bill"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text"><strong>Total COD</strong></span>
                                                        <span class="info-box-number">{{ $totalCod ?? 0.0 }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-success"><i
                                                            class="fas fa-solid fa-money-bill"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text"><strong>Submit COD</strong></span>
                                                        <span class="info-box-number">{{ $totalSubmit ?? 0.0 }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-danger"><i
                                                            class="fas fa-solid fa-money-bill"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text"><strong>Pending COD</strong></span>
                                                        <span class="info-box-number">{{ $totalPending ?? 0.0 }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="card card-light">
                            <div class="card-header">
                                <h1 class="card-title">All COD History</h1>
                                {{-- <form id="codAmount">
                                    <div class="input-group input-group-sm">
                                        <input type="hidden" name="delivery_boy" name="delivery_boy_id"
                                            id="delivery_boy_id" value="{{ request()->segment(2) }}">
                                        <input type="number" class="form-control" name="amount" id="amount"
                                            placeholder="0.0">
                                        <span class="input-group-append">
                                            <button class="btn btn-info btn-flat">Submit</button>
                                        </span>
                                    </div>
                                </form> --}}
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Amount</th>
                                            <th>Date Time</th>
                                            <th>Branch M. Name</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodyData">
                                        @include('admin.inc.cod-history')
                                    </tbody>
                                </table>
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
    </script>
    {{-- <script>
        $(document).ready(function() {
            $('#codAmount').submit(function(e) {
                e.preventDefault();
                var amount = $("#amount").val();
                var delivery_boy = $("#delivery_boy_id").val();
                if (amount == '') {
                    Toast("warning", "Please enter amount");
                    return;
                }
                $.ajax({
                    url: "{{ url('/delivery-cod-amount') }}",
                    type: 'POST',
                    data: {
                        amount: amount,
                        delivery_boy: delivery_boy
                    },
                    success: function(response) {
                        if (response.success) {
                            Toast("success", response.message);
                            $("#amount").val('');
                            $('#bodyData').html(response.html);
                        } else {
                            Toast("error", "Amount not submitted");
                        }
                    }
                });
            });
        });
    </script> --}}
@endpush
