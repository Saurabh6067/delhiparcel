@extends('web.layout.main')
@section('main')

    <style>
        /* Card container */
        .single-features-sec {
            background: linear-gradient(145deg, #ffffff, #f1f5f9);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        @media screen and (min-width: 250px) and (max-width: 592px) {
            .hero {
                /*height: 80vh;*/
                /*background-image: none !important;*/

            }
        }

        .services-sec {
            padding: 0px;
        }

        .features-sec {
            margin: 0px !important;
        }

        /* Hover effect for cards */
        .single-features-sec:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        }

        /* Card title styling */
        .single-features-sec h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 15px;
            letter-spacing: 1px;
            background: linear-gradient(to right, #28a745, #dc3545);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: underline;
            text-decoration-color: #0a0a0a;
        }

        /* Text content styling */
        .single-features-sec p {
            font-size: 1rem;
            line-height: 1.6;
            color: #333;
            margin-bottom: 20px;
        }

        /* Button styling (enhancing rainbow-hover) */


        /* Optional decorative element */
        .single-features-sec::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #28a745, #dc3545);
            border-radius: 15px 15px 0 0;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .single-features-sec h3 {
                font-size: 1.5rem;
            }

            .single-features-sec p {
                font-size: 0.9rem;
            }

            .rainbow-hover {
                padding: 8px 16px;
                font-size: 0.9rem;
            }

            .our_service {
                box-shadow: 5px 5px 10px silver;
                background-color: #eef0f4;
            }

            .features-sec {
                margin: 0px !important;
            }

            .single-features-sec {
                margin-bottom: 0px;
            }

            .image_block_1 {
                margin-top: 60px !important;
            }

            .btn-mobile_section {
                font-size: 10px;
            }
        }
    </style>
    @include('web.inc.hero')


    <div class="container-fluid">
        <div class="container mt-4 ">
            <h3 class="text-left">Track Your Order</h3>
            <div class="row">
                <form id="trackNow">
                    <div class="input-group">
                        <input type="text" class="form-control" id="orderId" name="orderId" placeholder="Enter order id..."
                            required>
                        <button type="submit" class="btn btn-dark btn-mobile_section">Track now</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="container text-center mt-5">
            <h1>Are you a business?</h1>
            <br>
            <strong class="mt-2">Start shipping your orders in Just 3 Steps !</strong>
            <section class="features-sec">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <div class="single-features-sec">
                                <div class="icon"><img src="{{ asset('web/images/features-icon1.png') }}" alt="">
                                </div>
                                <h4>1. Fill Form</h4>
                                <span>You just need to fill the form by clicking on the below apply now
                                    button.</span><br>
                                <a href="{{ route('web.enquiry') }}">Apply Now</a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="single-features-sec">
                                <div class="icon"><img src="{{ asset('web/images/features-icon2.png') }}" alt="">
                                </div>
                                <h4>2. Upload Documents</h4>
                                <span>Upload your gst no. or pan card to verify your details.</span><br>
                                <a href="{{ route('web.enquiry') }}">Submit Detail</a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="single-features-sec">
                                <div class="icon"><img src="{{ asset('web/images/features-icon3.png') }}" alt="">
                                </div>
                                <h4>3. Start Shipping</h4>
                                <span>Our team member will contact you to provide you further details.</span><br>
                                <a href="{{ route('web.enquiry') }}">Get in Touch</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <section class="about-sec pb-5 mb-5 mt-5">
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
                                        <h1>3<sup>+</sup></h1>
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
                                Delhi parcel is door to door courier service provider which offer three type of services
                                first is same day delivery service which is called “Express service” where we deliver
                                parcel on the same day of booking ,second is “Standard service” where parcel is
                                delivered within 2 days of booking and Third One is “Super Express service” where parcel is
                                delivered within 4 Hour's of booking , our charges are very competitive in the industry
                                which results in your great savings.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="services-sec our_service">
            <div class="container-fluid ">

                <div class="row justify-content-center pt-0">
                    <div class="col-lg-8 col-md-8">
                        <div class="sec-title">

                            <div class="sec-title mt-4">
                                <h2><span>Our Services</span></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <section class="features-sec">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <div class="single-features-sec">
                                    <h3><span>Super Express</span></h3>
                                    <p class="text-dark">
                                        1. Delivery within 4 Hours<br>
                                        2. Starting at Just ₹50<br>
                                        3. Secure Delivery<br>
                                        4. Verified Delivery Person
                                    </p>
                                    <a href="{{ route('web.bookparcel') }}" class="bg-none">
                                        <button data-label="Register" class="rainbow-hover btn mt-3">
                                            <span class="sp">Book Parcel</span>
                                        </button>
                                    </a>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <div class="single-features-sec">
                                    <h3><span>Express Service</span></h3>
                                    <p class="text-dark">
                                        1. Same Day Delivery<br>
                                        2. Starting at Just ₹70<br>
                                        3. Secure Delivery<br>
                                        4. Verified Delivery Person
                                    </p>
                                    <a href="{{ route('web.bookparcel') }}">
                                        <button data-label="Register" class="rainbow-hover btn mt-3">
                                            <span class="sp">Book Parcel</span>
                                        </button>
                                    </a>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-6">
                                <div class="single-features-sec">
                                    <h3><span>Standard Service</span></h3>
                                    <p class="text-dark">
                                        1. Delivery Within 2 Days<br>
                                        2. Starting at Just ₹25<br>
                                        3. Secure Delivery<br>
                                        4. Verified Delivery Person
                                    </p>
                                    <a href="{{ route('web.bookparcel') }}">
                                        <button data-label="Register" class="rainbow-hover btn mt-3">
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
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#trackNow').on('submit', function (e) {
                e.preventDefault();

                let orderId = $('#orderId').val();

                $.ajax({
                    type: "POST",
                    url: "{{ route('web.trackOrderDetails') }}",
                    data: {
                        orderId: orderId,
                    },
                    dataType: "json",
                    success: function (response) {
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
                    error: function () {
                        swal("Error", "Something went wrong. Please try again.", "error");
                    }
                });
            });
        });
    </script>
@endpush