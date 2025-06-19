<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/admin-dashboard') }}" class="brand-link">
        <img src="{{ asset('admin/dist/img/AdminLTELogo.png') }}" alt="parcel logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Admin | Delhi Parcel</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('/admin-dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-copy"></i>
                        <p>
                            Manage Services
                            <i class="fas fa-angle-left right"></i>
                            <span class="badge badge-info right"></span>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.superExpress') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Super Express</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.expressServices') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Express Services</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.standardServices') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Standard Services</p>
                            </a>
                        </li>
                         <li class="nav-item">
                            <a href="{{ url('/service_estimited_time') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Cutoff Time</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('amdin.pinCodes') }}" class="nav-link">
                        <i class="nav-icon fas fa-map-marker-alt"></i>
                        <p>
                            Manage Pin Codes
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.category') }}" class="nav-link">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>
                            Manage Categories
                        </p>
                    </a>
                </li>

                <li class="nav-item ">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-store"></i>
                        <p>
                            Manage Branch
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.branch') }}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Branch</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.allBranch') }}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <!--<p>All Branch</p>-->
                                <p>All Delivery Branch</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.allBookingBranch') }}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Booking Branch</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item ">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-store"></i>
                        <p>
                            Manage Seller
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.seller.branch') }}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Sellers</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.seller.allBranch') }}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Sellers</p>
                            </a>
                        </li>
                    </ul>
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
                            <a href="{{ route('admin.addDeliveryBoy') }}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Delivery Boy</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.allDeliveryBoy') }}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Delivery Boy</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.DirectOrders') }}" class="nav-link">
                        <i class="nav-icon fas fa-box"></i>
                        <p>
                            Direct Orders
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.allCodHistory') }}" class="nav-link">
                        <i class="nav-icon fas fa-solid fa-money-bill"></i>
                        <p>
                            All COD History
                        </p>
                    </a>
                </li>
                <!-- <li class="nav-item">-->
                <!--    <a href="{{ url('/admin-cod-sattlement') }}" class="nav-link">-->
                <!--        <i class="nav-icon fas fa-solid fa-money-bill"></i>-->
                <!--        <p>-->
                <!--            All COD Sattlement-->
                <!--        </p>-->
                <!--    </a>-->
                <!--</li>-->
                
                 <li class="nav-item ">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-shipping-fast"></i>
                        <p>
                            Manage Report
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/monthly-admin-invoice') }}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Monthly Invoice</p>
                            </a>
                        </li>
                        <!--<li class="nav-item">-->
                        <!--    <a href="{{ route('admin.allDeliveryBoy') }}" class="nav-link ">-->
                        <!--        <i class="far fa-circle nav-icon"></i>-->
                        <!--        <p>All Delivery Boy</p>-->
                        <!--    </a>-->
                        <!--</li>-->
                    </ul>
                </li>
                
                <li class="nav-header">Website</li>
                <li class="nav-item">
                    <a href="{{ route('admin.deliveryboy_enq') }}" class="nav-link">
                        <i class="nav-icon fas fa-question-circle"></i>
                        <p>
                            Delivery Boy Form
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.deliveryboy_enq') }}" class="nav-link">
                        <i class="nav-icon fas fa-question-circle"></i>
                        <p>
                            Franchise Form
                        </p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.allEnquiry') }}" class="nav-link">
                        <i class="nav-icon fas fa-question-circle"></i>
                        <p>
                            Inquiry Messages
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.feedback') }}" class="nav-link">
                        <i class="nav-icon fas fa-solid fa-comments"></i>
                        <p>
                            Manage FeedBack
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.setting') }}" class="nav-link ">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Settings
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.logout') }}" class="nav-link ">
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
