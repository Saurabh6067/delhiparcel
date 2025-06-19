<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/delivery-boy-dashboard') }}" class="brand-link">
        @if (!empty($data->type_logo))
            <img src="{{ asset($data->type_logo) }}" alt="parcel logo" class="brand-image img-circle elevation-3"
                style="opacity: .8">
        @else
            <img src="{{ asset('admin/dist/img/AdminLTELogo.png') }}" alt="parcel logo"
                class="brand-image img-circle elevation-3" style="opacity: .8">
        @endif
        {{-- <span class="brand-text font-weight-light">Delhi Parcel</span> --}}
        <span class="brand-text font-weight-light">{{ $deliveryPerson->name ?? 'Delhi Parcel' }}</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('/delivery-boy-dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    {{-- <a href="{{ route('delivery.boy.totalOrders') }}" class="nav-link"> --}}
                        <a href="{{ url('/delivery-boy-order-details/' . 'totalCompleteOrder') }}" class="nav-link">
                            <i class="nav-icon fas fa-box"></i>
                            <p>
                                Total Orders
                            </p>
                        </a>
                </li>
                <!-- <li class="nav-item">
                    <a href="{{ route('delivery.boy.codHistory') }}" class="nav-link">
                        <i class="nav-icon fas fa-solid fa-money-bill"></i>
                        <p>
                           Wallet
                        </p>
                    </a>
                </li> -->
                <li class="nav-item">
                    <a href="{{ route('delivery.boy.wallet') }}" class="nav-link ">
                        <i class="nav-icon fas fa-solid fa-wallet"></i>
                        <p>
                            COD History
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('delivery.boy.qrcode') }}" class="nav-link ">
                        <i class="nav-icon fas fa-qrcode"></i>
                        <p>
                            Scan QR
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('delivery.boy.myearning') }}" class="nav-link ">
                        <i class="nav-icon fas fa-qrcode"></i>
                        <p>
                            My Earning
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('delivery.boy.setting') }}" class="nav-link ">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Settings
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('delivery.boy.logout') }}" class="nav-link ">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>