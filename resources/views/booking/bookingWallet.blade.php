@extends('booking.layout.main')
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
                        <h1>My Wallet</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/booking-dashboard') }}">Home</a></li>
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
                                <h3 class="card-title font-weight-bold">Wallet Amount</h3>
                                <button type="button" class="btn btn-primary btn-lg float-right" data-toggle="modal"
                                    data-target="#exampleModal">
                                    ₹ {{ $amount->total ?? '0.0' }}
                                    <span class="badge badge-light"><i class="fas fa-solid fa-plus"></i></span>
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
                                            <th>Debit account</th>
                                            <th>Total</th>
                                            <th>Narration</th>
                                            <th>Status</th>
                                            <th>Ref.No.</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodyData">
                                        @include('booking.inc.wallet')
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
    <!-- Modal for add wallet amount -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Amount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="walletAdd">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="0.0" aria-label="Recipient's username"
                                aria-describedby="basic-addon2" name="amount" required>
                            <div class="input-group-append">
                                <button class="btn btn-success">Pay Now</button>
                            </div>
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

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <!-- Page specific script -->
    <script>
        $('#walletAdd').on('submit', function (e) {
            e.preventDefault();
            let amount = $('input[name="amount"]').val();
            if (!amount || isNaN(amount) || amount <= 0) {
                alert("Please enter a valid positive amount");
                return;
            }

            let amountInPaise = amount * 100;

            let rzp = new Razorpay({
                // "key": "{{ env('RAZORPAY_KEY', 'rzp_live_swdLQ9ocZUoa9F') }}", 
                "key": "{{ env('RAZORPAY_KEY', 'rzp_test_BCqQIjZcNVZHVw') }}",
                "amount": amountInPaise,
                "currency": "INR",
                "name": "Delhi Parcel",
                "description": "Add to Wallet",
                "handler": function (response) {
                    // ✅ On success
                    $.ajax({
                        url: "{{ route('booking.addWalletAmount') }}",
                        type: "POST",
                        data: {
                            amount: amount,
                            razorpay_payment_id: response.razorpay_payment_id,
                            status: 'success',
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (res) {
                            if (res.success) {
                                $('#exampleModal').modal('hide'); // Close modal
                                alert(res.message); // Show success message
                                // Update DataTable dynamically
                                $('#bodyData').html(res.html); // Update table body with new data
                                $('#example1').DataTable().destroy(); // Destroy existing DataTable
                                $('#example1').DataTable({ // Reinitialize DataTable
                                    "responsive": true,
                                    "lengthChange": false,
                                    "autoWidth": false,
                                    "buttons": ["copy", "excel", "pdf", "print"]
                                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                                // Update wallet balance display
                                $('.wallet-amount').text('₹ ' + res.data.total.toFixed(2));
                            } else {
                                // alert(res.message); 
                                console.error('Error:', res);
                            }
                        },
                        error: function (xhr) {
                            // alert('An error occurred while processing your request.');
                            console.error('AJAX Error:', xhr);
                        }
                    });
                },
                "prefill": {
                    "contact": "{{ $mobile ?? '9999999999' }}",
                    "name": "User",
                    "email": "user@example.com"
                },
                "theme": {
                    "color": "#28a745"
                },
                "modal": {
                    "ondismiss": function () {
                        // ❌ If payment is dismissed/cancelled
                        $.ajax({
                            url: "{{ route('booking.wallet') }}",
                            type: "POST",
                            data: {
                                amount: amount,
                                status: 'cancelled',
                                _token: "{{ csrf_token() }}"
                            },
                            success: function (res) {
                                // alert(res.message); 
                                console.log('Cancellation Response:', res);
                            },
                            error: function (xhr) {
                                // alert('Error occurred while cancelling payment.');
                                console.error('AJAX Error:', xhr);
                            }
                        });
                    }
                }
            });

            rzp.on('payment.failed', function (response) {
                // ❌ If payment fails
                $.ajax({
                    url: "{{ route('booking.wallet') }}",
                    type: "POST",
                    data: {
                        amount: amount,
                        status: 'failed',
                        reason: response.error.description,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (res) {
                        // alert('Payment failed: ' + res.message); 
                        console.log('Failure Response:', res);
                    },
                    error: function (xhr) {
                        // alert('Error occurred while processing failed payment.');
                        console.error('AJAX Error:', xhr);
                    }
                });
            });

            rzp.open();
        });
    </script>


@endpush