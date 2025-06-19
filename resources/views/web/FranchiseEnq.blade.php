@extends('web.layout.main')

<style>
    label{
        font-weight: 800;
    }
    .sub_text_under{
        font-size:15px;
        color: red;
    }
    
    @media (max-width: 768px) {
            .form-control{
                height: 46px !important;
            }
        }        
</style>
    


@section('main')
    <div class="container-fluid">

        <section class="services-sec">
            <div class="container">
                <div class="row justify-content-center">
                    
                </div>
            </div>
        </section>

        <div class="container contact-form-container">
            <h2 class="text-center contact-form-title">Frenchies Form</h2>
            <p class="text-center sub_text_under">Earn Upto 1 Lakh or More in a Month</p>
            <form id="add_form">
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Full Name</label>
                            <input type="text" class="form-control contact-form-input w-100" id="name"
                                name="name" placeholder="Enter your name">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Address</label>
                           <textarea name="address" class="form-control" placeholder="Address"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Pincode</label>
                            <input type="text" class="form-control contact-form-input w-100" id="pincode"
                                name="pincode" placeholder="Enter your Pincode">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Mobile No.</label>
                            <input type="number" pattern="[6789][0-9]{9}" class="form-control contact-form-input w-100"
                                id="mobile" name="mobile" placeholder="Enter your Mobile No.">
                        </div>
                        <div class="mb-3">
                            <label for="premises" class="form-label">Premises (Owned or Rented)</label>
                            <select id="premises" name="premises" class="form-control premises">
                                <option selected disabled>-- Select --</option>
                                <option value="Owned">Owned</option>
                                <option value="Rented">Rented</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">No of Delivery Boys</label>
                            <input type="text" class="form-control contact-form-input w-100" id="no_of_delivery_boys"
                                name="no_of_delivery_boys" placeholder="No of Delivery Boys">
                        </div>
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Qualification</label>
                            <input type="text" class="form-control contact-form-input w-100" id="qualification"
                                name="qualification" placeholder="Qualification">
                        </div>
                         <div class="mb-3">
                            <label for="fullName" class="form-label">Experience</label>
                            <select id="experience" name="experience" class="form-control">
                                <option selected disabled>-- Select --</option>
                                <option value="No Experience">No Experience</option>
                                <option value="1-2 Year">1-2 Year</option>
                                <option value="2-3 Years">2-3 Years</option>
                                <option value="More than 3 Years">More than 3 Years</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Reference (if any)</label>
                            <input type="text" class="form-control contact-form-input w-100" id="reference"
                                name="reference" placeholder="Reference">
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <button data-label="Register" class="rainbow-hover text-center mt-2">
                        <span class="sp">Submit</span>
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            // add inquiry 
            $('#add_form').on('submit', function(event) {
                event.preventDefault();
                // Front-end validation
                var name = $('#name').val();
                var mobile = $('#mobile').val();
                var pincode = $('#pincode').val();
                var qualification = $('#qualification').val();
                var experience = $('#experience').val();
                var no_of_delivery_boys = $('#no_of_delivery_boys').val();

                if (!name) {
                    Toast("error", "Name is required");
                    return;
                }
                if (!mobile) {
                    Toast("error", "Mobile is required");
                    return;
                }
                if (!pincode) {
                    Toast("error", "Pincode is required");
                    return;
                }
                if (!qualification) {
                    Toast("error", "Qualification is required");
                    return;
                }
                if (!experience) {
                    Toast("error", "Experience is required");
                    return;
                }
                if (!no_of_delivery_boys) {
                    Toast("error", "No of Delivery Boys is required");
                    return;
                }
                
                var formData = new FormData(this);
                $.ajax({
                    url: "{{ route('web.addFranchiseEnq') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    dataType: "json",
                    success: function(response) {
                        $('#add_form')[0].reset();
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
