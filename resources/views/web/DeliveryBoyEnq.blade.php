@extends('web.layout.main')

<style>
    label{
        font-weight: 800;
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
            <h2 class="text-center contact-form-title mb-4">Delivery Boy Form</h2>
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
                            <label for="phone" class="form-label">Mobile Number</label>
                            <input type="number" pattern="[6789][0-9]{9}" class="form-control contact-form-input w-100"
                                id="mobile" name="mobile" placeholder="Enter your Mobile Number">
                        </div>
                        
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="mb-3">
                            <label for="fullName" class="form-label">Qualification</label>
                            <input type="text" class="form-control contact-form-input w-100" id="qualification"
                                name="qualification" placeholder="Qualification">
                        </div>
                         <div class="mb-3">
                            <label for="fullName" class="form-label">Experience</label>
                            <input type="text" class="form-control contact-form-input w-100" id="experience"
                                name="experience" placeholder="Experience">
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
                var qualification = $('#qualification').val();
                var experience = $('#experience').val();

                if (!name) {
                    Toast("error", "Name is required");
                    return;
                }
                if (!mobile) {
                    Toast("error", "MObile is required");
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
                
                var formData = new FormData(this);
                $.ajax({
                    url: "{{ route('web.addDeliveryBoyEnq') }}",
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
