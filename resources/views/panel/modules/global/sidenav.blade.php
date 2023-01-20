<div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">
    <div class="brand flex-column-auto" id="kt_brand">
        <a href="{{ route('admin.dashboard') }}" class="brand-logo">
            <img id="sidenavLogo" alt="Logo" src="{{ secure_asset('site/assets/images/logo.svg')}}" />
        </a>
        <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
            <span class="svg-icon svg-icon svg-icon-xl">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                    height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24" />
                        <path
                            d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z"
                            fill="#000000" fill-rule="nonzero"
                            transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999)" />
                        <path
                            d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z"
                            fill="#000000" fill-rule="nonzero" opacity="0.3"
                            transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999)" />
                    </g>
                </svg>
            </span>
        </button>
    </div>
    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
        <div id="kt_aside_menu" class="aside-menu" data-menu-vertical="1" data-menu-scroll="1"
            data-menu-dropdown-timeout="500">
            <ul class="menu-nav">
                <li class="menu-item {{ Request::segment(1)== null ? 'menu-item-active' : '' }}" aria-haspopup="true">
                    <a href="{{ route('admin.dashboard') }}" class="menu-link">
                        <i class="menu-icon flaticon2-grids"></i>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="menu-item {{ Request::segment(1)== 'brands' ? 'menu-item-active' : '' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.brands.home') }}" class="menu-link">
                        <i class="menu-icon
                        flaticon-map"></i>
                        <span class="menu-text">Brands</span>
                    </a>
                </li>
                <li class="menu-item menu-item-submenu {{ Request::segment(1) == 'attributes' || Request::segment(1) == 'attribute_groups' ? 'menu-item-open' : '' }}"
                    aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <i class="menu-icon flaticon2-document"></i>
                        <span class="menu-text">Attribute Management</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item {{ Request::segment(1) == 'attribute_groups' ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('admin.attribute_groups.home') }}" class="menu-link">
                                    <span class="menu-text">Atttribute Groups</span>
                                </a>
                            </li>
                            <li class="menu-item {{ Request::segment(1) == 'attributes' ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('admin.attributes.home') }}" class="menu-link">
                                    <span class="menu-text">Atttributes</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-item {{ Request::segment(1) == 'categories' ? 'menu-item-active' : '' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.categories.home') }}" class="menu-link">
                        <i class="menu-icon flaticon2-list-1"></i>
                        <span class="menu-text">Categories</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::segment(1) == 'bank-management' ? 'menu-item-active' : '' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.bank_management.home') }}" class="menu-link">
                        <i class="menu-icon flaticon2-graphic"></i>
                        <span class="menu-text">Bank Management</span>
                    </a>
                </li>
                <li class="menu-item menu-item-submenu {{ Request::segment(1) == 'currencies' || Request::segment(1) == 'exchange-rates' ? 'menu-item-open' : '' }}"
                    aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <i class="menu-icon flaticon2-document"></i>
                        <span class="menu-text">Currency Management</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item {{ Request::segment(1) == 'currencies' ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('admin.currency.home') }}" class="menu-link">
                                    <span class="menu-text">Currencies</span>
                                </a>
                            </li>
                            <li class="menu-item {{ Request::segment(1) == 'exchange-rates' ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('admin.exchange_rates.home') }}" class="menu-link">
                                    <span class="menu-text">Exchange Rates</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-item {{ Request::segment(1) == 'listings' ? 'menu-item-active' : '' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.listings.home') }}" class="menu-link">
                        <i class="menu-icon flaticon2-file"></i>
                        <span class="menu-text">Listings</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::segment(1) == 'locations' ? 'menu-item-active' : '' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.locations.home') }}" class="menu-link">
                        <i class="menu-icon flaticon-map-location"></i>
                        <span class="menu-text">Locations</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::segment(1) == 'messages' ? 'menu-item-active' : '' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.messages.home') }}" class="menu-link">
                        <i class="menu-icon flaticon-multimedia"></i>
                        <span class="menu-text">Messages</span>
                    </a>
                </li>
                <li class="menu-item menu-item-submenu {{ Request::segment(1) == 'users' || Request::segment(1) == 'user_types' ? 'menu-item-open' : '' }}"
                    aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <i class="menu-icon flaticon-users-1"></i>
                        <span class="menu-text">Users Management</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item {{ request()->type_guid == 'c8423db6-300e-42fa-a103-c5b62e388f98' ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('admin.users.home') }}?&type_guid=c8423db6-300e-42fa-a103-c5b62e388f98"
                                    class="menu-link">
                                    <span class="menu-text">Standart</span>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->type_guid == '5d840a0f-c539-4257-955d-a375215ea307' ? 'menu-item-active' : '' }}"
                                aria-haspopup="true">
                                <a href="{{ route('admin.users.home') }}?&type_guid=5d840a0f-c539-4257-955d-a375215ea307"
                                    class="menu-link">
                                    <span class="menu-text">Commercial</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-item {{ Request::segment(1) == 'countries' ? 'menu-item-active' : '' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.countries.home') }}" class="menu-link">
                        <i class="menu-icon flaticon2-world"></i>
                        <span class="menu-text">Countries</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::segment(1) == 'admins' ? 'menu-item-active' : '' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.admin.home') }}" class="menu-link">
                        <i class="menu-icon flaticon-user-settings"></i>
                        <span class="menu-text">Admins</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::segment(1) == 'policies' ? 'menu-item-active' : '' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.policies.home') }}" class="menu-link">
                        <i class="menu-icon flaticon2-safe"></i>
                        <span class="menu-text">Policies</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::segment(1) == 'colors' ? 'menu-item-active' : '' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.colors.home') }}" class="menu-link">
                        <i class="menu-icon fas fa-palette"></i>
                        <span class="menu-text">Colors</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::segment(1) == 'loan-requests' ? 'menu-item-active' : '' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.loan.home') }}" class="menu-link">
                        <i class="menu-icon flaticon-graphic-2"></i>
                        <span class="menu-text">Loan Requests</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::segment(1) == 'adv-management' ? 'menu-item-active' : '' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.advm.home') }}" class="menu-link">
                        <i class="menu-icon flaticon2-digital-marketing"></i>
                        <span class="menu-text">Adv Management</span>
                    </a>
                </li>

                <li class="menu-item {{ Request::segment(1) == 'settings' ? 'menu-item-active' : '' }}"
                    aria-haspopup="true">
                    <a href="{{ route('admin.settings.home') }}" class="menu-link">
                        <i class="menu-icon flaticon2-settings"></i>
                        <span class="menu-text">Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
