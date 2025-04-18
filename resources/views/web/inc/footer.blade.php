<!-- Up arrow icon -->
<button id="scrollTop" title="Scroll to Top">
    <span class="scroll-icon">&#8679;</span>
</button>

<div class="container-fluid pt-5" style="background-color: #31323B">

    <!-- <div class="container">
      <section class="">
        <form action="">
          <div class="row d-flex justify-content-center">
            <div class="col-auto">
              <p class=" text-white">Subscribe Our Newsletter</p>
            </div>
            <div class="col-md-5 col-12">
              <div class="form-outline form-white mb-4">
                <input type="email" id="form5Example2" placeholder="Enter Your E-mail " class="form-control" />
              </div>
            </div>
            <div class="col-auto">
              <button data-label="Register" class="rainbow-hover">
                <span class="sp">Subscribe</span>
              </button>
            </div>
          </div>
        </form>
      </section>
    </div> -->

    <!-- Footer -->
    <footer class="text-center text-white">

        <div class="container-fluid pt-3 " style="display: flex; justify-content:center; align-items:center;">
            <section class="">
                <div class="row">

                    <div class="col-md-12 col-lg-4 mt-3">
                        <a href="{{ url('/') }}"> <img class="logo bg-white border-radius-5px"
                                src="{{ asset('web/images/logo.png') }}" alt="Logo"></a>
                        <br><br>
                        <em>
                            Delhi Parcel offers the best door-to-door courier service in Delhi NCR. We guarantee safe
                            and timely delivery of your packages. Enjoy the lowest prices without compromising on
                            quality. Experience top-notch service that sets us apart in the industry!
                        </em>
                    </div>

                    <div class="col-12 col-lg-4 row">

                        <!-- Align "Useful Links" and "Important Links" in same row on medium screens -->
                        <div class="col-6  mt-3">
                            <h6 class="text-uppercase text-center mb-4 font-weight-bold">Useful Links</h6>
                            <em>
                                <a class="text-white" href="{{route('web.blog')}}">Blog</a>
                            </em><br>
                            <em>
                                <a class="text-white" href="{{ ('/#pricing') }}">Pricing</a>
                            </em><br>
                            <em>
                                <a class="text-white" href="{{ route('web.bookparcel') }}">Book Parcel</a>
                            </em><br>
                            <em>
                                <a class="text-white" href="{{ route('web.trackOrder') }}">Track Order</a>
                            </em><br>
                            <em>
                                <a class="text-white" href="{{ route('web.services') }}">Our Services</a>
                            </em>
                        </div>

                        <div class="col-6 mt-3">
                            <h6 class="text-uppercase text-center mb-4 font-weight-bold">Important Links</h6>

                            <em>
                                <a class="text-white" href="{{ route('web.enquiry') }}">Contact-Us</a>
                            </em><br>
                            <em>
                                <a class="text-white" href="{{ route('web.privacy') }}">Privacy & Policy</a>
                            </em><br>
                            <em>
                                <a class="text-white" href="{{ route('web.terms_conditions') }}">Terms & Conditions</a>
                            </em><br>
                            <em>
                                <a class="text-white" href="{{ route('web.refundPolicy') }}">Cancellation & Refund
                                    Policy</a>
                            </em>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-4 ">
                        <!-- <h6 class="text-uppercase mb-3 font-weight-bold">Follow-us</h6> -->

                        <div class="contactus">
                            <h6 class="text-uppercase  mt-3 mb-3 font-weight-bold">Contact-Us</h6>
                            <span>B-14/100, Street no - 11, Subhash vihar<br>New Delhi North East, delhi 110053</span>
                        </div>

                        <div class="main">
                            <div class="up">
                                <button class="card1">
                                    <svg width="30" height="30" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg" class="whatsapp">
                                        <path
                                            d="M19.001 4.908A9.817 9.817 0 0 0 11.992 2C6.534 2 2.085 6.448 2.08 11.908c0 1.748.458 3.45 1.321 4.956L2 22l5.255-1.377a9.916 9.916 0 0 0 4.737 1.206h.005c5.46 0 9.908-4.448 9.913-9.913A9.872 9.872 0 0 0 19 4.908h.001ZM11.992 20.15A8.216 8.216 0 0 1 7.797 19l-.3-.18-3.117.818.833-3.041-.196-.314a8.2 8.2 0 0 1-1.258-4.381c0-4.533 3.696-8.23 8.239-8.23a8.2 8.2 0 0 1 5.825 2.413 8.196 8.196 0 0 1 2.41 5.825c-.006 4.55-3.702 8.24-8.24 8.24Zm4.52-6.167c-.247-.124-1.463-.723-1.692-.808-.228-.08-.394-.123-.556.124-.166.246-.641.808-.784.969-.143.166-.29.185-.537.062-.247-.125-1.045-.385-1.99-1.23-.738-.657-1.232-1.47-1.38-1.716-.142-.247-.013-.38.11-.504.11-.11.247-.29.37-.432.126-.143.167-.248.248-.413.082-.167.043-.31-.018-.433-.063-.124-.557-1.345-.765-1.838-.2-.486-.404-.419-.557-.425-.142-.009-.309-.009-.475-.009a.911.911 0 0 0-.661.31c-.228.247-.864.845-.864 2.067 0 1.22.888 2.395 1.013 2.56.122.167 1.742 2.666 4.229 3.74.587.257 1.05.408 1.41.523.595.19 1.13.162 1.558.1.475-.072 1.464-.6 1.673-1.178.205-.58.205-1.075.142-1.18-.061-.104-.227-.165-.475-.29Z">
                                        </path>
                                    </svg>
                                </button>
                                <button class="card2">
                                    <svg class="twitter" height="30px" width="30px" viewBox="0 0 48 48"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M42,12.429c-1.323,0.586-2.746,0.977-4.247,1.162c1.526-0.906,2.7-2.351,3.251-4.058c-1.428,0.837-3.01,1.452-4.693,1.776C34.967,9.884,33.05,9,30.926,9c-4.08,0-7.387,3.278-7.387,7.32c0,0.572,0.067,1.129,0.193,1.67c-6.138-0.308-11.582-3.226-15.224-7.654c-0.64,1.082-1,2.349-1,3.686c0,2.541,1.301,4.778,3.285,6.096c-1.211-0.037-2.351-0.374-3.349-0.914c0,0.022,0,0.055,0,0.086c0,3.551,2.547,6.508,5.923,7.181c-0.617,0.169-1.269,0.263-1.941,0.263c-0.477,0-0.942-0.054-1.392-0.135c0.94,2.902,3.667,5.023,6.898,5.086c-2.528,1.96-5.712,3.134-9.174,3.134c-0.598,0-1.183-0.034-1.761-0.104C9.268,36.786,13.152,38,17.321,38c13.585,0,21.017-11.156,21.017-20.834c0-0.317-0.01-0.633-0.025-0.945C39.763,15.197,41.013,13.905,42,12.429">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            <div class="down">
                                <button class="card3">
                                    <svg class="github" height="30px" width="30px" viewBox="0 0 30 30"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M15,3C8.373,3,3,8.373,3,15c0,5.623,3.872,10.328,9.092,11.63C12.036,26.468,12,26.28,12,26.047v-2.051 c-0.487,0-1.303,0-1.508,0c-0.821,0-1.551-0.353-1.905-1.009c-0.393-0.729-0.461-1.844-1.435-2.526 c-0.289-0.227-0.069-0.486,0.264-0.451c0.615,0.174,1.125,0.596,1.605,1.222c0.478,0.627,0.703,0.769,1.596,0.769 c0.433,0,1.081-0.025,1.691-0.121c0.328-0.833,0.895-1.6,1.588-1.962c-3.996-0.411-5.903-2.399-5.903-5.098 c0-1.162,0.495-2.286,1.336-3.233C9.053,10.647,8.706,8.73,9.435,8c1.798,0,2.885,1.166,3.146,1.481C13.477,9.174,14.461,9,15.495,9 c1.036,0,2.024,0.174,2.922,0.483C18.675,9.17,19.763,8,21.565,8c0.732,0.731,0.381,2.656,0.102,3.594 c0.836,0.945,1.328,2.066,1.328,3.226c0,2.697-1.904,4.684-5.894,5.097C18.199,20.49,19,22.1,19,23.313v2.734 c0,0.104-0.023,0.179-0.035,0.268C23.641,24.676,27,20.236,27,15C27,8.373,21.627,3,15,3z">
                                        </path>
                                    </svg>
                                </button>
                                <button class="card4">
                                    <svg class="discord" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"
                                        width="30px" height="30px">
                                        <path
                                            d="M40,12c0,0-4.585-3.588-10-4l-0.488,0.976C34.408,10.174,36.654,11.891,39,14c-4.045-2.065-8.039-4-15-4s-10.955,1.935-15,4c2.346-2.109,5.018-4.015,9.488-5.024L18,8c-5.681,0.537-10,4-10,4s-5.121,7.425-6,22c5.162,5.953,13,6,13,6l1.639-2.185C13.857,36.848,10.715,35.121,8,32c3.238,2.45,8.125,5,16,5s12.762-2.55,16-5c-2.715,3.121-5.857,4.848-8.639,5.815L33,40c0,0,7.838-0.047,13-6C45.121,19.425,40,12,40,12z M17.5,30c-1.933,0-3.5-1.791-3.5-4c0-2.209,1.567-4,3.5-4s3.5,1.791,3.5,4C21,28.209,19.433,30,17.5,30z M30.5,30c-1.933,0-3.5-1.791-3.5-4c0-2.209,1.567-4,3.5-4s3.5,1.791,3.5,4C34,28.209,32.433,30,30.5,30z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>


        <!-- Copyright -->
        <div class="container-fluid p-3 text-center ">
            Copyright © 2024 Delhi Parcel, All Right Reserved | Maintain & Developed by
            <a class="text-success" href="bhoomitechzone.in">BTPL</a>
        </div>
        <!-- Copyright -->
    </footer>

</div>

@push('scripts')
    <script>
        const scrollTopButton = document.getElementById("scrollTop");

        window.onscroll = function() {
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                scrollTopButton.style.display = "flex"; // Show button
            } else {
                scrollTopButton.style.display = "none"; // Hide button
            }
        };

        scrollTopButton.onclick = function() {
            document.body.scrollTop = 0; // For Safari
            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE, and Opera
        };
    </script>
@endpush
