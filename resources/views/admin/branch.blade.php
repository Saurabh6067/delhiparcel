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
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>
                            {{ !empty($singleData) ? 'Update Branch' : 'Add Branch' }}
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/admin-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">{{ !empty($singleData) ? 'Update Branch' : 'Add Branch' }}
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
                                                <label for="pinCode">Pin Code ss</label>
                                                <select class="select2 custom-select" style="width: 100%;"
                                                    name="pinCode[]" id="pinCode" 
                                                    {{ !empty($singleData) && $singleData->type == 'Delivery' ? 'multiple' : '' }}>
                                                    @foreach ($pinCode as $data)
                                                        <option value="{{ $data->pincodes }}"
                                                            @if (!empty($singleData) && in_array($data->pincodes, explode(',', $singleData->pincode))) selected @endif>
                                                            {{ $data->pincodes }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                
                                                <!-- Code remove here picode disabled condition -->
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="branchType">Select Branch for</label>
                                                <select class="custom-select rounded-0" id="branchType"
                                                    name="branchType">
                                                    <option selected disabled>Branch type</option>
                                                    {{-- <option value="Seller"
                                                        @if (!empty($singleData) && $singleData->type == 'Seller') selected @endif>Seller</option> --}}
                                                    <option
                                                        value="Booking"@if (!empty($singleData) && $singleData->type == 'Booking') selected @endif>
                                                        Booking</option>
                                                    <option value="Delivery"
                                                        @if (!empty($singleData) && $singleData->type == 'Delivery') selected @endif>Delivery</option>
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
                                        {{-- <div class="col-lg-4 d-none" id="branchCommission">
                                            <div class="form-group">
                                                <label for="branch_cm">Branch Commission in %</label>
                                                <input type="text" class="form-control" id="branch_cm"
                                                    name="branch_cm" placeholder="Enter Commission in %">
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    @if (!empty($singleData))
                                        <button class="btn btn-primary" id="addBtn">{{ 'Update Now' }}</button>
                                    @else
                                        <button class="btn btn-primary" id="addBtn">{{ 'Add Branch' }}</button>
                                    @endif
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
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            // Initialize BS Custom File Input
            bsCustomFileInput.init();
        });

        $(document).ready(function() {
            // Set initial state based on current branch type
            updatePincodeSelectState($('#branchType').val());
            
            // Handle branch type change
            $('#branchType').on('change', function() {
                updatePincodeSelectState($(this).val());
                
                if ($(this).val() === 'Booking') {
                    $('#branchCommission').removeClass('d-none').addClass('d-block');
                    $('#branch_cm').prop('required', true);
                } else {
                    $('#branchCommission').removeClass('d-block').addClass('d-none');
                    $('#branch_cm').prop('required', false);
                }
            });
            
            function updatePincodeSelectState(branchType) {
                if (branchType === 'Delivery') {
                    // Allow multiple selection for Delivery
                    $('#pinCode').prop('multiple', true);
                    $('#pinCode').select2({
                        theme: 'bootstrap4',
                        placeholder: 'Select Multiple Pincodes',
                        width: '100%'
                    });
                } else {
                    // Single selection for Booking
                    $('#pinCode').prop('multiple', false);
                    $('#pinCode').select2({
                        theme: 'bootstrap4',
                        placeholder: 'Select Pincode',
                        width: '100%'
                    });
                    
                    // If any option is selected, keep only the first one
                    var selected = $('#pinCode').val();
                    if (Array.isArray(selected) && selected.length > 1) {
                        $('#pinCode').val(selected[0]).trigger('change');
                    }
                }
            }

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
                    branchType: $('#branchType').val(),
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
                    {
                        field: "branchType",
                        message: "Choose a Branch type"
                    }
                ];

                for (var i = 0; i < validationRules.length; i++) {
                    var rule = validationRules[i];
                    if (!formData[rule.field] || (typeof formData[rule.field] === 'string' &&
                            formData[rule.field].trim() === "")) {
                        Toast("error", rule.message);
                        return;
                    }
                }

                // Check if any pincode is selected
                if ($('#pinCode').val() === null || $('#pinCode').val().length === 0) {
                    Toast("error", "Please select at least one pincode");
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
                        if (response.success) {
                            Toast("success", response.message);
                            // For update operation, don't reset the form
                            if (!$('input[name="id"]').val()) {
                                $('#sellerForm')[0].reset();
                                $('#pinCode').val(null).trigger('change');
                            }
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