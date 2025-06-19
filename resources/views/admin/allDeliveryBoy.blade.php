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
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>All Delivery Boy</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/seller-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">All Delivery Boy</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>COD Amount</th>
                                            <th>Name</th>
                                            <th>Branch Name</th>
                                            <th>Login-Id/Email</th>
                                            <th>Phone No</th>
                                            <th>Pin Code</th>
                                            <th>Address</th>
                                            <th>Password</th>
                                            <th>Order Rate</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $sr = 1;
                                        @endphp
                                        @foreach ($data as $item)
                                            <tr>
                                                <td>
                                                    {{ $sr++ }}
                                                </td>
                                                <td>₹ {{ $item->deliverywallet->amount ?? '0.00' }}</td>
                                                <td>
                                                    {{ $item->name }}
                                                    <a class="page-link" href="{{ url('/deliveryboy_earning/' . $item->id) }}" title="Delivery Boy Earning" style="display: inline-block; padding: 4px;">
                                                        <i class="fas fa-layer-group" style="font-size: 14px;"></i>
                                                    </a>
                                                </td>

                                                <td>
                                                    @php
                                                        // Split the pincode string into an array
                                                        $pincodes = explode(',', $item->pincode);
                                                        
                                                        // Initialize branch as null
                                                        $branch = null;
                                                        
                                                        // Loop through each pincode and check for a match in branchs table
                                                        foreach ($pincodes as $pin) {
                                                            $branch = DB::table('branchs')
                                                                ->where('pincode', 'LIKE', '%' . trim($pin) . '%')
                                                                ->first();
                                                            if ($branch) break; // Exit loop if a match is found
                                                        }
                                                    @endphp
                                                    {{ $branch ? $branch->fullname : 'No Branch Found' }}
                                                </td>
                                                <td>{{ $item->email }}</td>
                                                <td>{{ $item->phone }}</td>
                                                <td>{{ $item->pincode }}</td>
                                                <td>{{ $item->address }}</td>
                                                <td>{{ $item->password }}</td>
                                                <td>{{ !empty($item->orderRate) ? $item->orderRate . ' ₹' : '0.0' . ' ₹' }}
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div
                                                            class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                            <input type="checkbox"
                                                                class="custom-control-input status-toggle"
                                                                id="status{{ $item->id }}"
                                                                @if ($item->status == 'active') checked @endif
                                                                data-id="{{ $item->id }}">
                                                            <label class="custom-control-label"
                                                                for="status{{ $item->id }}"></label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger delete-btn"
                                                        data-id="{{ $item->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    <a href="{{ url('/admin-add-DeliveryBoy/' . $item->id) }}"
                                                        class="btn btn-sm btn-dark" data-id="{{ $item->id }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
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
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
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
        $(document).on("click", ".delete-btn", function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this Delivery Boy data!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })

                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: "POST",
                            url: "{{ route('admin.delete.dlyBoy') }}",
                            data: {
                                id: id
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.success) {
                                    swal("Poof! Your Delivery Boy data has been deleted!", {
                                        icon: "success",
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    swal("Error!", response.message || "Unable to delete the data.",
                                        "error");
                                }
                            }
                        });
                    } else {
                        swal("Your Delivery Boy data is safe!");
                    }
                });
        });
        $('.status-toggle').on('change', function() {
            var serviceId = $(this).data('id');
            var newStatus = $(this).is(':checked') ? 'active' : 'inactive';
            $.ajax({
                url: "{{ route('admin.update.boySt') }}",
                type: 'POST',
                data: {
                    id: serviceId,
                    status: newStatus,
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Toast("success", response.message);
                    } else {
                        Toast("error", response.message);
                    }
                },
                error: function() {
                    Toast('error', 'An error occurred. Please try again.');
                }
            });

        });
    </script>
@endpush
