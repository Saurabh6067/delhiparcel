<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

<style>
    .img_super{
        height: 423px;
        width:423px;
    }
    
    @media(max-width:768px){
         .img_super{
        height:300px;
        width:100%;
    }
    }
    
    .price-item{
        justify-content: space-between !important;
        padding: 10px 20px 0px 20px;
    }
</style>

<div class="container-fluid" id="pricing">
    <div class="row justify-content-center mt-5">
                    <div class="col-lg-8 col-md-8">
                        <div class="sec-title">

                            <div class="sec-title">
                                <h2><span>Pricing Rates</span></h2>
                            </div>
                        </div>
                    </div>
                </div>
    <div class="row">

        <!--  First column -->
        <div class="col-lg-4">
            <div class="serhead">
                <h4 class="text-center  pb-2">Super Express Service</h4>
                <p class="text-center">(Delivery Within 4 Hours)</p>
            </div>
            <dl class="price-list">
                <div class="d-flex justify-content-center">
                    <img class="img_super" src="{{ asset('web/images/sup2.png') }}"/>
                   
                </div>
            </dl>
        </div>

        <!--  Second column -->
        <div class="col-lg-4">
            <div class="serhead">
                <h4 class="text-center  pb-2">Express Service</h4>
                <p class="text-center">(Same Day Delivery)</p>
            </div>
            <dl class="price-list">
                @forelse ($ex as $data)
                    <div class="price-item ">
                        <dt>{{ $data->title }}</dt>
                        <dd>{{ $data->price }}</dd>
                    </div>
                @empty
                    <p class="text-center">No express services available at the moment.</p>
                @endforelse
            </dl>
        </div>

        <!--  Third column -->
        <div class="col-lg-4">
            <div class="serhead">
                <h4 class="text-center pb-2">Standard Service</h4>
                <p class="text-center ">(Delivery Within 2 Days)</p>
            </div>
            <dl class="price-list">
                @forelse ($ss as $data)
                    <div class="price-item">
                        <dt>{{ $data->title }}</dt>
                        <dd>{{ $data->price }}</dd>
                    </div>
                @empty
                    <p class="text-center">No standard services available at the moment.</p>
                @endforelse
            </dl>
        </div>
    </div>
</div>
