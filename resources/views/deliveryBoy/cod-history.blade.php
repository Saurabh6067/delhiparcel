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
                   
                    <!-- Total COD Amount Card -->
                    <div class="col-lg-12">
                        <div class="card card-light">
                            <div class="card-header">
                                <h3 class="card-title">Total COD Amount</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#submit-cod-modal">
                                        Submit COD to Branch
                                    </button>
                                </div>
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
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- All COD History Table -->
                    <!-- All COD History Table -->
                    <div class="col-lg-12">
                        <div class="card card-light">
                            <div class="card-header">
                                <h3 class="card-title">All COD History</h3>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Amount</th>
                                            <th>Date Time</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(isset($codAmounts) && count($codAmounts) > 0)
                                            @foreach($codAmounts as $key => $cod)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    @if($cod->type == 'Credited')
                                                        <td style="color:green">{{ '+' . $cod->amount }}</td>
                                                    @elseif($cod->type == 'Debited')
                                                        <td style="color:red">{{ '-' . $cod->amount }}</td>
                                                    @else
                                                        <td>{{ $cod->amount }}</td>
                                                    @endif
                                                    <td>{{ $cod->datetime }}</td>
                                                    <td>{{ $cod->type ?? '' }}</td>
                                                    <td style="color: {{ $cod->status == 'Pending' ? 'orange' : ($cod->status == 'Approve' ? 'green' : 'red') }}">
                                                        {{ $cod->status }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="4" class="text-center">No COD history found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
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
                        <span aria-hidden="true">&times;</span>
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
        $(function () {
            // Initialize DataTable
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            // Handle form submission
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
                            // Show success message
                            Toast("success", response.message);
                            // Close the modal
                            $('#submit-cod-modal').modal('hide');
                            // Reload the page to update the data
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
        });

        // Toast notification function
        function Toast(icon, message) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                onOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: icon,
                title: message
            });
        }
    </script>
@endpush