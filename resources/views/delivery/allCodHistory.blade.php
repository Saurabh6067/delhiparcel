@extends('delivery.layout.main')
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
                        <h1>All COD History</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/delivery-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item">All COD History</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Total COD Amount Card -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">Total Branch COD Amount</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#modal-submit-cod">
                                        Submit COD
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <h2 class="text-center">₹ {{ $totalCod ?? 0.0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-6 my-auto">
                                        <h3 class="card-title text-center">Today COD History</h3>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="float-right">
                                            <form id="allCodAmount">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="far fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                    <input type="text" class="form-control form-control-sm" name="daterange"
                                                        id="reservation">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Date</th>
                                            <th>Delivery Boy</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                            <th>Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodyData">
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach ($data as $row)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $row->datetime }}</td>
                                                <td>
                                                    @php
                                                        $deliveryBoy = App\Models\DlyBoy::find($row->delivery_boy_id);
                                                    @endphp
                                                    {{ $deliveryBoy->name ?? 'Transfer to Admin' }}
                                                </td>
                                                <td>
                                                    @if($row->type == 'Received')
                                                        <span class="text-success font-weight-bold">{{ $row->type }}</span>
                                                    @elseif($row->type == 'Debited')
                                                        <span class="text-danger font-weight-bold">{{ $row->type }}</span>
                                                    @else
                                                        {{ $row->type }}
                                                    @endif
                                                </td>
                                                <!--<td>-->
                                                <!--    @if ($row->status == 'Pending')-->
                                                <!--        <a href="#" class="status-link" data-toggle="modal" data-target="#modal-change-status"-->
                                                <!--        data-id="{{ $row->id }}" data-status="{{ $row->status }}"-->
                                                <!--        style="color: orange;">-->
                                                <!--        {{ $row->status }}-->
                                                <!--        </a>-->
                                                <!--    @else-->
                                                <!--        <span style="color: {{ $row->status == 'Approve' ? 'green' : 'red' }}; cursor: not-allowed;">-->
                                                <!--        {{ $row->status }}-->
                                                <!--        </span>-->
                                                <!--    @endif-->
                                                <!--</td>-->
                                                
                                                <td>
                                                    @if ($row->remarks == 'Transfer to Admin')
                                                        <span style="color: {{ $row->status == 'Approve' ? 'green' : ($row->status == 'Reject' ? 'red' : 'orange') }}; cursor: not-allowed;" 
                                                              title="Status change disabled for Transfer to Admin">
                                                            {{ $row->status }}
                                                        </span>
                                                    @else
                                                        @if ($row->status == 'Pending')
                                                            <a href="#" class="status-link" data-toggle="modal" data-target="#modal-change-status"
                                                               data-id="{{ $row->id }}" data-status="{{ $row->status }}"
                                                               style="color: orange;">
                                                                {{ $row->status }}
                                                            </a>
                                                        @else
                                                            <span style="color: {{ $row->status == 'Approve' ? 'green' : 'red' }}; cursor: not-allowed;">
                                                                {{ $row->status }}
                                                            </span>
                                                        @endif
                                                    @endif

                                                </td>
                                                <td>₹ {{ $row->amount }}</td>
                                                <td>{{ $row->remarks }}</td>
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
    <!-- Modal for COD Submission -->
    <div class="modal fade" id="modal-submit-cod">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Submit COD to Branch</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="submitCodForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter amount"
                                min="0" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="remarks">Remarks (Optional)</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="3"
                                placeholder="Enter remarks"></textarea>
                        </div>
                        <div id="error-message" class="text-danger"></div>
                        <div id="success-message" class="text-success"></div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submitCodBtn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal for Changing Status -->
    <div class="modal fade" id="modal-change-status">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Change Status</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="changeStatusForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="Pending">Pending</option>
                                <option value="Approve">Approve</option>
                                <option value="Reject">Reject</option>
                            </select>
                            <input type="hidden" id="record_id" name="record_id">
                        </div>
                        <div id="status-error-message" class="text-danger"></div>
                        <div id="status-success-message" class="text-success"></div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="changeStatusBtn">Update Status</button>
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
    <!-- date-range-picker -->
    <script src="{{ asset('admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let total = document.getElementById("totalAmount")?.value;
            if (total) {
                document.getElementById("amount").innerText = `₹ ${total}`;
            }
        });
    </script>
    <script>
        $(function () {
            // Page specific script 
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        $(document).ready(function () {
            // Date range picker
            $('#reservation').daterangepicker();

            $('#reservation').on('change', function () {
                var date = $('#reservation').val();
                $.ajax({
                    url: "{{ route('delivery.codHistory') }}",
                    type: 'GET',
                    data: {
                        date: date
                    },
                    success: function (response) {
                        $('#bodyData').html(response.html);
                        let total = document.getElementById("totalAmount")?.value;
                        if (total) {
                            document.getElementById("amount").innerText = `₹ ${total}`;
                        }
                    }
                });
            });

            // Submit COD Form
            $('#submitCodForm').on('submit', function (e) {
                e.preventDefault();

                $('#error-message').text('');
                $('#success-message').text('');
                $('#submitCodBtn').attr('disabled', true).text('Submitting...');

                $.ajax({
                    url: "{{ route('delivery.submitCodToAdmin') }}",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.success) {
                            $('#success-message').text(response.message);
                            setTimeout(function () {
                                $('#modal-submit-cod').modal('hide');
                                location.reload(); // Reload the page to update the data
                            }, 2000);
                        } else {
                            $('#error-message').text(response.message);
                            $('#submitCodBtn').attr('disabled', false).text('Submit');
                        }
                    },
                    error: function (xhr) {
                        let errorMessage = 'An error occurred';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        $('#error-message').text(errorMessage);
                        $('#submitCodBtn').attr('disabled', false).text('Submit');
                    }
                });
            });

            // Status Modal
            $('.status-link').on('click', function () {
                var id = $(this).data('id');
                var status = $(this).data('status');
                $('#record_id').val(id);
                $('#status').val(status);
            });

            // Change Status Form
            $('#changeStatusForm').on('submit', function (e) {
                e.preventDefault();

                $('#status-error-message').text('');
                $('#status-success-message').text('');
                $('#changeStatusBtn').attr('disabled', true).text('Updating...');

                $.ajax({
                    url: "/update-cod-status",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.success) {
                            $('#status-success-message').text(response.message);
                            setTimeout(function () {
                                $('#modal-change-status').modal('hide');
                                location.reload(); // Reload the page to update the data
                            }, 2000);
                        } else {
                            $('#status-error-message').text(response.message);
                            $('#changeStatusBtn').attr('disabled', false).text('Update Status');
                        }
                    },
                    error: function (xhr) {
                        let errorMessage = 'An error occurred';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        $('#status-error-message').text(errorMessage);
                        $('#changeStatusBtn').attr('disabled', false).text('Update Status');
                    }
                });
            });
        });
    </script>
@endpush