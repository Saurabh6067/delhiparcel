<div class="container1">
    <marquee>Delhi parcel is dedicated to provide reliable and efficient courier services to businesses and
        individuals alike.</marquee>
    <div id="header">
        <a href="{{ url('/') }}"> <img class="logo" src="{{ asset('web/images/logo.png') }}" alt="Logo"></a>
        <nav style="justify-content:end !important">

            <ul class="nav-links" id="navLinks">
                <li><a href="{{ url('/') }}">Home</a></li>
                <li><a href="{{ route('web.bookparcel') }}">Book Parcel</a></li>
                <li><a href="{{ route('web.trackOrder') }}">Track Order</a></li>
                <li>
                    <a href="{{ route('web.services') }}">Services</a>
                    <!--<div class="sub-dropdown">-->
                    <!--    <ul>-->
                    <!--        <li><a href="{{ route('web.services') }}"><span>All Services</span></a></li>-->
                    <!--        <li><a href="{{ route('web.services') }}">Express Service</a></li>-->
                    <!--        <li><a href="{{ route('web.services') }}">Standard Service</a></li>-->
                    <!--        <li><a href="{{ route('web.services') }}">Super Express Service</a></li>-->
                    <!--    </ul>-->
                    <!--</div>-->
                </li>
                <li><a href="contact.php" style="color:#FF5D01;"></a></li>
            </ul>

        </nav>

        <div class="auth-buttons">
            <a href="{{ route('web.enquiry') }}" data-label="Register" class="rainbow-hover">
                <span class="sp">Business Enquiry</span>
            </a>
            <div class="hamburger" id="hamburger">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>

    <div class="dropdown" id="dropdownMenu">
        <ul>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><a href="{{ route('web.bookparcel') }}">Book Parcel</a></li>
            <li><a href="{{ route('web.trackOrder') }}">Track Order</a></li>
            <li>
                <a href="{{ route('web.services') }}">Services</a>
                
                <!--<a> Services</a>-->
                <!--<div class="sub-dropdown">-->
                <!--    <ul>-->
                <!--        <li><a href="{{ route('web.services') }}">All Services</a></li>-->
                <!--        <li><a href="{{ route('web.services') }}">Express Service</a></li>-->
                <!--        <li><a href="{{ route('web.services') }}">Standard Service</a></li>-->
                <!--        <li><a href="{{ route('web.services') }}">Super Express Service</a></li>-->
                <!--    </ul>-->
                <!--</div>-->
            </li>

            <li><a href="#" style="color:#FF5D01;"></a></li>
        </ul>
    </div>

</div>

@push('scripts')
    <script>
        const header = document.getElementById('header');
        const hamburger = document.getElementById('hamburger');
        const dropdownMenu = document.getElementById('dropdownMenu');

        window.addEventListener('scroll', function() {
            if (window.scrollY > 0) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        hamburger.addEventListener('click', () => {
            dropdownMenu.classList.toggle('dropdown-active');
        });

        window.addEventListener('click', (event) => {
            if (!event.target.closest('nav') && !event.target.closest('#hamburger')) {
                dropdownMenu.classList.remove('dropdown-active');
                const subDropdowns = document.querySelectorAll('.sub-dropdown');
                subDropdowns.forEach(subDropdown => {
                    subDropdown.classList.remove('sub-dropdown-active');
                });
            }
        });

        document.querySelectorAll('#dropdownMenu > ul > li').forEach(item => {
            item.addEventListener('click', (event) => {
                const subDropdown = item.querySelector('.sub-dropdown');
                if (subDropdown) {
                    subDropdown.classList.toggle('sub-dropdown-active');
                    event.stopPropagation();
                }
            });
        });
    </script>
@endpush
