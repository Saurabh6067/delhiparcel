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
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Manage Wallet</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/seller-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('/all-branch') }}">All Branch</a></li>
                            <li class="breadcrumb-item"><a
                                    href="{{ url('/branch-Manage-Branch/' . request()->segment(2)) }}">Branch Details</a>
                            </li>
                            <li class="breadcrumb-item active">Wallet</li>
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
                        <div class="card">
                            <div class="card-header">
                                <button class="btn btn-primary">Total <span class="badge badge-light">₹
                                        {{ $amount->total ?? '0.0' }}</span></button>
                                @if (!empty($amount->total))
                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal"
                                        data-target="#CreditWallet">Credit Wallet
                                        <span class="badge badge-light"><i class="fas fa-solid fa-plus"></i></span>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal"
                                        data-target="#DebitWallet">Debit Wallet
                                        <span class="badge badge-light"><i class="fas fa-solid fa-minus"></i></span>
                                    </button>
                                @else
                                    -- No amount in wallet --
                                @endif
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Date Time</th>
                                            <th>Credit Amount</th>
                                            <th>Debit account</th>
                                            <th>Total</th>
                                            <th>Narration</th>
                                            <th>Status</th>
                                            <th>Ref.No.</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodyData">
                                        @include('admin.inc.wallet')
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
    <!-- Modal for credit wallet amount -->
    <div class="modal fade" id="CreditWallet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Credit Wallet Amount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="walletCredit">
                        <div class="mb-3">
                            <label for="amount">Enter Credit amount</label>
                            <input type="text" class="form-control" placeholder="Enter amount 0.0" name="amount"
                                id="amount" required>
                        </div>
                        <div class="mb-3">
                            <label for="remark">Enter Remark</label>
                            <input type="text" class="form-control" placeholder="Enter remark" name="remark"
                                id="remark" required>
                        </div>
                        <div class="input-group-append">
                            <input type="hidden" name="walletType" value="Credit">
                            <input type="hidden" name="id" value="{{ request()->segment(2) }}">
                            <button class="btn btn-success">Credit amount</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for debit wallet amount -->
    <div class="modal fade" id="DebitWallet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Debit Wallet Amount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="walletDebit">
                        <div class="mb-3">
                            <label for="amount">Enter Debit amount</label>
                            <input type="text" class="form-control" placeholder="Enter amount 0.0" name="amount"
                                id="amount" required>
                        </div>
                        <div class="mb-3">
                            <label for="remark">Enter Remark</label>
                            <input type="text" class="form-control" placeholder="Enter remark" name="remark"
                                id="remark" required>
                        </div>
                        <div class="input-group-append">
                            <input type="hidden" name="walletType" value="Debit">
                            <input type="hidden" name="id" value="{{ request()->segment(2) }}">
                            <button class="btn btn-danger">Debit amount</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
    <!-- Page specific script -->
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        $(document).ready(function() {
            $("#walletCredit").on("submit", function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.WalletAmount') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        $('#walletCredit')[0].reset();
                        if (response.success) {
                            Toast("success", response.message);
                            $('#bodyData').html(response.html);
                            $('#CreditWallet').modal('hide');
                        } else {
                            Toast("error", response.message);
                        }
                    },
                    error: function(err) {
                        Toast("error", "Amount not added!");
                    }
                });
            });
            $("#walletDebit").on("submit", function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.WalletAmount') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        $('#walletDebit')[0].reset();
                        if (response.success) {
                            Toast("success", response.message);
                            $('#bodyData').html(response.html);
                            $('#DebitWallet').modal('hide');
                        } else {
                            Toast("error", response.message);
                        }
                    },
                    error: function(err) {
                        Toast("error", "Amount not added!");
                    }
                });
            });
        });
    </script>
@endpush
