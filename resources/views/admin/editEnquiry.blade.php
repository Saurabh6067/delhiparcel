@extends('admin.layout.main')
@section('main')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Update Enquiry</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/admin-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Update Enquiry</li>
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
                            <!-- form start -->
                            <form id="deliveryForm">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="fullName">Full Name</label>
                                                <input type="text" class="form-control" id="fullName" name="fullName"
                                                    placeholder="Enter Full Name" value="{{ $enquiry->fullname }}">
                                                <input type="hidden" name="id" value="{{ $enquiry->id }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    placeholder="Enter Email" value="{{ $enquiry->email }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="phone">Phone Number</label>
                                                <input type="number" class="form-control" id="phone" name="phone"
                                                    placeholder="Enter phone" value="{{ $enquiry->phoneno }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="itemsCount">No.Of Items</label>
                                                <input type="number" class="form-control" id="itemsCount" name="itemsCount"
                                                    placeholder="Enter No.Of Items" value="{{ $enquiry->itemno }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="category" class="form-label">Select Categories</label>
                                                <select class="form-control w-100" id="category" name="category" required>
                                                    <option selected disabled>Select</option>
                                                    @foreach ($category as $item)
                                                        <option value="{{ $item->id }}">{{ $item->cat_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="pinCode" class="form-label">Pin Code</label>
                                                <input type="text" maxlength="6" name="pinCode"
                                                    class="form-control contact-form-input w-100" id="pinCode"
                                                    name="pinCode" placeholder="Enter your pin code"
                                                    value="{{ $enquiry->pinCode }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="panImage" class="form-label">Gst/Pan Card Image</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input" id="panImage"
                                                            name="panImage">
                                                        <label class="custom-file-label" for="panImage">Choose file</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="panNo" class="form-label">Gst/Pan Card No.</label>
                                                <input type="text" class="form-control contact-form-input w-100"
                                                    id="panNo" name="panNo" placeholder="Enter your gst/pan number"
                                                    value="{{ $enquiry->gst_panno }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="fullAddress">Address</label>
                                                <input type="text" class="form-control" id="fullAddress"
                                                    name="fullAddress" placeholder="Enter full Address"
                                                    value="{{ $enquiry->fulladdress }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary float-right"
                                        id="addBtn">Save</button>
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
            $('#deliveryForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "{{ route('admin.edit.enquiry') }}",
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success == true) {
                            Toast("success", response.message);
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);
                        } else {
                            Toast("error", response.message);
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            });
        });
    </script>
@endpush
