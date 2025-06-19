<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/seller-dashboard') }}" class="brand-link">
        @if (!empty($data->type_logo))
            <img src="{{ asset($data->type_logo) }}" alt="parcel logo" class="brand-image img-circle elevation-3"
                style="opacity: .8">
        @else
            <img src="{{ asset('admin/dist/img/AdminLTELogo.png') }}" alt="parcel logo"
                class="brand-image img-circle elevation-3" style="opacity: .8">
        @endif
        {{-- <span class="brand-text font-weight-light">Seller | Delhi Parcel</span> --}}
        <span class="brand-text font-weight-light">{{ $deliveryPerson->fullname ?? 'Delhi Parcel' }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('/seller-dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('seller.addDeliveryOrder') }}" class="nav-link">
                        <i class="nav-icon fas fa-solid fa-truck"></i>
                        <p>
                            Add Delivery Order
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('seller.addPickupOrder') }}" class="nav-link">
                        <i class="nav-icon fas fa-solid fa-truck-pickup"></i>
                        <p>
                            Add Pickup Order
                        </p>
                    </a>
                </li>
              
               
                <li class="nav-item">
                    <a href="{{ route('seller.allCodHistory') }}" class="nav-link">
                        <i class="nav-icon fas fa-solid fa-money-bill"></i>
                        <p>
                            All COD History
                        </p>
                    </a>
                </li>
               
              
                <li class="nav-item">
                    <a href="{{ route('seller.wallet') }}" class="nav-link ">
                        <i class="nav-icon fas fa-solid fa-wallet"></i>
                        <p>
                            Wallet
                        </p>
                    </a>
                </li>
                
                 <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Manage Reports
                            <i class="fas fa-angle-left right"></i>
                            <span class="badge badge-info right"></span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/monthly-seller-invoice') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Monthly Invoice</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/seller-cod-sattlement') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Cod Settlement </p>
                            </a>
                        </li>
                        <!--<li class="nav-item">-->
                        <!--    <a href="{{ route('admin.standardServices') }}" class="nav-link">-->
                        <!--        <i class="far fa-circle nav-icon"></i>-->
                        <!--        <p>Standard Services</p>-->
                        <!--    </a>-->
                        <!--</li>-->
                    </ul>
                </li>
                
                 <li class="nav-item">
                    <a href="{{ route('seller.setting') }}" class="nav-link ">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Settings
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('seller.logout') }}" class="nav-link ">
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
