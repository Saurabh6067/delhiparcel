@extends('admin.layout.main')
@section('main')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Total COD History</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ url('/admin-dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item active">Total COD History</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-light">
                                    <div class="card-header">
                                        <h3 class="card-title">Today COD Amount</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-secondary"><i
                                                            class="fas fa-solid fa-money-bill"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text"><strong>Today COD</strong></span>
                                                        <span class="info-box-number">{{ $todayCod ?? 0.0 }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-success"><i
                                                            class="fas fa-solid fa-money-bill"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text"><strong>Submit COD</strong></span>
                                                        <span class="info-box-number">{{ $todaySubmit ?? 0.0 }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-danger"><i
                                                            class="fas fa-solid fa-money-bill"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text"><strong>Pending COD</strong></span>
                                                        <span class="info-box-number">{{ $todayPending ?? 0.0 }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card card-light">
                                    <div class="card-header">
                                        <h3 class="card-title">Total COD Amount</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-secondary"><i
                                                            class="fas fa-solid fa-money-bill"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text"><strong>Total COD</strong></span>
                                                        <span class="info-box-number">{{ $totalCod ?? 0.0 }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-success"><i
                                                            class="fas fa-solid fa-money-bill"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text"><strong>Submit COD</strong></span>
                                                        <span class="info-box-number">{{ $totalSubmit ?? 0.0 }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6 col-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-danger"><i
                                                            class="fas fa-solid fa-money-bill"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text"><strong>Pending COD</strong></span>
                                                        <span class="info-box-number">{{ $totalPending ?? 0.0 }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card card-light">
                            <div class="card-header">
                                <form id="codAmount">
                                    <div class="input-group input-group-sm">
                                        <input type="hidden" name="delivery_boy" name="delivery_boy_id"
                                            id="delivery_boy_id" value="{{ request()->segment(2) }}">
                                        <input type="number" class="form-control" name="amount" id="amount"
                                            placeholder="0.0">
                                        <span class="input-group-append">
                                            <button class="btn btn-info btn-flat">Submit</button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Amount</th>
                                            <th>Date Time</th>
                                            <th>Branch</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodyData">
                                        @include('admin.inc.cod-history')
                                    </tbody>
                                </table>
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
            $('#codAmount').submit(function(e) {
                e.preventDefault();
                var amount = $("#amount").val();
                var delivery_boy = $("#delivery_boy_id").val();
                if (amount == '') {
                    Toast("warning", "Please enter amount");
                    return;
                }
                $.ajax({
                    url: "{{ url('/admin-cod-amount') }}",
                    type: 'POST',
                    data: {
                        amount: amount,
                        delivery_boy: delivery_boy
                    },
                    success: function(response) {
                        if (response.success) {
                            Toast("success", response.message);
                            $("#amount").val('');
                            $('#bodyData').html(response.html);
                        } else {
                            Toast("error", "Amount not submitted");
                        }
                    }
                });
            });
        });
    </script>
@endpush
