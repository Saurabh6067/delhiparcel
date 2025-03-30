@extends('web.layout.main')
@section('main')
    @include('web.inc.hero')
    <div class="container-fluid pt-5 pb-5">
        <div class="container ">
            <h3 class="text-left">Track Your Order</h3>
            <div class="row">
                <form id="trackNow">
                    <div class="input-group">
                        <input type="text" class="form-control" id="orderId" name="orderId" placeholder="Enter order id..."
                            required>
                        <button type="submit" class="btn btn-dark">Track now</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="container text-center mt-5">
            <h1>Are you a business?</h1>
            <br>
            <strong class="mt-2">Start shipping your orders within 10 minutes !</strong>
            <section class="features-sec">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="single-features-sec">
                                <div class="icon"><img src="{{ asset('web/images/features-icon1.png') }}" alt="">
                                </div>
                                <h4>Fill Form</h4>
                                <span>You just need to fill the form by clicking on the below apply now
                                    button.</span><br>
                                <a href="{{ route('web.enquiry') }}">Apply Now</a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="single-features-sec">
                                <div class="icon"><img src="{{ asset('web/images/features-icon2.png') }}" alt="">
                                </div>
                                <h4>Upload Documents</h4>
                                <span>Upload your gst no. or pan card to verify your details.</span><br>
                                <a href="{{ route('web.enquiry') }}">Submit Detail</a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="single-features-sec">
                                <div class="icon"><img src="{{ asset('web/images/features-icon3.png') }}" alt="">
                                </div>
                                <h4>Start Shipping</h4>
                                <span>Our team member will contact you to provide you further details.</span><br>
                                <a href="{{ route('web.enquiry') }}">Get in Touch</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <section class="about-sec pb-5 mb-5">
            <div class="container">
                <div class="row align-items-center clearfix">
                    <div class="col-lg-6 col-md-12 col-sm-12 image-column">
                        <div id="image_block_1">
                            <div class="image-box">
                                <figure class="image"><img src="{{ asset('web/images/about-1.jpg') }}" alt="">
                                </figure>
                                <div class="box">
                                    <div class="inner">
                                        <div class="icon-box">
                                            <div class="icon icon-1"></div>
                                            <div class="icon icon-2"></div>
                                        </div>
                                        <span>WE PROVIDE BEST COURIER SERVICES IN DELHI NCR</span>
                                        <h1>5<sup>+</sup></h1>
                                        <p>Years Of Experience</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                        <div id="content_block_1">
                            <div class="content-box">
                                <div class="section-title">
                                    <p>Who is Delhi Parcel</p>
                                    <h2>Best Courier &amp; Parcel Services</h2>
                                    <div class="dotted-box"> <span class="dotted"></span> <span class="dotted"></span>
                                        <span class="dotted"></span>
                                    </div>
                                </div>
                                Welcome to Delhi parcel courier website! Delhi parcel is dedicated to providing reliable
                                and efficient courier services to businesses and individuals alike. Our team is
                                committed to ensuring that your packages are delivered safely and on time, every time.
                                Delhi parcel is door to door courier service provider which offer two type of services
                                first is same day delivery service which is called “Express service” where we deliver
                                parcel on the same day of booking and second one is “Standard service” where parcel is
                                delivered within 2 days of booking, our charges are very competitive in the industry
                                which results in your great savings.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="services-sec pt-5">
            <div class="container-fluid">

                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-8">
                        <div class="sec-title">
                            <h4 class="text-center">
                                <span class="divhead">
                                    <span class="text-success">O</span>
                                    <span class="text-danger">ur </span>
                                    <span class="text-danger">S</span>
                                    <span class="text-success">ervice</span>
                                </span>
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="container mt-2">

                    <section class="features-sec">
                        <div class="container">
                            <div class="row">

                                <div class="col-lg-4 col-md-6">
                                    <div class="single-features-sec">
                                        <h3 class="text-center"><span class=""><span
                                                    class="text-success">S</span><span class="text-danger">uper </span>
                                                <span class="text-danger">E</span><span class="text-success">xpress<span
                                                        class="text-danger"></span></span></h4>

                                                <p class="text-dark">
                                                    1. Delivery in 4 Hours.
                                                    <br>
                                                    2. Starting at Just Js 70/km
                                                    <br>
                                                    3. Secure Delivery
                                                    <br>
                                                    4. Verified Delivery Person
                                                </p>

                                                <a href="{{ route('web.bookparcel') }}" class="bg-none">
                                                    <button data-label="Register" class="rainbow-hover btn  mt-3">
                                                        <span class="sp">Book Parcel</span>
                                                    </button>
                                                </a>
                                    </div>
                                </div>


                                <div class="col-lg-4 col-md-6">
                                    <div class="single-features-sec">
                                        <h3 class="text-center"><span class=""><span
                                                    class="text-success">E</span><span class="text-danger">xpress
                                                </span> <span class="text-danger">S</span><span
                                                    class="text-success">ervice<span class="text-danger"></span></span>
                                        </h3>

                                        <p class="text-dark">
                                            1. Same Day Delivery
                                            <br>
                                            2. Starting at Just Rs 70
                                            <br>
                                            3. Secure Delivery
                                            <br>
                                            4. Verified Delivery Person
                                        </p>

                                        <a href="{{ route('web.bookparcel') }}">
                                            <button data-label="Register" class="rainbow-hover btn  mt-3">
                                                <span class="sp">Book Parcel</span>
                                            </button>
                                        </a>
                                    </div>
                                </div>


                                <div class="col-lg-4 col-md-6">
                                    <div class="single-features-sec">
                                        <h3 class="text-center"><span class=""><span
                                                    class="text-success">S</span><span class="text-danger">tandard
                                                </span> <span class="text-danger">S</span><span
                                                    class="text-success">ervice<span class="text-danger"></span></span>
                                        </h3>

                                        <p class="text-dark">
                                            1. Delivery Within 2 Days
                                            <br>
                                            2. Starting at Just Rs 25,
                                            <br>
                                            3. Secure Pelivery,
                                            <br>
                                            4. Verified Delivery Person
                                        </p>

                                        <a href="{{ route('web.bookparcel') }}">
                                            <button data-label="Register" class="rainbow-hover btn  mt-3">
                                                <span class="sp">Book Parcel</span>
                                            </button>
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>

        @include('web.inc.priceList')

        <div class="container-fluid">
            <div class="row justify-content-center pt-5">
                <div class="col-lg-8 col-md-8">
                    <div class="sec-title">
                        <h4 class="text-center"><span class="divhead"><span class="text-success">W</span><span
                                    class="text-danger">hy </span> <span class="text-danger">C</span><span
                                    class="text-success">hoose</span></span></h4>
                    </div>
                </div>
            </div>


            <div class="row wc-card">

                <div class="col-12 col-md-4 col-lg-2 d-flex text-center pt-2 justify-content-center">
                    <div class="service-card ">
                        <img src="{{ asset('web/images/wc1.png') }}">
                        <h4 class="pt-2">5000<sup>+</sup></h4>
                        <span>Shipments/Day</span>
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-2 d-flex text-center  pt-2  justify-content-center">
                    <div class="service-card ">
                        <img src="{{ asset('web/images/wc2.png') }}">
                        <h4 class="pt-2">15<sup>+</sup></h4>
                        <span>Hubs</span>
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-2 d-flex text-center  pt-2  justify-content-center">
                    <div class="service-card ">
                        <img src="{{ asset('web/images/wc3.png') }}">
                        <h4 class="pt-2">350<sup>+</sup></h4>
                        <span>Service Centres</span>
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-2 d-flex text-center  pt-2  justify-content-center">
                    <div class="service-card ">
                        <img src="{{ asset('web/images/wc4.png') }}">
                        <h4 class="pt-2">5000<sup>+</sup></h4>
                        <span>Pin Codes</span>
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-2 d-flex text-center  pt-2  justify-content-center">
                    <div class="service-card ">
                        <img src="{{ asset('web/images/wc5.png') }}">
                        <h4 class="pt-2">50<sup>+</sup></h4>
                        <span>Field Service</span>
                    </div>
                </div>

                <div class="col-12 col-md-4 col-lg-2 d-flex text-center  pt-2  justify-content-center">
                    <div class="service-card ">
                        <img src="{{ asset('web/images/wc6.png') }}">
                        <h4 class="pt-2">5<sup>+</sup></h4>
                        <span>Abroads</span>
                    </div>
                </div>
            </div>

        </div>


        {{-- <div class="row justify-content-center pt-5">
            <div class="col-lg-8 col-md-8">
                <h4 class="text-center">
                    <span class="divhead">
                        <span class="text-success">C</span><span class="text-danger">lient </span>
                        <span class="text-danger">R</span><span class="text-success">eviews</span>
                    </span>
                </h4>
            </div>
        </div>

        <div id="testimonialCarousel" class="carousel slide" data-ride="carousel" data-interval="3000">
            <div class="carousel-inner">

                <div class="carousel-item">
                    <div class="testimonial-quote">
                        "The collaboration has exceeded our expectations. We appreciate their commitment to quality and
                        innovation."
                    </div>
                    <p class="partner-name">— Partner Name, Title, Company</p>
                </div>
                <div class="carousel-item">
                    <div class="testimonial-quote">
                        "We couldn't ask for better partners. Their dedication and responsiveness make all the
                        difference."
                    </div>
                    <p class="partner-name">— Partner Name, Title, Company</p>
                </div>
            </div>
        </div> --}}
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#trackNow').on('submit', function(e) {
                e.preventDefault();

                let orderId = $('#orderId').val();

                $.ajax({
                    type: "POST",
                    url: "{{ route('web.trackOrderDetails') }}",
                    data: {
                        orderId: orderId,
                    },
                    dataType: "json",
                    success: function(response) {
                        $('#trackNow')[0].reset();
                        if (response.success) {
                            swal("Success", response.message, "success");
                            setTimeout(() => {
                                window.location.href = "{{ url('trackOrders') }}/" +
                                    response.data.order_id;
                            }, 1500);
                        } else {
                            swal("Error", response.message, "error");
                        }
                    },
                    error: function() {
                        swal("Error", "Something went wrong. Please try again.", "error");
                    }
                });
            });
        });
    </script>
@endpush
