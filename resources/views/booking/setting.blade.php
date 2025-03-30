@extends('booking.layout.main')
@section('main')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Booking Setting</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/booking-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Booking Setting</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <!-- Profile Image -->
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <img class="profile-user-img img-fluid img-circle"
                                        src="{{ asset($data->type_logo) ?? asset('admin/dist/img/avatar5.png') }}"
                                        alt="User profile picture">
                                </div>
                                <h3 class="profile-username text-center">{{ $data->fullname }}</h3>
                                <p class="text-muted text-center"><b>Email:</b> {{ $data->email }}</p>
                                <p class="text-muted text-center"><b>Phone No:</b> {{ $data->phoneno }}</p>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active" href="#activity"
                                            data-toggle="tab">Update Profile</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Change
                                            Password</a></li>
                                </ul>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="active tab-pane" id="activity">
                                        <form class="form-horizontal" id="updateProfile">
                                            <input type="text" name="adminId" value="{{ $data->id }}" hidden>
                                            <div class="form-group row">
                                                <label for="adminName" class="col-sm-2 col-form-label">Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="adminName"
                                                        name="adminName" placeholder="Full Name"
                                                        value="{{ $data->fullname }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="adminEmail" class="col-sm-2 col-form-label">Email</label>
                                                <div class="col-sm-10">
                                                    <input type="email" class="form-control" id="adminEmail"
                                                        name="adminEmail" placeholder="Email Id" required
                                                        value="{{ $data->email }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="adminMobile" class="col-sm-2 col-form-label">Mobile</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="adminMobile"
                                                        name="adminMobile" placeholder="Phone no" required
                                                        value="{{ $data->phoneno }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="offset-sm-2 col-sm-10">
                                                    <button class="btn btn-primary" id="updateProfile">Update
                                                        Profile</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.tab-pane -->

                                    <div class="tab-pane" id="settings">
                                        <form class="form-horizontal" id="passwordChange">
                                            <input type="hidden" name="id" value="{{ $data->id }}">
                                            <div class="form-group row">
                                                <label for="oldPassword" class="col-sm-2 col-form-label">Old
                                                    Password</label>
                                                <div class="col-sm-10">
                                                    <input type="password" class="form-control" id="oldPassword"
                                                        name="oldPassword" placeholder="Old Password">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="newPassword" class="col-sm-2 col-form-label">New
                                                    Password</label>
                                                <div class="col-sm-10">
                                                    <input type="password" class="form-control" id="newPassword"
                                                        name="newPassword" placeholder="New Password">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="conPassword" class="col-sm-2 col-form-label">Confirm
                                                    Password</label>
                                                <div class="col-sm-10">
                                                    <input type="password" class="form-control" id="conPassword"
                                                        name="conPassword" placeholder="Confirm Password">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="offset-sm-2 col-sm-10">
                                                    <button type="submit" class="btn btn-danger"
                                                        id="changePassword">Change Password</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div><!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $("#updateProfile").on("submit", function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('booking.update.profile') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Toast("success", response.message);
                            location.reload();
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
            $("#passwordChange").on("submit", function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: "{{ route('booking.passwordChange') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        $('#passwordChange')[0].reset();
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
