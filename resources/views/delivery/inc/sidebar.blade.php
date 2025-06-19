<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/delivery-dashboard') }}" class="brand-link">
        @if (!empty($data->type_logo))
            <img src="{{ asset($data->type_logo) }}" alt="parcel logo" class="brand-image img-circle elevation-3"
                style="opacity: .8">
        @else
            <img src="{{ asset('admin/dist/img/AdminLTELogo.png') }}" alt="parcel logo"
                class="brand-image img-circle elevation-3" style="opacity: .8">
        @endif
        <span class="brand-text font-weight-light">{{ $deliveryPerson->fullname ?? 'Delhi Parcel' }}</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('/delivery-dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-item ">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-shipping-fast"></i>
                        <p>
                            Manage Delivery Boy
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('delivery.addDeliveryBoy') }}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Delivery Boy</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('delivery.allDeliveryBoy') }}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Delivery Boy</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!--<li class="nav-item">-->
                <!--    <a href="{{ route('delivery.DirectOrders') }}" class="nav-link">-->
                <!--        <i class="nav-icon fas fa-box"></i>-->
                <!--        <p>-->
                <!--            Direct Orders-->
                <!--        </p>-->
                <!--    </a>-->
                <!--</li>-->
                <!--<li class="nav-item ">-->
                <!--    <a href="#" class="nav-link ">-->
                <!--        <i class="nav-icon fas fa-box"></i>-->
                <!--        <p>-->
                <!--            Other Branch Order-->
                <!--            <i class="right fas fa-angle-left"></i>-->
                <!--        </p>-->
                <!--    </a>-->
                <!--    <ul class="nav nav-treeview">-->
                <!--        <li class="nav-item">-->
                <!--            <a href="{{ route('delivery.other.branch.order') }}" class="nav-link ">-->
                <!--                <i class="far fa-circle nav-icon"></i>-->
                <!--                <p>All Order</p>-->
                <!--            </a>-->
                <!--        </li>-->
                <!--        <li class="nav-item">-->
                <!--            <a href="{{ route('delivery.other.branch.order.status') }}" class="nav-link ">-->
                <!--                <i class="far fa-circle nav-icon"></i>-->
                <!--                <p>All Order Status</p>-->
                <!--            </a>-->
                <!--        </li>-->
                <!--        <li class="nav-item">-->
                <!--            <a href="{{ route('delivery.order-pin-code-orders') }}" class="nav-link ">-->
                <!--                <i class="far fa-circle nav-icon"></i>-->
                <!--                <p>My Order</p>-->
                <!--            </a>-->
                <!--        </li>-->
                <!--    </ul>-->
                <!--</li>-->
                
                <li class="nav-item">
                    <a href="{{ route('delivery.other.branch.order') }}" class="nav-link">
                        <i class="nav-icon fas fa-solid fas fa-box"></i>
                        <p>
                           Other Branch Order
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('delivery.allCodHistory') }}" class="nav-link">
                        <i class="nav-icon fas fa-solid fa-money-bill"></i>
                        <p>
                            All COD History
                        </p>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a href="{{ route('delivery.wallet') }}" class="nav-link ">
                        <i class="nav-icon fas fa-solid fa-wallet"></i>
                        <p>
                            Wallet
                        </p>
                    </a>
                </li> -->
                <li class="nav-item">
                    <a href="{{ route('delivery.setting') }}" class="nav-link ">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Settings
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('delivery.logout') }}" class="nav-link ">
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
