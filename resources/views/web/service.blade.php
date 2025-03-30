@extends('web.layout.main')
@section('main')
    <style>
        p {
            float: left;
            font-size: 1.5rem;
        }

        .services-sec {
            margin-top: 100px;
        }

        .container-fluid {
            background-image: url(assets/images/bgm.png);
            background-repeat: no-repeat;
            background-size: cover;
        }

        .title {
            font-size: 2em;
            margin-bottom: 20px;
            text-align: center;
        }

        .sc-iMWBiJ {
            margin: 0 10px;
            padding: 10px 20px;
            border: none;
            background-color: #0097a7;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .sc-iMWBiJ:hover {
            background-color: #00796b;
        }

        .tabActive {
            background-color: #00796b;
        }

        .sc-fvtFIe {
            display: none;
        }

        .active {
            display: block;
        }

        .blackBtnUnderline {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            border: 1px solid #00796b;
            color: #00796b;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .blackBtnUnderline:hover {
            background-color: #00796b;
            color: white;
        }

        #reference-tracking {
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            align-items: center;
            justify-content: center;
            width: 200px;
            height: 40px;
        }

        .s-card {
            display: flex;
            justify-content: center;
        }

        .service-card {
            max-width: 350px;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding: 35px;
            margin-top: 10px;
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 20px;
            background-color: rgba(255, 255, 255, 0.45);
            box-shadow: 0 0 10px 1px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(15px);
        }

        .whychoose {
            height: 80px;
            margin-bottom: 50px;
        }

        @media (max-width: 768px) {
            .Services-type {
                flex-direction: column;
                /* Stack buttons on small screens */
            }
        }
    </style>
    <div class="container-fluid pt-5">
        <section class="services-sec pt-5 pb-3">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-8 ">
                        <div class="sec-title ">
                            <h2><span>Our Services</span></h2>
                            <br>
                            <img src="{{ asset('web/images/logo.png') }}" class="whychoose">
                            <p>Delhi parcel is dedicated to provide reliable and efficient courier services to businesses
                                and individuals alike. Our team is committed to ensuring that your packages are delivered
                                safely and on time, every time. Delhi parcel is a door-to-door courier service provider
                                which offers two types of services which are as follows:</p>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div data-testid="Services-container" class="col-md-12 text-center">
                        <div class="Services-type d-md-flex justify-content-center">
                            <div class="col-12 col-lg-4 pt-3">
                                <button class="sc-iMWBiJ dXzrtu tabActive" onclick="showTab('b2c')">Express Service</button>
                            </div>
                            <div class="col-12 col-lg-4 pt-3">
                                <button class="sc-iMWBiJ dXzrtu" onclick="showTab('b2b')">Standard Service</button>
                            </div>
                            <div class="col-12 col-lg-4 pt-3">
                                <button class="sc-iMWBiJ dXzrtu" onclick="showTab('crossBorder')">Super Express
                                    Service</button>
                            </div>
                        </div>

                        <div id="b2c" class="sc-fvtFIe active">
                            <div class="row pt-5 justify-content-center">
                                <div class="col-12 col-lg-5 text-center">
                                    <p>Our Express Service provides fast and reliable delivery directly to consumers,
                                        catering to both individual and business needs. This service is designed for quick
                                        turnaround times, offering options for same-day and next-day delivery across 20,000+
                                        pin codes in India. Customers benefit from real-time tracking, ensuring transparency
                                        and confidence in every shipment.</p>
                                </div>

                                <div class="s-card col-12 col-lg-7 text-center">
                                    <div class="service-card">
                                        <h2>Express Service</h2>
                                        <p>
                                            1. Same Day Delivery<br>
                                            2. Starting at just ₹70<br>
                                            3. Secure delivery<br>
                                            4. Verified Delivery person
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="b2b" class="sc-fvtFIe">
                            <div class="row pt-5 justify-content-center">
                                <div class="col-12 col-lg-5">
                                    <p>Our Standard Service provides economical and reliable shipping solutions for
                                        businesses of all sizes. Designed for routine deliveries, it balances speed and
                                        cost, making it ideal for less urgent shipments. Customers can track their packages
                                        in real-time, ensuring transparency and peace of mind.</p>
                                </div>

                                <div class="s-card col-12 col-lg-7">
                                    <div class="service-card text-center">
                                        <h2>Standard Service</h2>
                                        <p>
                                            1. Guaranteed Delivery Within 2 days<br>
                                            2. Starting at just ₹25<br>
                                            3. Secure delivery<br>
                                            4. Verified Delivery person
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="crossBorder" class="sc-fvtFIe">
                            <div class="row pt-5 justify-content-center">
                                <div class="s-card col-12 col-lg-5">
                                    <p>Super Express Service offers ultra-fast delivery for urgent shipments, guaranteeing
                                        delivery within hours. Ideal for time-sensitive packages, this service prioritizes
                                        speed without compromising on safety. With advanced tracking features, customers can
                                        monitor their shipments closely from pickup to delivery.</p>
                                </div>

                                <div class="s-card col-12 col-lg-7">
                                    <div class="service-card text-center">
                                        <h2>Super Express Service</h2>
                                        <p>
                                            1. Guaranteed Delivery Within 4 Hours<br>
                                            2. Starting at just ₹70/km<br>
                                            3. Secure delivery<br>
                                            4. Delivered by Verified Delivery person
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <script>
        function showTab(tabId) {
            const tabs = document.querySelectorAll('.sc-fvtFIe');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });
            document.getElementById(tabId).classList.add('active');

            const buttons = document.querySelectorAll('.sc-iMWBiJ');
            buttons.forEach(button => {
                button.classList.remove('tabActive');
            });
            document.querySelector(`button[onclick="showTab('${tabId}')"]`).classList.add('tabActive');
        }
    </script>
@endpush
