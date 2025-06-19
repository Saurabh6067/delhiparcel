@extends('booking.layout.main')
@section('main')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add Delivery Boy</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/booking-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Add Delivery Boy</li>
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
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Add new delivery</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form id="deliveryForm">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="fullName">Full Name</label>
                                                <input type="text" class="form-control" id="fullName" name="fullName"
                                                    placeholder="Enter Full Name">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="email">Login-Id / Email</label>
                                                <input type="text" class="form-control" id="email" name="email"
                                                    placeholder="Enter Email">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="fullAddress">Address</label>
                                                <input type="text" class="form-control" id="fullAddress"
                                                    name="fullAddress" placeholder="Enter Address">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="phone">Phone No.</label>
                                                <input type="number" class="form-control" id="phone" name="phone"
                                                    placeholder="Enter Phone No.">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="pinCode">Pin code</label>
                                                <input type="number" class="form-control" id="pinCode" name="pinCode"
                                                    placeholder="Enter Pin code">
                                                <span></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" class="form-control" id="password" name="password"
                                                    placeholder="Enter Password">
                                                <span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary float-right d-none" id="addBtn">Add
                                        Delivery Boy</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // Handle pin code change
            $('#pinCode').on('change', function() {
                var pinCode = $(this).val();
                var setMsg = $(this).next('span');
                setMsg.text("Checking...");
                $.ajax({
                    url: "{{ route('booking.checkPinCode') }}",
                    type: 'POST',
                    data: {
                        pin: pinCode,
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            setMsg.text(response.message);
                            setMsg.css('color', 'green');
                            $("#addBtn").removeClass('d-none');
                        } else {
                            setMsg.text(response.message);
                            setMsg.css('color', 'red');
                            $("#addBtn").addClass('d-none');
                        }
                    },
                    error: function(err) {
                        Toast("error", "An unexpected error occurred. Please try again.");
                    }
                });
            });

            // Handle form submission
            $('#deliveryForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('booking.addNewDeliveryBoy') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#deliveryForm')[0].reset();
                            Toast("success", response.message);
                        } else {
                            Toast("error", response.message);
                        }
                    },
                    error: function(err) {
                        Toast("error",
                            "An unexpected error occurred. Please try again.");
                    }
                });
            });
        });
    </script>
@endpush
