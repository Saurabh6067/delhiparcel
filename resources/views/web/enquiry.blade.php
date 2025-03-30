@extends('web.layout.main')
@section('main')
    <div class="container-fluid pt-5">

        <section class="services-sec mt-5 pt-5">
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-8">
                        <div class="sec-title">
                            <h2><span>Contact Us</span></h2>
                            <br>
                            <img src="{{ asset('web/images/logo.png') }}" class="whychoose">
                            <p class="text-center">We are the fastest growing express logistics service provider in India</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="container contact-form-container">
            <h2 class="text-center contact-form-title mb-4">Contact Form</h2>
            <form id="contactForm">
                <div class="row">
                    <div class="col-12 col-lg-6">
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
                        <div class="mb-3">
                            <label for="panImage" class="form-label">Gst/Pan Card Image</label>
                            <input type="file" class="form-control contact-form-input w-100" id="panImage"
                                name="panImage" required>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="mb-3">
                            <label for="itemsCount" class="form-label">No.Of Items</label>
                            <input type="number" class="form-control contact-form-input w-100" id="itemsCount"
                                name="itemsCount" placeholder="Enter No.Of Items">
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Select Categories</label>
                            <select class="form-select contact-form-select w-100" id="category" name="category">
                                <option selected disabled>Select</option>
                                @foreach ($data as $item)
                                    <option value="{{ $item->id }}">{{ $item->cat_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="panNo" class="form-label">Gst/Pan Card No.</label>
                            <input type="text" class="form-control contact-form-input w-100" id="panNo"
                                name="panNo" placeholder="Enter your gst/pan card number">
                        </div>
                        <div class="mb-3">
                            <label for="pinCode" class="form-label">Pin Code</label>
                            <input type="text" maxlength="6" name="pinCode"
                                class="form-control contact-form-input w-100" id="pinCode" name="pinCode"
                                placeholder="Enter your pin code">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="fullAddress" class="form-label">Full Address</label>
                    <input type="text" class="form-control contact-form-input w-100" id="fullAddress" name="fullAddress"
                        placeholder="Enter address">
                </div>
                <div class="mb-3">
                    <label for="contactMessage" class="form-label">Message</label>
                    <textarea class="form-control contact-form-textarea w-100" id="contactMessage" name="contactMessage" rows="4"
                        placeholder="Type your message here"></textarea>
                </div>
                <div class="text-center">
                    <button data-label="Register" class="rainbow-hover text-center mt-2">
                        <span class="sp">Submit</span>
                    </button>
                </div>
            </form>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-8 ">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d111989.16561106232!2d77.12046146392828!3d28.699772976417194!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cfc159ee686d5%3A0x634bded521417dfb!2sDelhi%20Parcel!5e0!3m2!1sen!2sin!4v1730961959098!5m2!1sen!2sin"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>

                <div class="col-12 col-lg-4 address">
                    <h2>Address :</h2>
                    <p>B-14/100, Street no - 11, Subhash vihar New Delhi North East, delhi, 110053</p>
                    <p>call us: 7678149050</p>
                    <p>Mail : info@delhiparcel.com</p>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // add inquiry 
            $('#contactForm').on('submit', function(event) {
                event.preventDefault();

                // Front-end validation
                var fullName = $('#fullName').val()
                var email = $('#email').val()
                var phone = $('#phone').val()
                var category = $('#category').val()
                var fullAddress = $('#fullAddress').val()
                var itemsCount = $('#itemsCount').val()
                var panNo = $('#panNo').val()
                var pinCode = $('#pinCode').val()
                var contactMessage = $('#contactMessage').val()

                if (!fullName) {
                    Toast("error", "Name is required");
                    return;
                }
                if (!email) {
                    Toast("error", "email is required");
                    return;
                }
                if (!phone) {
                    Toast("error", "phone is required");
                    return;
                }
                if (!category) {
                    Toast("error", "category is required");
                    return;
                }
                if (!fullAddress) {
                    Toast("error", "Address is required");
                    return;
                }
                if (!itemsCount) {
                    Toast("error", "Items Count is required");
                    return;
                }
                if (!panNo) {
                    Toast("error", "Pan no or GST no is required");
                    return;
                }
                if (!pinCode) {
                    Toast("error", "Pin Code is required");
                    return;
                }
                if (!contactMessage) {
                    Toast("error", "Message is required");
                    return;
                }

                var formData = new FormData(this);
                $.ajax({
                    url: "{{ route('admin.addEnquiry') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    dataType: "json",
                    success: function(response) {
                        $('#contactForm')[0].reset();
                        if (response.success) {
                            Toast("success", response.message);
                        } else {
                            Toast("error", response.message);
                        }
                    },
                    error: function(err) {
                        Toast("error",
                            "An unexpected error occurred. Please try again.");
                    }
                });
            });

        });
    </script>
@endpush
