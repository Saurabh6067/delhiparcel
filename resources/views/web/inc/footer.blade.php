<!-- Up arrow icon -->
<button id="scrollTop" title="Scroll to Top">
    <span class="scroll-icon">&#8679;</span>
</button>

<div class="container-fluid pt-5" style="background-color: #31323B;margin-top:20px">
    <footer class="text-center text-white">

        <div class="container-fluid pt-3" style="display: flex; justify-content:center; align-items:center;">
    <section class="w-100">
        <div class="row">

            <!-- First Column -->
            <div class="col-12 col-lg-3 mt-5">
                <a href="{{ url('/') }}">
                    <img class="logo bg-white border-radius-5px" src="{{ asset('web/images/logo.png') }}" alt="Logo">
                </a>
                <br><br>
                <em>
                    Delhi Parcel offers the best door-to-door courier service in Delhi NCR. We guarantee safe
                    and timely delivery of your packages. Enjoy the lowest prices without compromising on
                    quality. Experience top-notch service that sets us apart in the industry!
                </em>
            </div>

            <!-- Second Column -->
            <div class="col-6 col-lg-2 mt-5">
                <h6 class="text-uppercase mb-4 font-weight-bold" style="text-decoration:underline">Useful Links</h6>
                <!--<em><a class="text-white" href="{{ route('web.blog') }}">Blog</a></em><br>-->
                <em><a class="text-white" href="{{ ('/#pricing') }}">Pricing</a></em><br>
                <em><a class="text-white" href="{{ route('web.bookparcel') }}">Book Parcel</a></em><br>
                <em><a class="text-white" href="{{ route('web.trackOrder') }}">Track Order</a></em><br>
                <em><a class="text-white" href="{{ route('web.services') }}">Our Services</a></em>
            </div>

            <!-- Third Column -->
            <div class="col-6 col-lg-3 mt-5">
                <h6 class="text-uppercase text-center mb-4 font-weight-bold" style="text-decoration:underline">Important Links</h6>
                <em><a class="text-white" href="{{ route('web.enquiry') }}">Contact-Us</a></em><br>
                <em><a class="text-white" href="{{ route('web.terms_conditions') }}">Terms & Conditions</a></em><br>
                <em><a class="text-white" href="{{ route('web.refundPolicy') }}">Cancellation & Refund Policy</a></em><br>
                <em><a class="text-white" href="{{ route('web.privacy') }}">Privacy & Policy</a></em>
            </div>

            <!-- Fourth Column -->
            <div class="col-6 col-lg-2 mt-5">
                <h6 class="text-uppercase mb-4 font-weight-bold" style="text-decoration:underline">Employment</h6>
                <em><a class="text-white" href="{{ route('web.deliveryboy_enq') }}">Become a Delivery Boy</a></em><br>
                <em><a class="text-white" href="{{ route('web.franchise_enq') }}">Franchise Enquiry</a></em>
            </div>

            <!-- Fifth Column -->
            <div class="col-6 col-lg-2 mt-5">
                <h6 class="text-uppercase mb-3 font-weight-bold" style="text-decoration:underline">Contact-Us</h6>
                <span><i class="bi bi-house-fill me-2"></i>J-16, Pratap Nagar <br>New Delhi ,110007</span>
                <p><i class="bi bi-telephone-fill me-2"></i> 7678149050</p>
                <p>
                    <a target="_blank" href="https://wa.me/+917678149050" class="text-white">
                        <i class="bi bi-whatsapp me-2"></i> 7678149050
                    </a>
                </p>
            </div>

        </div>
    </section>
</div>



        <!-- Copyright -->
        <div class="container-fluid p-3 text-center bg-success mt-5">
            Copyright © 2025 Delhi Parcel, All Right Reserved | Design & Developed By
            <a class="text-danger" target="_blank" href="bhoomitechzone.in">Bhoomitech Zone Pvt. Ltd.</a>
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
