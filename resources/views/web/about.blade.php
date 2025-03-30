@extends('web.layout.main')
@section('main')
    <style>
        p {
            float: left;
            font-size: 1.5rem;
        }

        .container-fluid {
            background-image: url(web/images/bgm.png);
            background-repeat: no-repeat;
            background-size: cover;
        }

        .whychoose {
            height: 80px;
            margin-bottom: 50px;

        }

        .space {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
    <div class="container-fluid pt-5">
        <section class="services-sec mt-5 pt-5">
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-8 ">
                        <div class="sec-title">
                            <h2><span>About Us</span></h2>
                            <br>
                            <img src="{{ asset('web/images/logo.png') }}" class="whychoose">
                            <p class="text-center">We are the fastest growing express logistics service provider in India</p>

                        </div>
                    </div>
                </div>
            </div>
        </section>


        <div class="container pb-3 pt-3 text-center">
            <div class="row space">
                <div class="col-lg-6">
                    <img src="assets/images/bg5.jpg">

                </div>
                <div class="col-lg-6 p-3">
                    <p>DAGRREGVF</p>

                </div>
            </div>
        </div>

        <div class="container text-center mb-5">
            <div class="row space">
                <div class="col-lg-6">
                    <h3>Our Mission</h3>
                    <p>SWFGRWS</p>
                </div>
                <div class="col-lg-6">
                    <h3>Our Vision</h3>
                    <p>AREGASrG</p>
                </div>
            </div>
        </div>
    </div>
@endsection
