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
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Total Orders</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/delivery-boy-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item">Total Orders</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h3 class="card-title">All Orders</h3>
                                    </div>
                                    <div class="col-lg-6 text-lg-right">
                                        <button class="btn btn-sm btn-warning font-weight-bold btn-order-stats d-none"
                                            id="transfer_order">Update Status</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>
                                                <label for="order_stats">Transfer Status</label>
                                                <input type="checkbox" id="order_stats"
                                                    onclick="toggleCheckboxes(this, 'row-checkbox1', 'btn-order-stats')">
                                            </th>
                                            <th>Order Id</th>
                                            <th>Order Date</th>
                                            <th>Sender Name</th>
                                            <th>Receiver Name</th>
                                            <th>Amount</th>
                                            <th>Payment Status</th>
                                            <th>Pickup PinCode</th>
                                            <th>Delivery PinCode</th>
                                            <th>Order Type</th>
                                            <th>Order Status</th>
                                            <th>Assign Order</th>
                                            <th>Transfer</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                        @include('deliveryBoy.inc.otherBranchOrder')
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
        function toggleCheckboxes(source, className, buttonClass) {
            document.querySelectorAll('.' + className).forEach(cb => cb.checked = source.checked);
            toggleButton(className, buttonClass);
        }

        function toggleButton(className, buttonClass) {
            document.querySelector('.' + buttonClass).classList.toggle('d-none',
                !document.querySelector('.' + className + ':checked'));
        }

        document.addEventListener("change", e => {
            if (e.target.classList.contains('row-checkbox1')) {
                toggleButton('row-checkbox1', 'btn-order-stats');
            } else if (e.target.classList.contains('row-checkbox2')) {
                toggleButton('row-checkbox2', 'btn-transfer');
            }
        });
    </script>

    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "excel", "pdf", "print"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $(document).on("click", ".transfer_order", function() {
                let selectedOrders = $(this).data('id');
                $('#assignOrderData').modal('show');
                $('#orderId').val(selectedOrders);
            });

            $(document).on("click", "#transfer_order", function() {
                let selectedOrders = $(".row-checkbox1:checked").map(function() {
                    return $(this).data("id");
                }).get();

                console.log(selectedOrders);
                $('#assignOrderData').modal('show');
                $('#orderId').val(selectedOrders);
            });

            $(document).on("submit", "#assignOrderForm", function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('delivery.boy.transfer.order.status') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function(response) {
                        $('#assignOrderForm')[0].reset();
                        if (response.success) {
                            Toast("success", response.message);
                            $('#assignOrderData').modal('hide');
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            Toast("error", response.message);
                        }
                    }
                });
            });
        });
    </script>
@endpush
@push('modals')
    <div class="modal fade" id="assignOrderData" tabindex="-1" role="dialog" aria-labelledby="assignOrderLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignOrderLabel">Update Order Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="assignOrderForm">
                        <input type="hidden" id="orderId" name="orderId" value="">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="order_status">Select Status</label>
                                <select class="custom-select rounded-0" id="order_status" name="order_status">
                                    <option value="Pending">Pending</option>
                                    <option value="Processing">Processing</option>
                                    <option value="Delivered">Delivered</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" id="assignOrderBtn">Update Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush
