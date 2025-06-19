@extends('web.layout.main')
@section('main')
    <style>
        /* General Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #333;
        }


        .services-sec {
            padding: 60px 0;
        }

        /* Section Title */
        .sec-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .sec-title h2 {
            font-size: 2.5rem;
            color: #000;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            display: inline-block;
        }


        .sec-title img.whychoose {
            width: 120px;
            margin: 20px 0;
            animation: pulse 2s infinite;
        }

        .sec-title p {
            font-size: 1.2rem;
            color: #000;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.8;
        }

        /* Tab Navigation */
        .Services-type {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .sc-iMWBiJ {
            padding: 12px 30px;
            border: none;
            background: #424242;
            color: #fff;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            outline: none;
        }

        .sc-iMWBiJ:hover {
            background: #e53935;
            transform: translateY(-2px);
        }

        .tabActive {
            background: #e53935;
            box-shadow: 0 4px 15px rgba(229, 57, 53, 0.4);
        }

        /* Tab Content */
        .sc-fvtFIe {
            display: none;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .active {
            display: block;
            opacity: 1;
        }

        /* Service Card */
        .service-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 30px;
            margin: 15px;
            max-width: 400px;
            min-height: 280px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        .service-card h2 {
            font-size: 1.8rem;
            color: #e53935;
            margin-bottom: 15px;
        }

        .service-card p {
            font-size: 1rem;
            color: #555;
            line-height: 1.6;
        }

        /* Content Text */
        .sc-fvtFIe .col-lg-5 p {
            font-size: 1.1rem;
            color: #000;
            line-height: 1.8;
            text-align: left;
            padding: 20px;
        }

        /* Animations */
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sec-title h2 {
                font-size: 2rem;
            }

            .Services-type {
                flex-direction: column;
                align-items: center;
            }

            .sc-iMWBiJ {
                width: 80%;
                margin-bottom: 10px;
            }

            .service-card {
                max-width: 90%;
            }

            .sc-fvtFIe .col-lg-5 p {
                text-align: center;
            }
        }
    </style>

    <div class="container-fluid" >
        <section class="services-sec">
            <div class="container" style="margin-top:80px">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="sec-title">
                            <h2>Our Services</h2>
                            <!--<img src="{{ asset('web/images/logo.png') }}" class="whychoose" alt="Delhi Parcel Logo">-->
                            <p>Delhi Parcel is committed to delivering reliable and efficient courier services to businesses and individuals. Our dedicated team ensures your packages arrive safely and on time, every time. Explore our tailored services below:</p>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center mt-5">
                    <div data-testid="Services-container" class="col-md-12 text-center">
                        <div class="Services-type">
                            <button class="sc-iMWBiJ tabActive" onclick="showTab('b2c')">Express Service</button>
                            <button class="sc-iMWBiJ" onclick="showTab('b2b')">Standard Service</button>
                            <button class="sc-iMWBiJ" onclick="showTab('crossBorder')">Super Express Service</button>
                        </div>

                        <div id="b2c" class="sc-fvtFIe active">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-12 col-lg-5">
                                    <p>Our Express Service ensures swift and dependable delivery directly to consumers, perfect for both individuals and businesses. Enjoy same-day delivery across Delhi NCR with complete transparency.</p>
                                </div>
                                <div class="col-12 col-lg-5">
                                    <div class="service-card">
                                        <h2>Express Service</h2>
                                        <p>
                                            • Same Day Delivery<br>
                                            • Starting at Just ₹70<br>
                                            • Secure Delivery<br>
                                            • Verified Delivery Person
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="b2b" class="sc-fvtFIe">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-12 col-lg-5">
                                    <p>Our Standard Service offers cost-effective and reliable shipping for businesses of all sizes. Ideal for non-urgent deliveries, it balances speed and affordability, complete with real-time tracking for peace of mind.</p>
                                </div>
                                <div class="col-12 col-lg-5">
                                    <div class="service-card">
                                        <h2>Standard Service</h2>
                                        <p>
                                            • Delivery Within 2 Days<br>
                                            • Starting at Just ₹25<br>
                                            • Secure Delivery<br>
                                            • Verified Delivery Person
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="crossBorder" class="sc-fvtFIe">
                            <div class="row justify-content-center align-items-center">
                                <div class="col-12 col-lg-5">
                                    <p>Super Express Service guarantees ultra-fast delivery for urgent shipments, often within hours. Perfect for time-sensitive packages, this service prioritizes speed and safety with advanced tracking features.</p>
                                </div>
                                <div class="col-12 col-lg-5">
                                    <div class="service-card">
                                        <h2>Super Express Service</h2>
                                        <p>
                                            • Delivery within 4 Hours<br>
                                            • Starting at Just ₹50<br>
                                            • Secure Delivery<br>
                                            • Verified Delivery Person
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
            tabs.forEach(tab => tab.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');

            const buttons = document.querySelectorAll('.sc-iMWBiJ');
            buttons.forEach(button => button.classList.remove('tabActive'));
            document.querySelector(`button[onclick="showTab('${tabId}')"]`).classList.add('tabActive');
        }
    </script>
@endpush