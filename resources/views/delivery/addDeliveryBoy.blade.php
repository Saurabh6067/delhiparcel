@extends('delivery.layout.main')
@push('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <style>
        .select2-container--bootstrap4 .select2-selection--multiple {
            height: auto !important;
        }
    </style>
@endpush
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
                            <li class="breadcrumb-item"><a href="{{ url('/delivery-dashboard') }}">Home</a></li>
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
                                        <div class="col-lg-10">
                                            <div class="form-group">
                                                <label for="fullAddress">Address</label>
                                                <input type="text" class="form-control" id="fullAddress"
                                                    name="fullAddress" placeholder="Enter Address">
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="orderRate">Order Rate</label>
                                                <input type="text" class="form-control" id="orderRate" name="orderRate"
                                                    placeholder="₹ 0.0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="phone">Phone No.</label>
                                                <!--<input type="number" class="form-control" id="phone" name="phone"-->
                                                <!--    placeholder="Enter Phone No.">-->
                                                
                                                <!-- This is for phone validation -->
                                                <input type="text" class="form-control" id="phone" name="phone" 
                                                       placeholder="Enter Phone No." 
                                                       maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10); 
                                                                document.getElementById('phone-error').textContent = 
                                                                this.value.length !== 10 ? 'Phone number must be exactly 10 digits' : '';" 
                                                       required aria-describedby="phone-error">
                                                <span id="phone-error" style="color: red; font-size: 1.1em;"></span>
       
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            {{-- <div class="form-group">
                                                <label for="pinCode">Pin code</label>
                                                <input type="number" class="form-control" id="pinCode" name="pinCode"
                                                    placeholder="Enter Pin code">
                                            </div> --}}

                                            <div class="form-group">
                                                <label for="pinCode">Pin Code</label>
                                                <select class="select2bs4 select2-hidden-accessible" multiple=""
                                                    data-placeholder="Select a Pin code" style="width: 100%;"
                                                    data-select2-id="23" tabindex="-1" aria-hidden="true" name="pinCode[]"
                                                    id="pinCode">
                                                    @php
                                                        $pinCode = explode(',', $delivery->pincode);
                                                    @endphp
                                                    @foreach ($pinCode as $data)
                                                        <option value="{{ $data }}"
                                                            @if ($data)  @endif>
                                                            {{ $data }}
                                                        </option>
                                                    @endforeach
                                                </select>
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
                                    <button type="submit" class="btn btn-primary float-right" id="addBtn">Add
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
    <!-- Select2 -->
    <script src="{{ asset('admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- bs-custom-file-input -->
    <script src="{{ asset('admin/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

        });


        $(document).ready(function() {
            // Handle form submission
            $('#deliveryForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('delivery.addNewDeliveryBoy') }}",
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
