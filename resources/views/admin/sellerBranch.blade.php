@extends('admin.layout.main')
@section('main')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>
                            {{ !empty($singleData) ? 'Update Seller Branch' : 'Add Seller Branch' }}
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/admin-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">
                                {{ !empty($singleData) ? 'Update Seller Branch' : 'Add Seller Branch' }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
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
                                <h3 class="card-title">{{ !empty($singleData) ? 'Update Branch' : 'Add New Branch' }}</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form id="sellerForm" enctype="multipart/form-data">
                                <input type="hidden" name="id"
                                    value="{{ !empty($singleData) ? $singleData->id : '' }}">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="fullName">Name</label>
                                                <input type="text" class="form-control" id="fullName" name="fullName"
                                                    placeholder="Enter Name"
                                                    value="{{ !empty($singleData) ? $singleData->fullname : '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    placeholder="Enter Email"
                                                    value="{{ !empty($singleData) ? $singleData->email : '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="fullAddress">Full Address</label>
                                                <input type="text" class="form-control" id="fullAddress"
                                                    name="fullAddress" placeholder="Enter Address"
                                                    value="{{ !empty($singleData) ? $singleData->fulladdress : '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="itemsCount">No. of items</label>
                                                <input type="number" class="form-control" id="itemsCount" name="itemsCount"
                                                    placeholder="Enter No. of items"
                                                    value="{{ !empty($singleData) ? $singleData->itemcount : '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="phone">Phone No.</label>
                                                <input type="tel" pattern="[6789][0-9]{9}" class="form-control"
                                                    id="phone" name="phone" placeholder="Enter Phone No."
                                                    value="{{ !empty($singleData) ? $singleData->phoneno : '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>Select Category</label>
                                                <select class="custom-select" id="category" name="category">
                                                    <option selected disabled>Select</option>
                                                    @foreach ($cat as $data)
                                                        <option value="{{ $data->id }}"
                                                            @if (!empty($singleData) && $singleData->category == $data->id) selected @endif>
                                                            {{ $data->cat_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="panNo">GST or Pan no</label>
                                                <input type="text" class="form-control" id="panNo" name="panNo"
                                                    placeholder="Enter GST or Pan No"
                                                    value="{{ !empty($singleData) ? $singleData->gst_panno : '' }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="panImage">GST or Pan card Image</label> <br>
                                                <input type="file" class="form-control" id="panImage" name="panImage">
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="pinCode">Pin code</label>
                                                {{-- <input type="number" class="form-control" id="pinCode" name="pinCode"
                                                    placeholder="Enter Pin code"
                                                    value="{{ !empty($singleData) ? $singleData->pincode : '' }}">
                                                <span></span> --}}
                                                <select class="custom-select" id="pinCode" name="pinCode">
                                                    <option selected disabled>Select</option>
                                                    @foreach ($pinCode as $data)
                                                        <option value="{{ $data->pincodes }}"
                                                            @if (!empty($singleData) && $singleData->pincode == $data->pincodes) selected @endif>
                                                            {{ $data->pincodes }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="branchType">Branch type</label>
                                                <select class="custom-select rounded-0" id="branchType"
                                                    name="branchType">
                                                    <option selected value="Seller">Seller Branch</option>
                                                    {{-- <option value="Seller"
                                                        @if (!empty($singleData) && $singleData->type == 'Seller') selected @endif>Seller</option>
                                                    <option value="Booking">Booking</option>
                                                    <option value="Delivery"
                                                        @if (!empty($singleData) && $singleData->type == 'Delivery') selected @endif>Delivery</option> --}}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="sellerLogo">Seller Logo (if available)</label> <br>
                                                <input type="file" class="form-control" id="sellerLogo"
                                                    name="sellerLogo" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button class="btn btn-primary"
                                        id="addBtn">{{ !empty($singleData) ? 'Update Now' : 'Add Branch' }}</button>
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
    <!-- bs-custom-file-input -->
    <script src="{{ asset('admin/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#branchType').on('change', function() {
                if ($(this).val() === 'Booking') {
                    $('#branchCommission').removeClass('d-none').addClass('d-block');
                    $('#branch_cm').prop('required', true);
                } else {
                    $('#branchCommission').removeClass('d-block').addClass('d-none');
                    $('#branch_cm').prop('required', false);
                }
            });
        });

        $(document).ready(function() {
            // Handle pin code change
            $('#pinCode').on('change', function() {
                var pinCode = $(this).val();
                var setMsg = $(this).next('span');
                setMsg.text("Checking...");
                $.ajax({
                    url: "{{ route('admin.checkPinCode') }}",
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
            $('#sellerForm').on('submit', function(event) {
                event.preventDefault();

                // Front-end validation
                var formData = {
                    fullName: $('#fullName').val(),
                    email: $('#email').val(),
                    fullAddress: $('#fullAddress').val(),
                    itemsCount: $('#itemsCount').val(),
                    phone: $('#phone').val(),
                    category: $('#category').val(),
                    panNo: $('#panNo').val(),
                    // panImage: $('#panImage').prop('files')[0],
                    pinCode: $('#pinCode').val(),
                    branchType: $('#branchType').val(),
                    // sellerLogo: $('#sellerLogo').prop('files')[0]
                };

                var validationRules = [{
                        field: "fullName",
                        message: "Name is required"
                    },
                    {
                        field: "email",
                        message: "Email is required"
                    },
                    {
                        field: "fullAddress",
                        message: "Address is required"
                    },
                    {
                        field: "itemsCount",
                        message: "Items count is required"
                    },
                    {
                        field: "phone",
                        message: "Phone number is required"
                    },
                    {
                        field: "category",
                        message: "Category is required"
                    },
                    {
                        field: "panNo",
                        message: "PAN or GST number is required"
                    },
                    // {
                    //     field: "panImage",
                    //     message: "Pancard Image is required"
                    // },
                    {
                        field: "pinCode",
                        message: "Pincode is required"
                    },
                    {
                        field: "branchType",
                        message: "Choose a Branch type"
                    },
                    // {
                    //     field: "sellerLogo",
                    //     message: "Seller Logo is required"
                    // }
                ];

                for (var i = 0; i < validationRules.length; i++) {
                    var rule = validationRules[i];
                    if (!formData[rule.field] || (typeof formData[rule.field] === 'string' && formData[rule
                            .field].trim() === "")) {
                        Toast("error", rule.message);
                        return;
                    }
                }

                if (formData.pinCode.length !== 6) {
                    Toast("error", "Enter a valid pincode");
                    return;
                }
                var formData = new FormData(this);
                $.ajax({
                    url: "{{ route('admin.addBranch') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    dataType: "json",
                    success: function(response) {
                        $('#sellerForm')[0].reset();
                        if (response.success) {
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
