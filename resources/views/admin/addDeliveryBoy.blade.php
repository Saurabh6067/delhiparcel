@extends('admin.layout.main')
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
                            <li class="breadcrumb-item"><a href="{{ url('/admin-dashboard') }}">Home</a></li>
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
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="userid" id="optionId">Select Branch</label>
                                                <!--<select class="custom-select rounded-0" id="userid" name="userid">-->
                                                <!--    <option selected disabled>Select</option>-->
                                                <!--    @foreach ($branch as $item)-->
                                                <!--        <option value="{{ $item->id }}">{{ $item->fullname }}</option>-->
                                                <!--    @endforeach-->
                                                <!--</select>-->
                                                
                                                <select class="custom-select rounded-0" id="userid" name="userid">
                                                    <option selected disabled>Select</option>
                                                    @foreach ($branch as $item)
                                                        <option value="{{ $item->id }}" {{ isset($data) && $data->userid == $item->id ? 'selected' : '' }}>{{ $item->fullname }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="fullName">Full Name</label>
                                                <input type="text" class="form-control" id="fullName" name="fullName"
                                                    placeholder="Enter Full Name" value="{{ $data->name ?? '' }}">
                                                <input type="hidden" name="id" value="{{ $data->id ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="email">Login-Id / Email</label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    placeholder="Enter Email" value="{{ $data->email ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-10">
                                            <div class="form-group">
                                                <label for="fullAddress">Address</label>
                                                <input type="text" class="form-control" id="fullAddress"
                                                    name="fullAddress" placeholder="Enter Address"
                                                    value="{{ $data->address ?? '' }}">
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="orderRate">Order Rate</label>
                                                <!--<input type="text" class="form-control" id="orderRate" name="orderRate"-->
                                                <!--    placeholder="₹ 0.0" {{ $data->orderRate ?? '' }}>-->
                                                <input type="text" class="form-control" id="orderRate" name="orderRate" 
                                                    placeholder="₹ 0.0" value="{{ $data->orderRate ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="phone">Phone No.</label>
                                                <input type="tel" pattern="[6789][0-9]{9}" class="form-control"
                                                    id="phone" name="phone" placeholder="Enter Phone No."
                                                    value="{{ $data->phone ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="text" class="form-control" id="password" name="password"
                                                    placeholder="Enter Password" value="{{ $data->password ?? '' }}">
                                                <span></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="pinCode">Pin Code</label>
                                                
                                                <select class="select2bs4 select2-hidden-accessible" multiple=""
                                                    data-placeholder="Select a Pin code" style="width: 100%;"
                                                    data-select2-id="23" tabindex="-1" aria-hidden="true" name="pinCode[]"
                                                    id="pinCode">
                                                    @foreach ($delivery as $item)
                                                        <option value="{{ $item->pincodes }}"
                                                            @if (!empty($data) && in_array($item->pincodes, explode(',', $data->pincode))) selected @endif>
                                                            {{ $item->pincodes }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                
                                            </div>
                                        </div>
                                        <!--<div class="col-lg-4">-->
                                        <!--    <div class="form-group">-->
                                        <!--        <label for="password">Password</label>-->
                                        <!--        <input type="text" class="form-control" id="password" name="password"-->
                                        <!--            placeholder="Enter Password" value="{{ $data->orderRate ?? '' }}">-->
                                        <!--        <span></span>-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary float-right" id="addBtn">Delivery
                                        Boy</button>
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
            // Handle pin code change
            // $('#pinCode').on('change', function() {
            //     var pinCode = $(this).val();
            //     var setMsg = $(this).next('span');
            //     setMsg.text("Checking...");
            //     $.ajax({
            //         url: "{{ route('admin.checkPinCode') }}",
            //         type: 'POST',
            //         data: {
            //             pin: pinCode,
            //         },
            //         dataType: 'json',
            //         success: function(response) {
            //             if (response.success) {
            //                 setMsg.text(response.message);
            //                 setMsg.css('color', 'green');
            //             } else {
            //                 setMsg.text(response.message);
            //                 setMsg.css('color', 'red');
            //             }
            //         },
            //         error: function(err) {
            //             Toast("error", "An unexpected error occurred. Please try again.");
            //         }
            //     });
            // });

            // Handle form submission
            $('#deliveryForm').on('submit', function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.addNewDeliveryBoy') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#deliveryForm')[0].reset();
                            if (response.data && response.data.password) {
                                $('#password').val(response.data.password);
                            }
                            Toast("success", response.message);
                            window.location.reload();
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
