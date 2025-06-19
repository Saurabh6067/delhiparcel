@extends('admin.layout.main')
@push('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- DateRangePicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
@section('main')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $branch_name ?? ''}} - COD Settlement</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/seller-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">COD Settlement</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <input type="hidden" value="{{ $seller_id ?? '' }}" id="seller_id"/>
                                <h3 class="card-title font-weight-bold">COD Settlement Amount</h3>
                                <div class="float-right">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                        data-target="#exampleModal">
                                        ₹ {{ $amount->total ?? '0.0' }}
                                        <span class="badge badge-light"><i class="fas fa-solid fa-plus"></i></span>
                                    </button>
                                    <input type="text" class="form-control-sm" id="dateRangePicker" style="width: 220px; display: inline-block;" placeholder="Select Date Range">
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sr No</th>
                                            <th>Date Time</th>
                                            <th>Credit Amount</th>
                                            <th>Debit Amount</th>
                                            <th>Total</th>
                                            <th>Narration</th>
                                            <th>Status</th>
                                            <th>Ref.No.</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodyData">
                                        @php
                                            $sr = 1;
                                        @endphp
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>{{ $sr++ }}</td>
                                                <td>{{ $item->datetime }}</td>
                                                <td>{{ $item->c_amount ?? '-' }}</td>
                                                <td>{{ $item->d_amount ?? '-' }}</td>
                                                <td>{{ $item->total }}</td>
                                                <td class="text-uppercase">
                                                    @if (!empty($item->adminid))
                                                        {{ $item->users->type . '/' . $item->msg }}
                                                    @else
                                                        @if ($item->msg == 'credit')
                                                            Credit
                                                        @elseif ($item->msg == 'debit')
                                                            Debit
                                                        @else
                                                            {{ $item->msg }}
                                                        @endif
                                                    @endif
                                                </td>
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
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" style="text-align:right">Total Credit:</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
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
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Debit Amount</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="walletAdd">
                        @csrf
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <input type="text" class="form-control" placeholder="0.0" aria-label="Amount"
                                    name="amount" required>
                            </div>
                            <div class="form-group col-sm-6">
                                <input type="text" class="form-control" placeholder="Ref No" aria-label="Ref No"
                                    name="refno" required>
                            </div>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-success">Pay Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
    <!-- DateRangePicker -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
    <!-- Toast Notifications -->
    <script>
        function Toast(type, message) {
            alert(type + ': ' + message); // Replace with your actual Toast implementation (e.g., Toastr)
        }
    </script>
    <!-- Page specific script -->
    <script>
        $(function() {
            // Initialize DateRangePicker
            $('#dateRangePicker').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'YYYY-MM-DD'
                }
            });

            // Initialize DataTable
            var table = $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "excel", "pdf", "print"],
                "footerCallback": function(row, data, start, end, display) {
                    var api = this.api();
                    // Calculate total credit amount for displayed data
                    var totalCredit = api
                        .column(2, { page: 'current' })
                        .data()
                        .reduce(function(a, b) {
                            return a + (parseFloat(b !== '-' ? b : 0));
                        }, 0);
                    // Update footer
                    $(api.column(2).footer()).html('₹' + totalCredit.toFixed(2));
                }
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            // Handle date range selection
            $('#dateRangePicker').on('apply.daterangepicker', function(ev, picker) {
                var startDate = picker.startDate.format('YYYY-MM-DD');
                var endDate = picker.endDate.format('YYYY-MM-DD');
                $(this).val(startDate + ' - ' + endDate);
                console.log('Start Date:', startDate, 'End Date:', endDate);
                fetchFilteredData(startDate, endDate);
            });

            // Handle clearing the date range
            $('#dateRangePicker').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                console.log('Date range cleared');
                fetchFilteredData('', '');
            });

            // Function to fetch filtered data
            // function fetchFilteredData(startDate, endDate) {
            //     var sellerId = $("#seller_id").val();
            //     console.log('Seller ID:', sellerId);
            //     $.ajax({
            //         url: "{{ route('admin-branch-COD-History', ['id' => ':seller_id']) }}".replace(':seller_id', sellerId),
            //         type: 'GET',
            //         data: {
            //             start_date: startDate,
            //             end_date: endDate
            //         },
            //         dataType: 'json',
            //         success: function(response) {
            //             console.log('AJAX Response:', response);
            //             // Clear existing table data
            //             table.clear();
            //             // Add new data
            //             if (response.data && response.data.length > 0) {
            //                 response.data.forEach(function(item, index) {
            //                     table.row.add([
            //                         index + 1,
            //                         item.datetime,
            //                         item.c_amount ?? '-',
            //                         item.d_amount ?? '-',
            //                         item.total,
            //                         item.narration || (item.msg === 'credit' ? 'Credit' : item.msg === 'debit' ? 'Debit' : item.msg),
            //                         '<span class="font-weight-bold text-' + 
            //                             (item.status === 'success' ? 'success' : item.status === 'pending' ? 'warning' : 'danger') + 
            //                             '">' + item.status + '</span>',
            //                         item.refno ?? '-'
            //                     ]);
            //                 });
            //             }
            //             // Redraw table
            //             table.draw();
            //             // Update total amount
            //             $('.btn-primary .badge').prev().text('₹' + (response.amount.total ?? '0.0'));
            //         },
            //         error: function(err) {
            //             console.error('AJAX Error:', err);
            //             let errorMessage = 'Failed to fetch data!';
            //             if (err.responseJSON && err.responseJSON.error) {
            //                 errorMessage = err.responseJSON.error;
            //             } else if (err.status === 404) {
            //                 errorMessage = 'Endpoint not found. Check the route configuration.';
            //             } else if (err.status === 500) {
            //                 errorMessage = 'Server error. Check the server logs for details.';
            //             }
            //             Toast("error", errorMessage);
            //         }
            //     });
            // }

            function fetchFilteredData(startDate, endDate) {
                var sellerId = $("#seller_id").val();
                console.log('Seller ID:', sellerId);
                $.ajax({
                    url: "{{ url('/admin-branch-COD-History-datefilter') }}",
                    type: 'GET',
                    data: {
                        seller_id: sellerId,
                        start_date: startDate,
                        end_date: endDate
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('AJAX Response:', response);
                        
                        // Destroy existing DataTable instance if it exists
                        if ($.fn.DataTable.isDataTable('#example1')) {
                            $('#example1').DataTable().destroy();
                        }
                        
                        // Clear the tbody
                        $('#bodyData').empty();
                        
                        // Add new rows to tbody
                        var tbodyContent = '';
                        if (response.data && response.data.length > 0) {
                            response.data.forEach(function(item, index) {
                                var statusClass = item.status === 'success' ? 'text-success' : 
                                                item.status === 'pending' ? 'text-warning' : 'text-danger';
                                
                                tbodyContent += `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${item.datetime}</td>
                                        <td>${item.c_amount ?? '-'}</td>
                                        <td>${item.d_amount ?? '-'}</td>
                                        <td>${item.total}</td>
                                        <td class="text-uppercase">${item.msg}</td>
                                        <td><span class="font-weight-bold ${statusClass}">${item.status}</span></td>
                                        <td>${item.refno ?? '-'}</td>
                                    </tr>
                                `;
                            });
                        } else {
                            tbodyContent = '<tr><td colspan="8" class="text-center">No data found</td></tr>';
                        }
                        
                        // Set the tbody content
                        $('#bodyData').html(tbodyContent);
                        
                        // Reinitialize DataTable with the new data
                        $('#example1').DataTable({
                            "responsive": true,
                            "autoWidth": false,
                            "paging": true,
                            "searching": true,
                            "ordering": true,
                            "info": true,
                            "dom": 'Bfrtip',
                            "buttons": [
                                'copy', 'excel', 'pdf', 'print'
                            ],
                            "pageLength": 10
                        });
                        
                        // Update total amount in button
                        $('.btn-primary .badge').prev().text('₹' + (response.amount.total ?? '0.0'));
                        
                        // Update total credit in footer
                        var totalCredit = response.data ? response.data.reduce((sum, item) => sum + (parseFloat(item.c_amount) || 0), 0).toFixed(2) : '0.00';
                        $('#example1 tfoot th:eq(1)').text('₹' + totalCredit);
                    },
                    error: function(err) {
                        console.error('AJAX Error:', err);
                        let errorMessage = 'Failed to fetch data!';
                        if (err.responseJSON && err.responseJSON.error) {
                            errorMessage = err.responseJSON.error;
                        } else if (err.status === 404) {
                            errorMessage = 'Endpoint not found. Check the route configuration.';
                        } else if (err.status === 500) {
                            errorMessage = 'Server error. Check the server logs for details.';
                        }
                        Toast("error", errorMessage);
                    }
                });
            }




            // Wallet add form submission
            $("#walletAdd").on("submit", function(e) {
                e.preventDefault();
                var sellerId = $("#seller_id").val();
                let formData = $(this).serialize();
                formData += '&seller_id=' + sellerId;

                $.ajax({
                    url: "{{ route('admin.deductBranchCod') }}",
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        $('#walletAdd')[0].reset();
                        if (response.success) {
                            Toast("success", response.message);
                            $('#exampleModal').modal('hide');
                            fetchFilteredData('', ''); // Refresh table data
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        } else {
                            Toast("error", response.message);
                        }
                    },
                    error: function(err) {
                        console.error('Wallet Add Error:', err);
                        let errorMessage = 'Failed to add amount!';
                        if (err.responseJSON && err.responseJSON.error) {
                            errorMessage = err.responseJSON.error;
                        }
                        Toast("error", errorMessage);
                        $('#exampleModal').modal('hide');
                        fetchFilteredData('', ''); // Refresh table data
                    }
                });
            });
        });
    </script>
@endpush