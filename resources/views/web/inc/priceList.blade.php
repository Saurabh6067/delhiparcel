<div class="container-fluid" id="pricing">
    <div class="row justify-content-center pt-5 pb-2">
        <div class="col-lg-8 col-md-8">
            <div class="sec-title">
                <h4 class="text-center">
                    <span class="divhead">
                        <span class="text-success">P</span>
                        <span class="text-danger">ricing </span>
                        <span class="text-danger">R</span>
                        <span class="text-success">ates</span>
                    </span>
                </h4>
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
                @foreach ($se as $key => $data)
                    <div class="price-item">
                        <dt>{{ $data->title }}</dt>
                        <dd>{{ $data->price }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>

        <!--  Second column -->
        <div class="col-lg-4">
            <div class="serhead">
                <h4 class="text-center  pb-2">Express Service</h4>
                <p class="text-center">(Delivery Within Same Days)</p>
            </div>
            <dl class="price-list">
                @forelse ($ex as $data)
                    <div class="price-item">
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
