@extends('admin.layout.main')
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
                        <h1>Admin COD History</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/delivery-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item">Admin COD History</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- Branch Balance Card -->


                    <!-- Total Submitted to Admin Card -->
                    <div class="col-md-12">
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">Total Cod Received</h3>
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
                                        <h3 class="card-title text-center">Admin COD Submission History</h3>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="float-right">
                                            <form id="adminCodAmount">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">
                                                            <i class="far fa-calendar-alt"></i>
                                                        </span>
                                                    </div>
                                                    <input type="date" class="form-control form-control-sm" name="daterange"
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
                                            <th>Branch</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Remarks</th>
                                            <th>Amount</th>
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
                                                        $branch = App\Models\Branch::find($row->branch_id);
                                                    @endphp
                                                    {{ $branch->fullname ?? 'N/A' }}
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
                                                <td>
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
                                                </td>
                                                <td>{{ $row->remarks }}</td>
                                                <td>₹ {{ $row->amount }}</td>
                                            </tr>
                                        @endforeach
                                        <input type="hidden" value="{{ $totalAmount }}" id="totalAmount">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
                            <!--<input type="text" id="branch_cod_history_id" name="branch_cod_history_id">-->
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
    <!-- jQuery 3.6.4 -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
            integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8="
            crossorigin="anonymous"></script>
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
    <!-- date-range-picker -->
    <script src="{{ asset('admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let totalElement = document.getElementById("totalAmount");
            if (totalElement) {
                let total = totalElement.value;
                document.getElementById("amount").innerText = `₹ ${total}`;
            } else {
                console.error("Element with ID 'totalAmount' not found");
            }
        });
    </script>
    <script>
        $(function () {
            // Initialize DataTable
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        $(document).ready(function () {
            // Test alert to confirm jQuery is working
            // Initialize daterangepicker (adjusted for single date or range)
            $('#reservation').daterangepicker({
                singleDatePicker: true, // Use single date picker since input is type="date"
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            // Handle date change
            $('#reservation').on('change', function () {
                var date = $('#reservation').val();
                $.ajax({
                    url: "{{ route('delivery.adminCodHistory') }}",
                    type: 'GET',
                    data: {
                        date: date
                    },
                    success: function (response) {
                        $('#bodyData').html(response.html);
                        let totalElement = document.getElementById("totalAmount");
                        if (totalElement) {
                            let total = totalElement.value;
                            document.getElementById("amount").innerText = `₹ ${total}`;
                        } else {
                            console.error("Element with ID 'totalAmount' not found");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX error:", status, error);
                    }
                });
            });
            // this is for all cod history manage by delivery panel 



            // Status Modal
            $('.status-link').on('click', function () {
                var id = $(this).data('id');
                // var branch_cod_history_id = $(this).data('branch_cod_history_id');
               
                var status = $(this).data('status');
                $('#record_id').val(id);
                // $('#branch_cod_history_id').val(branch_cod_history_id);
                $('#status').val(status);
            });

            // Change Status Form
            $('#changeStatusForm').on('submit', function (e) {
                e.preventDefault();

                $('#status-error-message').text('');
                $('#status-success-message').text('');
                $('#changeStatusBtn').attr('disabled', true).text('Updating...');

                $.ajax({
                    url: "/admin-update-cod-status",
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.success) {
                            $('#status-success-message').text(response.message);
                           window.location.reload();
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

            // end here 



        }); // Fixed missing closing parenthesis
    </script>
@endpush