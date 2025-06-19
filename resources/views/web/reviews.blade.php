@extends('web.layout.main')
@section('main')
    <div class="container-fluid pt-5">
        <section class="services-sec mt-5 pt-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-8">
                        <div class="sec-title">
                            <h2><span>FeedBack</span></h2>
                            <br>
                            <img src="{{ asset('web/images/logo.png') }}" class="whychoose">
                            <p class="text-center"></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="container contact-form-container">
            <h2 class="text-center contact-form-title mb-4">FeedBack</h2>
            <form id="reviewForm">
                <div class="row">
                    <div class="col-12 col-lg-12">
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Full Name</label>
                            <input type="text" class="form-control contact-form-input w-100" id="fullName"
                                name="fullName" placeholder="Enter your name">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control contact-form-input w-100" id="email"
                                name="email" placeholder="Enter your email">
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" pattern="[6789][0-9]{9}" class="form-control contact-form-input w-100"
                                id="phone" name="phone" placeholder="Enter your phone number">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control contact-form-textarea w-100" id="message" name="message" rows="4"
                        placeholder="Type your message here"></textarea>
                </div>
                <div class="text-center">
                    <button data-label="Register" class="rainbow-hover text-center mt-2">
                        <span class="sp">Send</span>
                    </button>
                </div>
            </form>
        </div>
    @endsection
    @push('scripts')
        <script>
            $(document).ready(function() {
                // add inquiry 
                $('#reviewForm').on('submit', function(event) {
                    event.preventDefault();

                    // Front-end validation
                    var fullName = $('#fullName').val()
                    var email = $('#email').val()
                    var phone = $('#phone').val()
                    var message = $('#message').val()

                    if (!fullName) {
                        Toast("error", "Name is required");
                        return;
                    }

                    if (!message) {
                        Toast("error", "Message is required");
                        return;
                    }

                    var formData = new FormData(this);
                    $.ajax({
                        url: "{{ route('web.addReviews') }}",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        cache: false,
                        dataType: "json",
                        success: function(response) {
                            $('#reviewForm')[0].reset();
                            if (response.success) {
                                Toast("success", response.message);
                            } else {
                                Toast("error", response.message);
                            }
                        },
                        error: function(err) {
                            Toast("error", "Please try again.");
                        }
                    });
                });

            });
        </script>
    @endpush
